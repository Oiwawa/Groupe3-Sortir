<?php

namespace App\Command;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateStatesCommand extends Command
{
    protected static $defaultName = 'app:update-states';
    protected static $defaultDescription = 'Met a jour l\'état des sortie';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $io->error('error');
        $io->newLine('test');

       $allEvents = $this->entityManager->getRepository('App:Event')->findAll();

        /**
         * @var Event $event
         */
        foreach ($allEvents as $event){
            if($event->getLimitDate() >= (new \DateTime('now'))){
                $event->setState($state = $this->entityManager->getRepository('App:EventState')->find(3));
            }
            if($event->getEventDate() > (new \DateTime('now'))){
                $event->setState($state = $this->entityManager->getRepository('App:EventState')->find(4));
            }
            if($event->getCurrentSubs() >= $event->getNbrPlace()){
                $event->setState($state = $this->entityManager->getRepository('App:EventState')->find(3));
            }
        }
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
