<?php
declare(strict_types=1);

namespace EonX\EasyErrorHandler\Tests\Unit\Common\Resolver;

use EonX\EasyErrorHandler\Common\Resolver\ErrorDetailsResolver;
use EonX\EasyErrorHandler\Tests\Stub\Translator\TranslatorStub;
use EonX\EasyErrorHandler\Tests\Unit\AbstractUnitTestCase;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\NullLogger;
use Throwable;

final class ErrorDetailsResolverTest extends AbstractUnitTestCase
{
    /**
     * @see testResolveExtendedDetails
     */
    public static function provideResolveExtendedDetailsData(): iterable
    {
        yield 'simple' => [
            'throwable' => new Exception(),
            'assertions' => null,
            'maxDepth' => null,
        ];

        yield 'max depth 0 so no previous' => [
            'throwable' => new Exception('message', 0, new Exception()),
            'assertions' => static function (array $details): void {
                self::assertNull($details['previous_1'] ?? null);
            },
            'maxDepth' => 0,
        ];

        yield 'max depth -1 so infinite' => [
            'throwable' => self::createExceptionChain(100),
            'assertions' => static function (array $details): void {
                self::assertNotNull($details['previous_1'] ?? null);
            },
            'maxDepth' => -1,
        ];

        yield 'all previous in root level' => [
            'throwable' => self::createExceptionChain(2),
            'assertions' => static function (array $details): void {
                self::assertNotNull($details['previous_1'] ?? null);
                self::assertNotNull($details['previous_2'] ?? null);
            },
            'maxDepth' => null,
        ];
    }

    #[DataProvider('provideResolveExtendedDetailsData')]
    public function testResolveExtendedDetails(
        Throwable $throwable,
        ?callable $assertions = null,
        ?int $maxDepth = null,
    ): void {
        $errorDetailsResolver = new ErrorDetailsResolver(new NullLogger(), new TranslatorStub());
        $details = $errorDetailsResolver->resolveExtendedDetails($throwable, $maxDepth);

        self::assertSame($throwable->getCode(), $details['code']);
        self::assertSame($throwable::class, $details['class']);
        self::assertSame($throwable->getFile(), $details['file']);
        self::assertSame($throwable->getLine(), $details['line']);
        self::assertSame($throwable->getMessage(), $details['message']);

        if ($assertions !== null) {
            $assertions($details);
        }
    }

    private static function createExceptionChain(int $max, ?int $current = null, ?Throwable $previous = null): Throwable
    {
        $current ??= 0;
        $previous ??= new Exception();

        if ($max === $current) {
            return $previous;
        }

        return self::createExceptionChain($max, $current + 1, new Exception('message', 0, $previous));
    }
}
