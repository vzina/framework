<?php
declare (strict_types=1);

namespace OpenEf\Framework;

use OpenEf\Container\Collector\AnnotationCollector;
use OpenEf\Container\Config\ConfigInterface;
use OpenEf\Framework\Command\Annotation\Command;
use OpenEf\Framework\Command\Parser;
use OpenEf\Framework\Events\BootApplication;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ApplicationFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        $eventDispatcher->dispatch(new BootApplication());

        $config = $container->get(ConfigInterface::class);
        $annotationCommands = AnnotationCollector::getClassesByAnnotation(Command::class);
        $annotationCommands = array_keys($annotationCommands);

        $commands = array_unique(array_merge((array)$config->get('commands', []), $annotationCommands));
        $application = new Application();
        $config->get('symfony.event.enable', false) && $application->setDispatcher($eventDispatcher);

        foreach ($commands as $command) {
            $application->add(
                $this->pendingCommand($container->get($command))
            );
        }

        return $application;
    }

    protected function pendingCommand(SymfonyCommand $command): SymfonyCommand
    {
        /** @var Command|null $annotation */
        $annotation = AnnotationCollector::getClassAnnotation($command::class, Command::class) ?? null;

        if (! $annotation) {
            return $command;
        }

        if ($annotation->signature) {
            [$name, $arguments, $options] = Parser::parse($annotation->signature);
            if ($name) {
                $annotation->name = $name;
            }
            if ($arguments) {
                $annotation->arguments = array_merge($annotation->arguments, $arguments);
            }
            if ($options) {
                $annotation->options = array_merge($annotation->options, $options);
            }
        }

        if ($annotation->name) {
            $command->setName($annotation->name);
        }

        if ($annotation->arguments) {
            $annotation->arguments = array_map(static function ($argument): InputArgument {
                if ($argument instanceof InputArgument) {
                    return $argument;
                }

                if (is_array($argument)) {
                    return new InputArgument(...$argument);
                }

                throw new LogicException(sprintf('Invalid argument type: %s.', gettype($argument)));
            }, $annotation->arguments);

            $command->getDefinition()->addArguments($annotation->arguments);
        }

        if ($annotation->options) {
            $annotation->options = array_map(static function ($option): InputOption {
                if ($option instanceof InputOption) {
                    return $option;
                }

                if (is_array($option)) {
                    return new InputOption(...$option);
                }

                throw new LogicException(sprintf('Invalid option type: %s.', gettype($option)));
            }, $annotation->options);

            $command->getDefinition()->addOptions($annotation->options);
        }

        if ($annotation->description) {
            $command->setDescription($annotation->description);
        }

        if ($annotation->aliases) {
            $command->setAliases($annotation->aliases);
        }

        return $command;
    }
}
