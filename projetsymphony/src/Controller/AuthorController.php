<?php

namespace App\Controller;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry; // ✅ Correct
use App\Entity\Author;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

#[Route(path:'/show/{name}', name: 'showAuthor')]
public function showAuthor($name): Response
{
    return $this->render('author/show.html.twig',['nom'=>$name]);
}
#[Route(path:'/listAuthor', name: 'listAuthor')]
public function  listAuthors(): Response
{
    $authors = array(
array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
'victor.hugo@gmail.com ', 'nb_books' => 100),
array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
' william.shakespeare@gmail.com', 'nb_books' => 200 ),
array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
'taha.hussein@gmail.com', 'nb_books' => 300),
    );
    return $this->render('author/list.html.twig',[
        'authors' => $authors
    ]);

}
#[Route('/showAll', name: 'showAll')]
    public function showAll(AuthorRepository $repo){
        $authors=$repo->findAll();
        return $this->render('author/showAll.html.twig',[
            'list'=>$authors
        ]);
        
    }



#[Route(path: '/add', name: 'add')]
    public function add(ManagerRegistry $doctrine): Response{
        $author=new Author();
        $author->setEmail(email: 'foulen@esprit.tn');
        $author->setUsername(username: 'foulen');
        $em = $doctrine->getManager();
        $em->persist(object: $author);
        $em->flush();
        //return new Response(content: "Author added successfully");
        return $this->redirectToRoute(route: 'showAll');
      
        
    }


#[Route(path: '/deleteAuthor/{id}', name: 'deleteAuthor')]
public function deleteAuthor($id, AuthorRepository $repo, ManagerRegistry $doctrine): Response
{
    $author = $repo->find($id);

    if (!$author) {
        // Si aucun auteur trouvé, afficher une erreur claire
        throw $this->createNotFoundException("Aucun auteur trouvé avec l'ID $id");
    }

    $em = $doctrine->getManager();
    $em->remove($author);
    $em->flush();

    $this->addFlash('success', 'Auteur supprimé avec succès !');

    return $this->redirectToRoute('app_show_all');
}




#[Route(path: '/showDetails/{id}', name: 'showDetails')]
    public function showDetails($id, AuthorRepository $repo): Response{
        $author=$repo->find(id: $id);
        return $this->render(view: 'author/showDetails.html.twig',parameters: ['author'=>$author]);


}

}