<?php

declare(strict_types=1);

namespace EonX\EasyErrorHandler\Builders;

use EonX\EasyErrorHandler\Interfaces\Exceptions\TranslatableExceptionInterface;
use EonX\EasyErrorHandler\Interfaces\TranslatorInterface;
use Throwable;

final class UserMessageBuilder extends AbstractSingleKeyErrorResponseBuilder
{
    /**
     * @var \EonX\EasyErrorHandler\Interfaces\TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator, ?string $key = null, ?int $priority = null)
    {
        $this->translator = $translator;

        parent::__construct($key, $priority);
    }

    /**
     * @param mixed[] $data
     *
     * @return string
     */
    protected function doBuildValue(Throwable $throwable, array $data)
    {
        $message = null;
        $parameters = [];
        $options = [];

        if ($throwable instanceof TranslatableExceptionInterface) {
            $message = $throwable->getUserMessage();
            $parameters = $throwable->getUserMessageParams();
            $options[TranslatableExceptionInterface::OPTION_DOMAIN] = $throwable->getDomain();
        }

        return $this->translator->trans(
            $message ?? TranslatableExceptionInterface::DEFAULT_USER_MESSAGE,
            $parameters,
            $options
        );
    }

    protected function getDefaultKey(): string
    {
        return 'message';
    }
}
