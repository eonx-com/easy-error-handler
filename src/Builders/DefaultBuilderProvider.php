<?php

declare(strict_types=1);

namespace EonX\EasyErrorHandler\Builders;

use EonX\EasyErrorHandler\Interfaces\ErrorResponseBuilderProviderInterface;
use EonX\EasyErrorHandler\Interfaces\TranslatorInterface;

final class DefaultBuilderProvider implements ErrorResponseBuilderProviderInterface
{
    /**
     * @var mixed[]
     */
    private $keys;

    /**
     * @var \EonX\EasyErrorHandler\Interfaces\TranslatorInterface
     */
    private $translator;

    /**
     * @param null|string[] $keys
     */
    public function __construct(TranslatorInterface $translator, ?array $keys = null)
    {
        $this->translator = $translator;
        $this->keys = $keys ?? [];
    }

    /**
     * @return iterable<\EonX\EasyErrorHandler\Interfaces\ErrorResponseBuilderInterface>
     */
    public function getBuilders(): iterable
    {
        yield new CodeBuilder($this->getKey('code'));
        yield new ExtendedExceptionBuilder(
            $this->translator,
            $this->getKey('exception'),
            $this->keys['extended_exception_keys'] ?? []
        );
        yield new StatusCodeBuilder();
        yield new SubCodeBuilder($this->getKey('sub_code'));
        yield new TimeBuilder($this->getKey('time'));
        yield new UserMessageBuilder($this->translator, $this->getKey('message'));
        yield new ViolationsBuilder($this->getKey('violations'));
    }

    private function getKey(string $name): string
    {
        return $this->keys[$name] ?? $name;
    }
}
