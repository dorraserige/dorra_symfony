<?php

namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;


use Symfony\Component\HttpFoundation\Request;


use Doctrine\Persistence\ManagerRegistry;


use App\Repository\BookRepository;





use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }


#[Route('/addBook', name: 'addForm')]
    public function addForm(Request $request, ManagerRegistry $doctrine): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Auteur ajouté avec succès !');
            return $this->redirectToRoute('app_book');
        }

        return $this->render('book/add.html.twig', [
            // ⚠️ Ici on passe bien la variable `form` utilisée dans le Twig
            'formulaireBook' => $form->createView()
        ]);
    }




    #[Route('/listBook', name: 'list_books')]
public function listBook(BookRepository $repository): Response
{
    $books = $repository->findAll();

    return $this->render('book/list.html.twig', [
        'books' => $books,
    ]);
}


#[Route('/deleteBook/{id}', name: 'deleteBook')]
public function deleteBook(int $id, BookRepository $repo, ManagerRegistry $doctrine): Response
{
    $book = $repo->find($id);

    if (!$book) {
        throw $this->createNotFoundException("Aucun livre trouvé avec l'ID $id");
    }

    $em = $doctrine->getManager();
    $em->remove($book);
    $em->flush();

    $this->addFlash('success', 'Livre supprimé avec succès !');
    return $this->redirectToRoute('list_books');
}
  



    // --- SHOW BOOK DETAILS ---
    #[Route('/showBook/{id}', name: 'showBook')]
    public function showBook(int $id, BookRepository $repo): Response
    {
        $book = $repo->find($id);

        if (!$book) {
            throw $this->createNotFoundException("Aucun livre trouvé avec l'ID $id");
        }

        return $this->render('book/showDetails.html.twig', [
            'book' => $book
        ]);
    }

#[Route('/updateBook/{id}', name: 'updateBook')]
public function updateBook(int $id, Request $request, BookRepository $repo, ManagerRegistry $doctrine): Response
{
    // On récupère le livre à mettre à jour
    $book = $repo->find($id);

    if (!$book) {
        throw $this->createNotFoundException("Aucun livre trouvé avec l'ID $id");
    }

    // On crée le formulaire pré-rempli
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->flush(); // Pas besoin de persist, le livre existe déjà

        $this->addFlash('success', 'Livre mis à jour avec succès !');
        return $this->redirectToRoute('list_books');
    }

    return $this->render('book/update.html.twig', [
        'formulaireBook' => $form->createView()
    ]);
}


}


   




   
