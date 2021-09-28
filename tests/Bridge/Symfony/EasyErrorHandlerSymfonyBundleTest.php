<?php

declare(strict_types=1);

namespace EonX\EasyErrorHandler\Tests\Bridge\Symfony;

use EonX\EasyErrorHandler\Interfaces\ErrorHandlerInterface;

final class EasyErrorHandlerSymfonyBundleTest extends AbstractSymfonyTestCase
{
    public function testSanity(): void
    {
        $container = $this->getKernel([__DIR__ . '/Fixtures/config/exception_log_levels.yaml'])->getContainer();

        self::assertInstanceOf(ErrorHandlerInterface::class, $container->get(ErrorHandlerInterface::class));
    }
}