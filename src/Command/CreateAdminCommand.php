<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un utilisateur administrateur en posant des questions interactives',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        // 1 - firstname
        $firstnameQ = new Question('Prénom de l’admin : ');
        $firstname = $helper->ask($input, $output, $firstnameQ);

        // 2 - lastname
        $lastnameQ = new Question('Nom de l’admin : ');
        $lastname = $helper->ask($input, $output, $lastnameQ);

        // 3 - email
        $emailQ = new Question('Email de l’admin : ');
        $email = $helper->ask($input, $output, $emailQ);

        // 4 - password (caché dans le terminal)
        $passwordQ = new Question('Mot de passe de l’admin : ');
        $passwordQ->setHidden(true);
        $passwordQ->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $passwordQ);

        // Création de l’utilisateur
        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']); // rôle forcé à ADMIN

        // Hash du mot de passe
        $hashed = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashed);

        // Enregistrement en BDD
        $this->em->persist($user);
        $this->em->flush();

        $output->writeln("✅ Administrateur $firstname $lastname <$email> créé avec succès !");
        return Command::SUCCESS;
    }
}
