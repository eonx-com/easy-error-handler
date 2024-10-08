<?php
declare(strict_types=1);

namespace EonX\EasyErrorHandler\Tests\Unit\Common\Exception;

use EonX\EasyErrorHandler\Tests\Stub\Enum\ErrorCode;
use EonX\EasyErrorHandler\Tests\Stub\Exception\BaseExceptionStub;
use EonX\EasyErrorHandler\Tests\Unit\AbstractUnitTestCase;
use EonX\EasyUtils\Common\Enum\HttpStatusCode;
use Monolog\Level;
use PHPUnit\Framework\Attributes\DataProvider;

final class BaseExceptionTest extends AbstractUnitTestCase
{
    /**
     * @see testSetCode
     */
    public static function provideCodes(): iterable
    {
        yield 'integer' => [
            'code' => 1,
            'expectedCode' => 1,
        ];

        yield 'enum' => [
            'code' => ErrorCode::Code1,
            'expectedCode' => 1,
        ];
    }

    /**
     * @see testLogLevelConvenientMethods
     */
    public static function provideLogLevelConvenientMethodsData(): iterable
    {
        yield 'critical' => [
            'method' => 'setCriticalLogLevel',
            'expectedLogLevel' => Level::Critical,
        ];

        yield 'debug' => [
            'method' => 'setDebugLogLevel',
            'expectedLogLevel' => Level::Debug,
        ];

        yield 'error' => [
            'method' => 'setErrorLogLevel',
            'expectedLogLevel' => Level::Error,
        ];

        yield 'info' => [
            'method' => 'setInfoLogLevel',
            'expectedLogLevel' => Level::Info,
        ];

        yield 'warning' => [
            'method' => 'setWarningLogLevel',
            'expectedLogLevel' => Level::Warning,
        ];
    }

    /**
     * @see testSetStatusCode
     */
    public static function provideStatusCodes(): iterable
    {
        yield 'integer' => [
            'statusCode' => 418,
            'expectedStatusCode' => HttpStatusCode::IamTeapot,
        ];

        yield 'enum' => [
            'statusCode' => HttpStatusCode::IamTeapot,
            'expectedStatusCode' => HttpStatusCode::IamTeapot,
        ];
    }

    /**
     * @see testSetSubCode
     */
    public static function provideSubCodes(): iterable
    {
        yield 'integer' => [
            'subCode' => 1,
            'expectedSubCode' => 1,
        ];

        yield 'enum' => [
            'subCode' => ErrorCode::Code1,
            'expectedSubCode' => 1,
        ];
    }

    public function testGetLogLevel(): void
    {
        $logLevel = Level::Critical;
        $exception = new BaseExceptionStub();
        self::setPrivatePropertyValue($exception, 'logLevel', $logLevel);

        $result = $exception->getLogLevel();

        self::assertSame($logLevel, $result);
    }

    public function testGetMessageParams(): void
    {
        $messageParams = [
            'foo' => 'bar',
        ];
        $exception = new BaseExceptionStub();
        self::setPrivatePropertyValue($exception, 'messageParams', $messageParams);

        $result = $exception->getMessageParams();

        self::assertSame($messageParams, $result);
    }

    public function testGetStatusCode(): void
    {
        $statusCode = HttpStatusCode::IamTeapot;
        $exception = new BaseExceptionStub();
        self::setPrivatePropertyValue($exception, 'statusCode', $statusCode);

        $result = $exception->getStatusCode();

        self::assertSame($statusCode, $result);
    }

    public function testGetSubCode(): void
    {
        $subCode = 123;
        $exception = new BaseExceptionStub();
        self::setPrivatePropertyValue($exception, 'subCode', $subCode);

        $result = $exception->getSubCode();

        self::assertSame($subCode, $result);
    }

    public function testGetUserMessage(): void
    {
        $userMessage = 'User message';
        $exception = new BaseExceptionStub();
        self::setPrivatePropertyValue($exception, 'userMessage', $userMessage);

        $result = $exception->getUserMessage();

        self::assertSame($userMessage, $result);
    }

    public function testGetUserMessageParams(): void
    {
        $userMessageParams = [
            'foo' => 'bar',
        ];
        $exception = new BaseExceptionStub();
        self::setPrivatePropertyValue($exception, 'userMessageParams', $userMessageParams);

        $result = $exception->getUserMessageParams();

        self::assertSame($userMessageParams, $result);
    }

    #[DataProvider('provideLogLevelConvenientMethodsData')]
    public function testLogLevelConvenientMethods(string $method, Level $expectedLogLevel): void
    {
        $exception = new BaseExceptionStub();

        $result = $exception->{$method}();

        self::assertSame($exception, $result);
        self::assertSame($expectedLogLevel, self::getPrivatePropertyValue($result, 'logLevel'));
    }

    #[DataProvider('provideCodes')]
    public function testSetCode(int|ErrorCode $code, int $expectedCode): void
    {
        $result = new BaseExceptionStub(code: $code);

        self::assertSame($expectedCode, self::getPrivatePropertyValue($result, 'code'));
    }

    public function testSetLogLevel(): void
    {
        $logLevel = Level::Critical;
        $exception = new BaseExceptionStub();

        $result = $exception->setLogLevel($logLevel);

        self::assertSame($exception, $result);
        self::assertSame($logLevel, self::getPrivatePropertyValue($result, 'logLevel'));
    }

    public function testSetMessageParams(): void
    {
        $messageParams = [
            'foo' => 'bar',
        ];
        $exception = new BaseExceptionStub();

        $result = $exception->setMessageParams($messageParams);

        self::assertSame($exception, $result);
        self::assertSame($messageParams, self::getPrivatePropertyValue($result, 'messageParams'));
    }

    #[DataProvider('provideStatusCodes')]
    public function testSetStatusCode(int|HttpStatusCode $statusCode, HttpStatusCode $expectedStatusCode): void
    {
        $exception = new BaseExceptionStub();

        $result = $exception->setStatusCode($statusCode);

        self::assertSame($exception, $result);
        self::assertSame($expectedStatusCode, self::getPrivatePropertyValue($result, 'statusCode'));
    }

    #[DataProvider('provideSubCodes')]
    public function testSetSubCode(int|ErrorCode $subCode, int $expectedSubCode): void
    {
        $exception = new BaseExceptionStub();

        $result = $exception->setSubCode($subCode);

        self::assertSame($exception, $result);
        self::assertSame($expectedSubCode, self::getPrivatePropertyValue($result, 'subCode'));
    }

    public function testSetUserMessage(): void
    {
        $userMessage = 'User message';
        $exception = new BaseExceptionStub();

        $result = $exception->setUserMessage($userMessage);

        self::assertSame($exception, $result);
        self::assertSame($userMessage, self::getPrivatePropertyValue($result, 'userMessage'));
    }

    public function testSetUserMessageParams(): void
    {
        $userMessageParams = [
            'foo' => 'bar',
        ];
        $exception = new BaseExceptionStub();

        $result = $exception->setUserMessageParams($userMessageParams);

        self::assertSame($exception, $result);
        self::assertSame($userMessageParams, self::getPrivatePropertyValue($result, 'userMessageParams'));
    }
}
