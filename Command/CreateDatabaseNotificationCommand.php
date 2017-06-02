<?php

namespace IrishDan\NotificationBundle\Command;

use IrishDan\NotificationBundle\Generator\DatabaseNotificationGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;


class CreateDatabaseNotificationCommand extends GeneratorCommand
{
    private $entityName;

    public function setEntityName(array $databaseChannelConfig)
    {
        if (!empty($databaseChannelConfig['entity'])) {
            $this->entityName = explode(':', $databaseChannelConfig['entity'])[1];
        }
    }

    protected function configure()
    {
        $this
            ->setName('notification:create-database-notification')
            ->setDescription('Creates a new doctrine database channel notification class')
            ->setDefinition([
                new InputOption('bundle', '', InputOption::VALUE_REQUIRED, 'The bundle for this notification'),
            ]);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the database channel Notification generator');

        // Get the Bundle to generate it in
        $output->writeln([
            'This command helps you generate a a database channel Notification class',
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

        $style->text('Generating new database notification class ' . $this->entityName . ' generated in ' . $bundle);

        try {
            $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
        } catch (\Exception $e) {
            $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
        }

        $generator = $this->getGenerator($bundle);
        $generator->generate($bundle, $this->entityName);

        $output->writeln(sprintf('Generated the <info>%s</info> notification in <info>%s</info>', $this->entityName, $bundle->getName()));
        $questionHelper->writeGeneratorSummary($output, []);
    }

    protected function createGenerator()
    {
        return new DatabaseNotificationGenerator(
            $this->getContainer()->get('filesystem')
        );
    }
}