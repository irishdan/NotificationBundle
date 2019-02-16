<?php

namespace IrishDan\NotificationBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class DatabaseNotificationMaker extends AbstractMaker
{
    private $channels;
    private $channelTemplates = [];

    public function setEnabledChannels(array $channels)
    {
        $this->channels = $channels;
    }

    public static function getCommandName(): string
    {
        return 'make:database-notification';
    }


    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new doctrine entity class for use as a database notification')
            // ->addArgument('name', InputArgument::OPTIONAL, 'The name of the notification class (e.g. <fg=yellow>NewUserNotification</>)')
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeDatabaseNotification.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $voterClassNameDetails = $generator->createClassNameDetails(
            'Notification',
            'Entity\\',
            'Entity'
        );

        $generator->generateClass(
            $voterClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/notification/DatabaseNotification.tpl.php',
            []
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Open your notification and add your logic.'
        ]);
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        // $dependencies->addClassDependency(
        //     Voter::class,
        //     'security'
        // );
    }
}