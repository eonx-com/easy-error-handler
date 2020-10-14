<?php

declare(strict_types=1);

namespace EonX\EasyErrorHandler\Interfaces\Exceptions;

interface TranslatableExceptionInterface
{
    /**
     * @var string
     */
    public const DEFAULT_USER_MESSAGE = 'exceptions.default_user_message';

    /**
     * @var string
     */
    public const OPTION_DOMAIN = 'domain';

    /**
     * Returns the translation domain.
     *
     * @return null|string
     */
    public function getDomain(): ?string;

    /**
     * Returns the exception message parameters.
     *
     * @return mixed[]
     */
    public function getMessageParams(): array;

    /**
     * Returns the user-friendly message.
     */
    public function getUserMessage(): ?string;

    /**
     * Returns the user-friendly message parameters.
     *
     * @return mixed[]
     */
    public function getUserMessageParams(): array;
}
