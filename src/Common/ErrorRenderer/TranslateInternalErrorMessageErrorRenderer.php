<?php
declare(strict_types=1);

namespace EonX\EasyErrorHandler\Common\ErrorRenderer;

use EonX\EasyErrorHandler\Common\Resolver\ErrorDetailsResolverInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Throwable;

final readonly class TranslateInternalErrorMessageErrorRenderer implements ErrorRendererInterface
{
    private const PATTERNS = [
        '<title>%s',
        '%s</h1>',
    ];

    public function __construct(
        private ErrorDetailsResolverInterface $errorDetailsResolver,
        private ErrorRendererInterface $decorated,
    ) {
    }

    public function render(Throwable $exception): FlattenException
    {
        $flattenException = $this->decorated->render($exception);

        $flattenException->setAsString(\str_replace(
            $this->resolvePatterns($exception->getMessage()),
            $this->resolvePatterns($this->errorDetailsResolver->resolveInternalMessage($exception)),
            $flattenException->getAsString()
        ));

        return $flattenException;
    }

    /**
     * @return string[]
     */
    private function resolvePatterns(string $value): array
    {
        return \array_map(
            static fn (string $pattern): string => \sprintf($pattern, $value),
            self::PATTERNS
        );
    }
}
