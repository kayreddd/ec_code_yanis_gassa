<?php

namespace App\Controller;

use App\Repository\BookReadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class HomeController extends AbstractController
{
    private BookReadRepository $bookReadRepository;

    // Inject the repository via the constructor
    public function __construct(BookReadRepository $bookReadRepository)
    {
        $this->bookReadRepository = $bookReadRepository;
    }

    #[Route('/', name: 'app.home')]
    public function index(): Response
    {
        $userId     = 1;
        $booksRead  = $this->bookReadRepository->findByUserId($userId, false);

        // Render the 'hello.html.twig' template
        return $this->render('pages/home.html.twig', [
            'booksRead' => $booksRead,
            'name'      => 'Accueil', // Pass data to the view
        ]);
    }
}
