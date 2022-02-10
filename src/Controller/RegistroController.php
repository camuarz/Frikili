<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistroController extends AbstractController
{
    #[Route('/registro', name: 'registro')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $hashedPassword = $passwordHasher->hashPassword($user, $form['password']->getData());
            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();
            $this->addFlash('exito', user::REGISTRO_EXITOSO);
            return $this->redirectToRoute('registro');
        }
        return $this->render('registro/index.html.twig', [
            'controller_name' => 'Bienvenido a Frikili',
            'MiVariable'=>'Control de registro',
            'Formulario'=>$form->createview()
        ]);

    }
}
