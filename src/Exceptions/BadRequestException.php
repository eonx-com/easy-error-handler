<?php

declare(strict_types=1);

namespace EonX\EasyErrorHandler\Exceptions;

abstract class BadRequestException extends BaseException
{
    /**
     * @var int
     */
    protected $statusCode = 400;

    /**
     * @var string
     */
    protected $userMessage = 'easy-error-handler::messages.bad_request';
}
