<?php

declare(strict_types=1);

/**
 * @author Masaru Yamagishi <m.yamagishi90+git@gmail.com>
 * @license Apache-2.0
 */

namespace Shibare\HttpServer;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Shibare\HttpFactory\ServerRequestFactory;
use Shibare\HttpFactory\StreamFactory;
use Shibare\HttpFactory\UploadedFileFactory;
use Shibare\HttpMessage\Response;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;

/**
 * RoadRunner HTTP Dispatcher
 * @package Shibare\HttpServer
 * @link https://docs.roadrunner.dev/docs/php-worker/worker
 * @codeCoverageIgnore
 */
class RoadRunnerHttpDispatcher
{
    public function __construct()
    {
        if (\class_exists(PSR7Worker::class) === false) {
            throw new \RuntimeException('You need to require "spiral/roadrunner-http" package');
        }
    }

    public function serve(LoggerInterface $logger, RequestHandlerInterface $handler): void
    {
        $worker = new PSR7Worker(
            Worker::create(interceptSideEffects: true, logger: $logger),
            new ServerRequestFactory(),
            new StreamFactory(),
            new UploadedFileFactory(),
        );

        while (true) {
            try {
                $request = $worker->waitRequest();
                if ($request === null) {
                    break; // @codeCoverageIgnore
                }
            } catch (\Throwable $e) {
                // @codeCoverageIgnoreStart
                $logger->error('Failed to receive request', ['exception' => $e]);
                $worker->respond(new Response(400));
                continue;
                // @codeCoverageIgnoreEnd
            }

            \assert($request instanceof ServerRequestInterface);

            try {
                $response = $handler->handle($request);
                $worker->respond($response);
            } catch (\Throwable $e) {
                $logger->error('Unhandled throwable', ['exception' => $e]);
                $worker->respond(new Response(500));
            }
        }
    }
}
