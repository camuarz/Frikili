<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Entity\Posts;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
    {
        $user=$this->getUser();
        if ($user){
            $em = $doctrine->getManager();
            $query = $em->getRepository(Posts::class)->BuscarTodosLosPosts();
            $comentarios = $em->getRepository(Comentarios::class)->Buscarcomentarios($user->getUserIdentifier());
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                2 /*limit per page*/
            );

            return $this->render('dashboard/index.html.twig', [
                'controller_name' => 'Frikili',
                'pagination' => $pagination,
                'comentarios'=>$comentarios
            ]);
        }else{
            return $this->redirectToRoute('app_login');
        }
    }
}
