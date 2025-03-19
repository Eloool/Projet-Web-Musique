<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delete-all-users',
    description: 'Supprime tous les utilisateurs de la base de données.',
)]
class DeleteAllUsersCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $query = $this->entityManager->createQuery('DELETE FROM App\Entity\User u');
        $deletedCount = $query->execute();

        $output->writeln("<comment>$deletedCount utilisateur(s) supprimé(s).</comment>");

        return Command::SUCCESS;
    }
}
