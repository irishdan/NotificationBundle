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


class NotificationMaker extends AbstractMaker
{
    private $channels;
    private $channelTemplates = [];

    public function setEnabledChannels(array $channels)
    {
        $this->channels = $channels;
    }

    public static function getCommandName(): string
    {
        return 'make:notification';
    }


    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new notification class')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the notification class (e.g. <fg=yellow>NewUserNotification</>)')
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeNotification.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        // Create The notification class
        // ask if create twig templates perhaps??
        // Use the configured channels to determine which channels to include in the class, or...
        // ..ask user which of the enabled channels to use for the notification...

        // Broadcasts could be to a mailing list also
        // Broadcasts are for notifications to places that are not notifiables..
        // eg mailing lists, mercure, push notifications etc etc

        $voterClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Notification\\',
            'Notification'
        );

        $generator->generateClass(
            $voterClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/notification/Notification.tpl.php',
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