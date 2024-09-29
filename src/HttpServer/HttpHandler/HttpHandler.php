<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpServer\HttpHandler;

use Closure;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionFunction;
use ReflectionNamedType;

class HttpHandler implements RequestHandlerInterface
{
    /** @var class-string $handler_name */
    private ?string $handler_name = null;

    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    /**
     * Set Handler name
     * @param class-string $handler_name
     * @return void
     */
    public function setHandlerName(string $handler_name): void
    {
        $this->handler_name = $handler_name;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (\is_null($this->handler_name)) {
            throw new InvalidHandlerDefinitionException('Handler name not set'); // @codeCoverageIgnore
        }
        if ($this->container->has($this->handler_name) === false) {
            throw new InvalidHandlerDefinitionException(\sprintf('Handler %s not found in container', $this->handler_name));
        }
        $handler = $this->container->get($this->handler_name);
        if (\is_callable($handler) === false) {
            throw new InvalidHandlerDefinitionException(\sprintf('Handler %s must be callable or implement __invoke method', $this->handler_name));
        }
        $ref = new ReflectionFunction(Closure::fromCallable($handler));
        $input_class_name = null;
        foreach ($ref->getParameters() as $param) {
            if ($param->getType() instanceof ReflectionNamedType) {
                $input_class_name = $param->getType()->getName();
                break;
            }
        }
        $args = [];
        if (!\is_null($input_class_name) && \class_exists($input_class_name)) {
            $args['input'] = (new InputGenerator($input_class_name))->generateInput($request);
        }
        $output = $ref->invokeArgs($args);
        $result = (new OutputConverter())->convert($output);

        $response_factory = $this->container->get(ResponseFactoryInterface::class);
        \assert($response_factory instanceof ResponseFactoryInterface);
        $stream_factory = $this->container->get(StreamFactoryInterface::class);
        \assert($stream_factory instanceof StreamFactoryInterface);

        $response = $response_factory->createResponse();
        $stream = $stream_factory->createStream($result);
        $length = \function_exists('mb_strlen') ? \mb_strlen($result) : \strlen($result);

        return $response->withBody($stream)
            ->withHeader('Content-Length', \strval($length));
    }
}
