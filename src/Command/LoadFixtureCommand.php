<?php

namespace App\Command;

use App\Service\FixtureLoaderService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixtureCommand extends Command
{
    protected static $defaultName = 'app.load.fixture';

    /**
     * @var FixtureLoaderService
     */
    public $fixtureService;

    public function __construct(FixtureLoaderService $fixtureService)
    {
        $this->fixtureService = $fixtureService;
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setDescription('Load a fixture')
            ->setHelp('This command allows you  to load fixture')
            ->addArgument('fixture_file', InputArgument::REQUIRED, 'Your fixture file ?')
            ->addOption(
                'iteration',
                null,
                InputOption::VALUE_REQUIRED,
                'description of this option');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixturePath = $input->getArgument('fixture_file');

        $this->fixtureService->load($fixturePath);

        /*$user = 'Salut Abdellah';

        for ($i = 0; $i < $input->getOption('iteration'); $i++) {

            $output->writeln($user);
        }*/
    }
}