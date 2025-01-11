<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/books', name: 'app_books')]
    public function getBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll(); // Récupère tous les livres

        return $this->render('book/book.html.twig', [
            'books' => $books, // Envoie les livres à la vue
        ]);
    }
}
