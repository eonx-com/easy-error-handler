<?php

declare(strict_types=1);

namespace EonX\EasyErrorHandler\Reporters;

use EonX\EasyErrorHandler\Interfaces\ErrorReporterInterface;
use EonX\EasyErrorHandler\Traits\DealsWithErrorLogLevelTrait;

abstract class AbstractErrorReporter implements ErrorReporterInterface
{
    use DealsWithErrorLogLevelTrait;

    /**
     * @var int
     */
    private $priority;

    public function __construct(?int $priority = null)
    {
        $this->priority = $priority ?? 0;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
