<?php
declare(strict_types=1);

namespace EonX\EasyErrorHandler\Tests\Stub\Enum;

use EonX\EasyErrorHandler\ErrorCodes\Attribute\AsErrorCodes;

#[AsErrorCodes]
enum ErrorCode: int
{
    case Code1 = 1;
}
