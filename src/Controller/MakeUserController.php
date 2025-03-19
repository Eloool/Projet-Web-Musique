<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class MakeUserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/make/user', name: 'make_user', methods: ['POST'])]
    public function makeUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $query = $this->entityManager->createQuery('DELETE FROM App\Entity\User u');
        $deletedCount = $query->execute();
        $user = new User();
        $user->setName($request->request->get('name'));
        $userCount = $entityManager->getRepository(User::class)->count([]);
        if ($userCount === 0) {
            $user->setRoles(['ROLE_ADMIN']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }
        $user->setRoles(['ROLE_USER']);
        $user->setEmail($request->request->get('email'));
        $user->setPassword($passwordHasher->hashPassword($user, $request->request->get('password')));
        
        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->render("Accueil/accueil.html.twig", []);
    }
}
