<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Carbon\Carbon;

class SetDeployTimestampsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('deploy:timestamps');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->getContainer()->get('cache')->fetch('first_deploy') === false) {
            $this->getContainer()->get('cache')->save('first_deploy', Carbon::now());
        }

        $this->getContainer()->get('cache')->save('latest_deploy', Carbon::now());

        $output->writeln('Deploy timestamps have been set!');
    }
}