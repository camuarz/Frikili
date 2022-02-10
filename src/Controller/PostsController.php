<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Entity\Posts;
use App\Form\ComentarioType;
use App\Form\PostsType;
use Doctrine\ORM\Mapping\Id;
use Knp\Component\Pager\PaginatorInterface;
use mysql_xdevapi\Exception;
use PhpParser\Node\Stmt\If_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostsController extends AbstractController
{
    #[Route('/registrar-posts', name: 'RegistrarPosts')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $posts = new Posts();
        $form = $this->createForm(PostsType::class, $posts);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form->get('Foto')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups! ha ocurrido un error, sorry :c)');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $posts->setFoto($newFilename);
            }
            $user= $this->getUser();
            $posts->setUser($user);
            $em = $doctrine->getManager();
            $em->persist($posts);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('posts/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/post/{id}", name="VerPost")
     */
    public function VerPost ($id, PersistenceManagerRegistry $doctrine, Request $request){
        $em = $doctrine->getManager();
        $post  = $em->getRepository(Posts::class)->find($id);
//        $comentarios = $em->getRepository(Comentarios::class)->findAll();
        $comentario = new Comentarios();
        $form = $this->createForm(ComentarioType::class, $comentario);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $user= $this->getUser();
            $comentario->setUser($user);
            $comentario->setPosts($post);
            $em=$doctrine->getManager();
            $em->persist($comentario);
            $em->flush();
        }
        return $this->render('Posts/VerPost.html.twig', [
            'post'=>$post,
            'formc' => $form->createView(),
        ]);

    }
    /**
     * @Route("/mis-posts", name="MisPosts")
     */
    public function MisPosts (PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request){
        $em = $doctrine->getManager();
        $user = $this->getUser();
        if ($user) {
            $em = $doctrine->getManager();
            $query = $em->getRepository(Posts::class)->BuscarTodosLosPosts();
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );
        }
        $posts = $em->getRepository(Posts::class)->findBy(['User'=>$user]);
        return $this->render('Posts/MisPosts.html.twig', [
            'posts'=>$posts,
            'pagination' => $pagination
        ]);

    }
    /**
     * @Route("/liks", options={"expose"=true}, name="Liks")
     */
    public function Liks (Request $request, PersistenceManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()){
            $em = $doctrine->getManager();
            $user= $this->getUser();
            $id=$request->request->get('id');
            $post=$em->getRepository(Posts::class)->find($id);
            $liks=$post->getlik();
            $liks.=$user->getId().',';
            $post->setlik($liks);
            $em->flush();
            return new JsonResponse(['Liks'=>$liks]);
        }else{
            throw new Exception('No me Hackees');
        }
    }


}
