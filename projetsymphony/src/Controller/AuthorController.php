<?php
// src/Controller/AuthorController.php

namespace App\Controller;

use App\Form\AuthorType;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function listAuthors(): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        return $this->render('author/list.html.twig',[
            'authors' => $authors
        ]);
    }

    #[Route('/showAll', name: 'showAll')]
    public function showAll(AuthorRepository $repo): Response
    {
        $authors = $repo->findAll();
        return $this->render('author/showAll.html.twig',[
            'list' => $authors
        ]);
    }

    #[Route(path: '/add', name: 'add')]
    public function add(ManagerRegistry $doctrine): Response
    {
        $author = new Author();
        $author->setEmail('foulen@esprit.tn');
        $author->setUsername('foulen');
        $em = $doctrine->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('showAll');
    }

    #[Route(path: '/addForm', name: 'addForm')]
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
            'formulaire' => $form->createView()
        ]);
    }

    #[Route(path: '/deleteAuthor/{id}', name: 'deleteAuthor')]
    public function deleteAuthor($id, AuthorRepository $repo, ManagerRegistry $doctrine): Response
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

    #[Route(path: '/showDetails/{id}', name: 'showDetails')]
    public function showDetails($id, AuthorRepository $repo): Response
    {
        $author = $repo->find($id);
        
        if (!$author) {
            throw $this->createNotFoundException("Aucun auteur trouvé avec l'ID $id");
        }
        
        return $this->render('author/showDetails.html.twig', [
            'author' => $author
        ]);
    }
}