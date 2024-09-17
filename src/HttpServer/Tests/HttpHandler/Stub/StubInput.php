<?php

declare(strict_types=1);

/**
 * @license MIT
 */

namespace Shibare\HttpServer\Tests\HttpHandler\Stub;

use Shibare\Contracts\HttpServer\ServerRequestAwareInterface;
use Shibare\HttpServer\HttpHandler\ServerRequestAwareTrait;

final class StubInput implements ServerRequestAwareInterface
{
    use ServerRequestAwareTrait;

    /**
     * @param string $name
     * @param int $age
     * @param float $height
     * @param bool $is_student
     * @param null|string $school
     * @param mixed[] $hobbies
     * @param mixed $extra
     * @return void
     */
    public function __construct(
        public string $name,
        public int $age,
        public float $height,
        public bool $is_student = false,
        public ?string $school = null,
        public array $hobbies = [],
        public $extra = null,
    ) {}
}
