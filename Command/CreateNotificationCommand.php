<?php

namespace IrishDan\NotificationBundle\Command;

use IrishDan\NotificationBundle\Generator\NotificationGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;


class CreateNotificationCommand extends GeneratorCommand
{
    private $channels;
    private $channelTemplates = [];

    public function setEnabledChannels(array $channels)
    {
        $this->channels = $channels;
    }

    protected function configure()
    {
        $this
            ->setName('notification:create')
            ->setDescription('Create a new notification class')
            ->setDefinition([
                new InputOption('bundle', '', InputOption::VALUE_REQUIRED, 'The bundle for this notification'),
                new InputOption('notification_name', '', InputOption::VALUE_REQUIRED, 'The name of the notification'),
                new InputOption('channel_templates', '', InputOption::VALUE_REQUIRED, 'The name of the notification'),
            ]);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the Notification generator');

        // Get the Bundle to generate it in
        $output->writeln([
            'This command helps you generate a Notification class',
            '',
            'First, give the name of the bundle to generate the notification in (eg <comment>AppBundle</comment>)',
        ]);

        $question = new Question($questionHelper->getQuestion('The bundle name', $input->getOption('bundle')), $input->getOption('bundle'));

        // @TODO: Add existing bundle validation
        $question->setValidator(['Sensio\Bundle\GeneratorBundle\Command\Validators', 'validateBundleName']);
        $question->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });
        $question->setMaxAttempts(2);

        $bundle = $questionHelper->ask($input, $output, $question);
        $input->setOption('bundle', $bundle);

        // Get the Bundle to generate it in
        $output->writeln([
            '',
            'Now, give the name of the new notification class (eg <comment>NewMember</comment>)',
        ]);

        // Get the new class name and validate it.
        $question = new Question($questionHelper->getQuestion('The notification name', $input->getOption('notification_name')), $input->getOption('notification_name'));
        $question->setValidator(function ($answer) {
            // Should only contain letters.
            $valid = preg_match('/^[a-zA-Z]+$/', $answer);
            if (!$valid) {
                throw new \RuntimeException(
                    'The class name should only contain letters'
                );
            }

            return $answer;
        });
        $question->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });

        $notificationName = $questionHelper->ask($input, $output, $question);
        $input->setOption('notification_name', $notificationName);

        // ask whether to generate templates for enabled channels.
        var_dump($this->channels);
        foreach ($this->channels as $channel) {
            $question = $this->createYesNoQuestion($questionHelper, $input, $channel);

            $generateTemplate = $questionHelper->ask($input, $output, $question);
            if ($generateTemplate == 'y') {
                $this->channelTemplates[] = $channel;
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();

        if ($input->isInteractive()) {
            $question = new ConfirmationQuestion($questionHelper->getQuestion('Do you confirm generation', 'yes', '?'), true);
            if (!$questionHelper->ask($input, $output, $question)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        $style = new SymfonyStyle($input, $output);

        $bundle = $input->getOption('bundle');
        $name = $input->getOption('notification_name');

        $style->text('Generating New notification class ' . $name . ' generated in ' . $bundle);

        try {
            $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
        } catch (\Exception $e) {
            $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
        }

        $generator = $this->getGenerator($bundle);
        $generator->generate($bundle, $name);

        $output->writeln(sprintf('Generated the <info>%s</info> notification in <info>%s</info>', $name, $bundle->getName()));
        $questionHelper->writeGeneratorSummary($output, []);
    }

    protected function createYesNoQuestion($questionHelper, $input, $channel)
    {
        $question = new Question($questionHelper->getQuestion('Generate a message template for the ' . $channel . ' <comment>[yes]</comment>', 'channel_templates'), 'yes');
        $question->setNormalizer(function ($value) {
            return $value[0] == 'y' ? 'y' : 'n';
        });

        $question->setValidator(function ($answer) {
            // Should only contain letters.
            $allowed = [
                'y',
                'n',
            ];
            $valid = in_array($answer, $allowed);
            if (!$valid) {
                throw new \RuntimeException(
                    'Only allowed value are ' . implode(', ', $allowed)
                );
            }

            return $answer;
        });

        return $question;
    }

    protected function createGenerator()
    {
        return new NotificationGenerator(
            $this->getContainer()->get('filesystem'),
            $this->channelTemplates,
            $this->getContainer()->getParameter('kernel.root_dir')
        );
    }
}