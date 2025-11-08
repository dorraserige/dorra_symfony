<?php
// src/Controller/AuthorController.php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/show/{name}', name: 'showAuthor')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/show.html.twig', [
            'nom' => $name
        ]);
    }

    #[Route('/listAuthor', name: 'listAuthor')]
    public function listAuthors(): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => '/images/Victor-Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => '/images/william-shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => '/images/Taha_Hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ],
        ];

        return $this->render('author/list.html.twig', [
            'authors' => $authors
        ]);
    }

    #[Route('/showAll', name: 'showAll')]
    public function showAll(AuthorRepository $repo): Response
    {
        $authors = $repo->findAll();

        return $this->render('author/showAll.html.twig', [
            'list' => $authors
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $doctrine): Response
    {
        $author = new Author();
        $author->setEmail('foulen@esprit.tn');
        $author->setUsername('foulen');
        $author->setNbBooks(0);

        $em = $doctrine->getManager();
        $em->persist($author);
        $em->flush();

        $this->addFlash('success', 'Auteur ajouté avec succès !');
        return $this->redirectToRoute('showAll');
    }

    #[Route('/addForm', name: 'addForm')]
    public function addForm(Request $request, ManagerRegistry $doctrine): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();

            $this->addFlash('success', 'Auteur ajouté avec succès !');
            return $this->redirectToRoute('showAll');
        }

        return $this->render('author/add.html.twig', [
            // ⚠️ Ici on passe bien la variable `form` utilisée dans le Twig
            'form' => $form->createView()
        ]);
    }

    #[Route('/deleteAuthor/{id}', name: 'deleteAuthor')]
    public function deleteAuthor(int $id, AuthorRepository $repo, ManagerRegistry $doctrine): Response
    {
        $author = $repo->find($id);

        if (!$author) {
            throw $this->createNotFoundException("Aucun auteur trouvé avec l'ID $id");
        }

        $em = $doctrine->getManager();
        $em->remove($author);
        $em->flush();

        $this->addFlash('success', 'Auteur supprimé avec succès !');
        return $this->redirectToRoute('showAll');
    }

    #[Route('/showDetails/{id}', name: 'showDetails')]
    public function showDetails(int $id, AuthorRepository $repo): Response
    {
        $author = $repo->find($id);

        if (!$author) {
            throw $this->createNotFoundException("Aucun auteur trouvé avec l'ID $id");
        }

        return $this->render('author/showDetails.html.twig', [
            'author' => $author
        ]);
    }


//////////////////





// Ajoutez cette méthode dans votre AuthorController existant
#[Route('/updateAuthor/{id}', name: 'updateAuthor')]
public function updateAuthor(int $id, Request $request, AuthorRepository $repo, ManagerRegistry $doctrine): Response
{
    $author = $repo->find($id);

    if (!$author) {
        throw $this->createNotFoundException("Aucun auteur trouvé avec l'ID $id");
    }

    $form = $this->createForm(AuthorType::class, $author);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->flush();

        $this->addFlash('success', 'Auteur modifié avec succès !');
        return $this->redirectToRoute('showAll');
    }

    return $this->render('author/update.html.twig', [
        'form' => $form->createView()
    ]);
}


/////////////////


#[Route('/searchAuthors', name: 'searchAuthors')]
public function searchAuthors(Request $request, AuthorRepository $repo): Response
{
    $minBooks = $request->query->get('minBooks');
    $maxBooks = $request->query->get('maxBooks');
    
    $authors = [];
    
    if ($minBooks !== null && $maxBooks !== null) {
        $authors = $repo->findByBookRange($minBooks, $maxBooks);
    }

    return $this->render('author/list.html.twig', [
        'authors' => $authors,
        'minBooks' => $minBooks,
        'maxBooks' => $maxBooks
    ]);
}













/////////////////


// Dans AuthorController.php, ajoutez cette méthode :

#[Route('/authors/by-email', name: 'authors_by_email')]
public function listAuthorsByEmail(AuthorRepository $repo): Response
{
    $authors = $repo->listAuthorByEmail();

    return $this->render('author/listByEmail.html.twig', [
        'authors' => $authors
    ]);
}

///////////////////


 #[Route('/showAllAuthorsDQL', name: 'showAllAuthorsDQL')]
    public function showAllAuthorsDQL(AuthorRepository $repo): Response
    {
        $authors = $repo->showAllAuthorsDQL();
        return $this->render('author/showAll.html.twig', [
            'list' => $authors
        ]);
    }



    
}
