<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <akai_inu@live.jp>
 * @license Apache-2.0
 */

namespace Rayleigh\HttpServer;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use Rayleigh\HttpMessage\MalformedUriException;
use Rayleigh\HttpMessage\ServerRequest;
use Rayleigh\HttpMessage\UploadedFile;
use Rayleigh\HttpMessage\Uri;
use RuntimeException;

/**
 * PSR-7 ServerRequest builds from global variables
 * @package Rayleigh\HttpServer
 * @link https://github.com/laminas/laminas-diactoros/blob/3.4.x/src/ServerRequestFactory.php
 * @example
 * ```php
 * $middlewares = [snip...];
 * $server_request = TraditionalServerRequestBuilder::build();
 * $response = (new ServerRequestRunner($middlewares))->handle($server_request);
 * (new ResponseEmitter(new Emitter()))->emit($response);
 * ```
 */
/* final readonly */ class TraditionalServerRequestBuilder
{
    protected const VALID_HTTP_METHODS = ['GET', 'POST', 'HEAD', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'];

    /** @var bool Whether to override HTTP method from Header or form */
    protected static bool $override_method = false;

    /**
     * This class cannot be instantiated, use TraditionalServerRequestBuilder::build method instead
     * Only in tests, you may use this constructor
     * @param array<non-empty-string, mixed> $server
     * @param array<non-empty-string, mixed> $cookie
     * @param array<non-empty-string, mixed> $files
     * @param array<non-empty-string, mixed> $get
     * @param array<non-empty-string, mixed> $post
     * @param array<non-empty-string, mixed> $request
     */
    protected function __construct(
        protected array $server,
        protected array $cookie,
        protected array $files,
        protected array $get,
        protected array $post,
        protected array $request,
    ) {}

    /**
     * Build ServerRequest from superglobal variables
     * @return ServerRequestInterface
     * @codeCoverageIgnore
     */
    public static function buildFromSuperGlobals(): ServerRequestInterface
    {
        return self::build(
            $_SERVER,
            $_COOKIE,
            $_FILES,
            $_GET,
            $_POST,
            $_REQUEST,
        );
    }

    /**
     * Enable override method from Header or form
     * @return void
     */
    public static function enableOverrideMethod(): void
    {
        self::$override_method = true;
    }

    /**
     * Build ServerRequest from provided variables
     * @param array<array-key, mixed> $server
     * @param array<array-key, mixed> $cookie
     * @param array<array-key, mixed> $files
     * @param array<array-key, mixed> $get
     * @param array<array-key, mixed> $post
     * @param array<array-key, mixed> $request
     * @return ServerRequestInterface
     */
    public static function build(
        array $server,
        array $cookie,
        array $files,
        array $get,
        array $post,
        array $request,
    ): ServerRequestInterface {
        $builder = new self(
            self::normalizeKey($server),
            self::normalizeKey($cookie),
            self::normalizeKey($files),
            self::normalizeKey($get),
            self::normalizeKey($post),
            self::normalizeKey($request),
        );
        $method = $builder->buildMethod();
        $overrided_method = $builder->buildOverrideMethod();
        if (self::$override_method) {
            $method = $overrided_method ?? $method;
        }
        self::$override_method = false; // reset
        $builder->normalizeByMethod($method);
        $protocol_version = $builder->buildProtocolVersion();

        return new ServerRequest(
            method: $method,
            uri: self::generateUri($server),
            headers: $builder->buildHeaders(),
            body: self::generateBody($method),
            protocol_version: $protocol_version,
            server_params: $builder->server,
            cookie_params: $builder->cookie,
            query_params: $builder->get,
            uploaded_files: $builder->buildUploadedFiles(),
            parsed_body: null, // default null
            attributes: [], // default empty
        );
    }

    /**
     * Find HTTP method
     * @return string
     * @see https://github.com/laminas/laminas-diactoros/blob/dfc42c1bddb1b81f11f8093b1daa68a13f481146/src/functions/marshal_method_from_sapi.php
     */
    public function buildMethod(): string
    {
        $request_method = self::findStringFromArray($this->server, 'REQUEST_METHOD');

        if (\is_null($request_method)) {
            return 'GET'; // @codeCoverageIgnore default
        }

        return $request_method;
    }

    /**
     * Find override method from the application
     * @return null|string
     * @see https://github.com/symfony/symfony/blob/e99e052f19c8bfa21d48ce2e768d794a113e873e/src/Symfony/Component/HttpFoundation/Request.php#L1135
     */
    public function buildOverrideMethod(): ?string
    {
        // check override method
        $overrided_method = self::findStringFromArray($this->server, 'HTTP_X_HTTP_METHOD_OVERRIDE');
        if (\is_string($overrided_method) && \in_array($overrided_method, self::VALID_HTTP_METHODS, true)) {
            return $overrided_method;
        }

        // check form method
        $get_form_method = self::findStringFromArray($this->get, '_method');
        if (\is_string($get_form_method) && \in_array($get_form_method, self::VALID_HTTP_METHODS, true)) {
            return $get_form_method;
        }

        $post_form_method = self::findStringFromArray($this->post, '_method');
        if (\is_string($post_form_method) && \in_array($post_form_method, self::VALID_HTTP_METHODS, true)) {
            return $post_form_method;
        }

        return null;
    }

    public function buildProtocolVersion(): string
    {
        $server_protocol = self::findStringFromArray($this->server, 'SERVER_PROTOCOL');
        if ($server_protocol !== null && \str_starts_with($server_protocol, 'HTTP/')) {
            return \substr($server_protocol, 5);
        }
        throw new RuntimeException(\sprintf('Unknown server protocol: %s', $server_protocol));
    }

    /**
     * Generate headers from $_SERVER['HTTP_*']
     * @return array<string, scalar[]>
     */
    public function buildHeaders(): array
    {
        $headers = [];

        if ($content_type = self::findStringFromArray($this->server, 'CONTENT_TYPE')) {
            $headers['Content-Type'] = [$content_type];
        }
        if ($content_length = self::findStringFromArray($this->server, 'CONTENT_LENGTH')) {
            $headers['Content-Length'] = [$content_length];
        }
        if ($content_md5 = self::findStringFromArray($this->server, 'CONTENT_MD5')) {
            $headers['Content-Md5'] = [$content_md5];
        }

        foreach ($this->server as $key => $value) {
            if (\str_starts_with($key, 'HTTP_')) {
                $header_name = \ucwords(\strtolower(\str_replace('_', '-', \substr($key, 5))), '-');
                if (\array_key_exists($header_name, $headers) === false) {
                    $headers[$header_name] = [];
                }
                if (\is_scalar($value)) {
                    $headers[$header_name][] = $value;
                }
            }
        }

        return $headers;
    }

    /**
     * Recursively build uploaded files
     * @return array<array-key, mixed>
     */
    public function buildUploadedFiles(): array
    {
        $normalized = [];

        /**
         * @param array<array-key, mixed> $tmp_names
         * @param array<array-key, mixed> $sizes
         * @param array<array-key, mixed> $errors
         * @param array<array-key, mixed>|null $names
         * @param array<array-key, mixed>|null $types
         * @return array<array-key, mixed>
         */
        $recursivelyNormalizeTree = static function (
            array $tmp_names,
            array $sizes,
            array $errors,
            ?array $names,
            ?array $types,
        ) use (&$recursivelyNormalizeTree): array {
            $normalized = [];

            foreach ($tmp_names as $key => $value) {
                if (\is_array($value)) {
                    \assert(\array_key_exists($key, $sizes));
                    \assert(\array_key_exists($key, $errors));
                    // recursive
                    $normalized[$key] = $recursivelyNormalizeTree(
                        $tmp_names[$key],
                        $sizes[$key],
                        $errors[$key],
                        $names[$key] ?? null,
                        $types[$key] ?? null,
                    );
                    continue;
                }
                \assert(\array_key_exists($key, $sizes));
                \assert(\array_key_exists($key, $errors));
                \assert(\is_int($sizes[$key]));
                \assert(\is_int($errors[$key]));
                $normalized[$key] = new UploadedFile(
                    $tmp_names[$key],
                    $sizes[$key],
                    $errors[$key],
                    $names[$key] ?? null,
                    $types[$key] ?? null,
                );
            }

            return $normalized;
        };

        /**
         * @param array<array-key, mixed> $files
         * @return array<array-key, mixed>
         */
        $normalizeUploadedFileTree = static function (array $files) use ($recursivelyNormalizeTree): array {
            if (!\array_key_exists('tmp_name', $files) || !\is_array($files['tmp_name'])) {
                throw new \InvalidArgumentException('Invalid uploaded files'); // @codeCoverageIgnore
            } elseif (!\array_key_exists('size', $files) || !\is_array($files['size'])) {
                throw new \InvalidArgumentException('Invalid uploaded files'); // @codeCoverageIgnore
            } elseif (!\array_key_exists('error', $files) || !\is_array($files['error'])) {
                throw new \InvalidArgumentException('Invalid uploaded files'); // @codeCoverageIgnore
            }

            return $recursivelyNormalizeTree(
                $files['tmp_name'],
                $files['size'],
                $files['error'],
                $files['name'] ?? null,
                $files['type'] ?? null,
            );
        };

        foreach ($this->files as $key => $value) {
            if ($value instanceof UploadedFileInterface) {
                $normalized[$key] = $value; // @codeCoverageIgnore
                continue; // @codeCoverageIgnore
            }

            if (\is_array($value)) {
                if (\array_key_exists('tmp_name', $value)) {
                    if (\is_array($value['tmp_name'])) {
                        // recursive
                        $normalized[$key] = $normalizeUploadedFileTree($value);
                        continue;
                    }
                    \assert(\array_key_exists('size', $value));
                    \assert(\is_int($value['size']));
                    \assert(\array_key_exists('error', $value));
                    \assert(\is_int($value['error']));
                    $normalized[$key] = new UploadedFile(
                        $value['tmp_name'],
                        $value['size'],
                        $value['error'],
                        $value['name'] ?? null,
                        $value['type'] ?? null,
                    );
                    continue;
                }
            }
        }

        return $normalized;
    }

    private function normalizeByMethod(string $method): void
    {
        switch (\strtoupper($method)) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                if (\array_key_exists('CONTENT_TYPE', $this->server) === false) {
                    $this->server['CONTENT_TYPE'] = 'application/x-www-form-urlencoded'; // Override default content type
                }
                break;
            case 'PATCH':
                break; // Do nothing
            default:
                $this->post = []; // clear post
                $this->request = []; // clear request
                break;
        }
    }

    /**
     * Normalize superglobals
     * @param array<array-key, mixed> $arr
     * @return array<non-empty-string, mixed>
     */
    private static function normalizeKey(array $arr): array
    {
        /** @var array<non-empty-string, mixed> $normalized */
        $normalized = [];
        foreach ($arr as $key => $value) {
            if (\is_string($key) === false) {
                continue; // ignore integer key
            }
            if ($key === '') {
                continue; // ignore empty key
            }
            $normalized[$key] = $value;
        }
        return $normalized;
    }

    /**
     * find and satisfies the value is string
     * @param array<string, mixed> $arr
     * @param string $key
     * @return ?string
     */
    private static function findStringFromArray(array $arr, string $key): ?string
    {
        if (\array_key_exists($key, $arr)) {
            $value = $arr[$key];
            if (\is_string($value)) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @param array<string, mixed> $server
     * @return UriInterface
     * @throws MalformedUriException
     * @link http://www.faqs.org/rfcs/rfc3875.html
     */
    private static function generateUri(array $server): UriInterface
    {
        $uri = new Uri();
        $server_protocol = $server['SERVER_PROTOCOL'] ?? '';
        $https = $server['HTTPS'] ?? $server['https'] ?? '';
        if (\is_string($https) && $https !== 'off') {
            $uri = $uri->withScheme('https');
        } elseif (\is_string($server_protocol)) {
            if (\str_starts_with($server_protocol, 'HTTP/')) {
                $uri = $uri->withScheme('http');
            }
        }
        $user = $server['PHP_AUTH_USER'] ?? null;
        $pass = $server['PHP_AUTH_PASS'] ?? $server['PHP_AUTH_PW'] ?? null;
        if (\is_string($user) && (\is_string($pass) || $pass === null)) {
            $uri = $uri->withUserInfo($user, $pass);
        }
        if (\array_key_exists('SERVER_NAME', $server) && \is_string($server['SERVER_NAME'])) {
            $uri = $uri->withHost((string)$server['SERVER_NAME']);
        } else {
            $uri = $uri->withHost('localhost');
        }
        if (\array_key_exists('SERVER_PORT', $server) && \is_numeric($server['SERVER_PORT'])) {
            $uri = $uri->withPort((int)$server['SERVER_PORT']);
        } else {
            $uri = $uri->withPort($uri->getScheme() === 'https' ? 443 : 80);
        }
        if (\array_key_exists('SCRIPT_NAME', $server) && \is_string($server['SCRIPT_NAME'])) {
            $uri = $uri->withPath((string)$server['SCRIPT_NAME']);
        } else {
            $uri = $uri->withPath('/');
        }
        if (\array_key_exists('QUERY_STRING', $server) && \is_string($server['QUERY_STRING'])) {
            $uri = $uri->withQuery((string)$server['QUERY_STRING']);
        } else {
            $uri = $uri->withQuery('');
        }
        return $uri;
    }

    /**
     * @param string $method
     * @return resource|null
     */
    private static function generateBody(string $method): mixed
    {
        $body = match (\strtoupper($method)) {
            'POST', 'PUT', 'DELETE', 'PATCH' => \fopen('php://input', 'r'),
            default => null,
        };
        \assert($body === null || \is_resource($body)); // for phpstan
        return $body;
    }
}
