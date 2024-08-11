<?php
declare(strict_types=1);

namespace EonX\EasyErrorHandler\Bundle;

use Bugsnag\Client;
use EonX\EasyErrorHandler\Bugsnag\Ignorer\BugsnagExceptionIgnorerInterface;
use EonX\EasyErrorHandler\Bundle\CompilerPass\ErrorHandlerCompilerPass;
use EonX\EasyErrorHandler\Bundle\CompilerPass\ErrorRendererCompilerPass;
use EonX\EasyErrorHandler\Bundle\Enum\ConfigParam;
use EonX\EasyErrorHandler\Bundle\Enum\ConfigTag;
use EonX\EasyErrorHandler\Common\Driver\VerboseStrategyDriverInterface;
use EonX\EasyErrorHandler\Common\Provider\ErrorReporterProviderInterface;
use EonX\EasyErrorHandler\Common\Provider\ErrorResponseBuilderProviderInterface;
use EonX\EasyWebhook\Common\Event\FinalFailedWebhookEvent;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class EasyErrorHandlerBundle extends AbstractBundle
{
    public function __construct()
    {
        $this->path = \realpath(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new ErrorHandlerCompilerPass())
            ->addCompilerPass(new ErrorRendererCompilerPass());
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('config/definition.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $parameters = $container->parameters();

        $parameters->set(ConfigParam::BugsnagThreshold->value, $config['bugsnag']['threshold']);
        $parameters->set(
            ConfigParam::BugsnagIgnoredExceptions->value,
            \count($config['bugsnag']['ignored_exceptions']) > 0 ? $config['bugsnag']['ignored_exceptions'] : []
        );
        $parameters->set(
            ConfigParam::BugsnagHandledExceptions->value,
            \count($config['bugsnag']['handled_exceptions']) > 0 ? $config['bugsnag']['handled_exceptions'] : null
        );

        $parameters->set(
            ConfigParam::IgnoredExceptions->value,
            \count($config['ignored_exceptions']) > 0 ? $config['ignored_exceptions'] : null
        );
        $parameters->set(
            ConfigParam::ReportRetryableExceptionAttempts->value,
            $config['report_retryable_exception_attempts'] ?? false
        );

        $parameters->set(
            ConfigParam::SkipReportedExceptions->value,
            $config['skip_reported_exceptions'] ?? false
        );

        $parameters->set(ConfigParam::IsVerbose->value, $config['verbose']);

        $parameters->set(
            ConfigParam::LoggerExceptionLogLevels->value,
            \count($config['logger']['exception_log_levels']) > 0 ? $config['logger']['exception_log_levels'] : null
        );
        $parameters->set(
            ConfigParam::LoggerIgnoredExceptions->value,
            \count($config['logger']['ignored_exceptions']) > 0 ? $config['logger']['ignored_exceptions'] : null
        );

        $parameters->set(ConfigParam::ResponseKeys->value, $config['response']);
        $parameters->set(ConfigParam::TranslationDomain->value, $config['translation_domain']);

        $parameters->set(
            ConfigParam::ErrorCodesInterface->value,
            $config['error_codes_interface']
        );
        $parameters->set(
            ConfigParam::ErrorCodesCategorySize->value,
            $config['error_codes_category_size']
        );
        $parameters->set(
            ConfigParam::TranslateInternalErrorMessagesEnabled->value,
            $config['translate_internal_error_messages']['enabled'] ?? false
        );
        $parameters->set(
            ConfigParam::TranslateInternalErrorMessagesLocale->value,
            $config['translate_internal_error_messages']['locale']
        );

        $builder
            ->registerForAutoconfiguration(ErrorReporterProviderInterface::class)
            ->addTag(ConfigTag::ErrorReporterProvider->value);

        $builder
            ->registerForAutoconfiguration(ErrorResponseBuilderProviderInterface::class)
            ->addTag(ConfigTag::ErrorResponseBuilderProvider->value);

        $builder
            ->registerForAutoconfiguration(VerboseStrategyDriverInterface::class)
            ->addTag(ConfigTag::VerboseStrategyDriver->value);

        $container->import('config/services.php');

        if ($config['use_default_builders'] ?? true) {
            $container->import('config/default_builders.php');
        }

        if ($config['use_default_reporters'] ?? true) {
            $container->import('config/default_reporters.php');
        }

        if (($config['bugsnag']['enabled'] ?? true) && \class_exists(Client::class)) {
            $builder->registerForAutoconfiguration(BugsnagExceptionIgnorerInterface::class)
                ->addTag(ConfigTag::BugsnagExceptionIgnorer->value);

            $container->import('config/bugsnag_reporter.php');
        }

        // EasyWebhook integration
        if (\class_exists(FinalFailedWebhookEvent::class)) {
            $container->import('config/easy_webhook.php');
        }
    }
}