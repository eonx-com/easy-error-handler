<?php
declare(strict_types=1);

namespace EonX\EasyErrorHandler\Interfaces;

use EonX\EasyUtils\Interfaces\HasPriorityInterface;
use Throwable;

interface ErrorResponseBuilderInterface extends HasPriorityInterface
{
    public function buildData(Throwable $throwable, array $data): array;

    public function buildHeaders(Throwable $throwable, ?array $headers = null): ?array;

    public function buildStatusCode(Throwable $throwable, ?int $statusCode = null): ?int;
}
