<?php

namespace App\Controller;

use App\Repository\BookReadRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\BookRead;

class HomeController extends AbstractController
{
    private BookReadRepository $bookReadRepository;
    private EntityManagerInterface $entityManager;
    private BookRepository $bookRepository;

    // Injection des repositories et de l'EntityManager via le constructeur
    public function __construct(BookReadRepository $bookReadRepository, BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        $this->bookReadRepository = $bookReadRepository;
        $this->bookRepository = $bookRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app.home')]
    public function index(): Response
    {
        $userId = 1;

        // ici on récupère les livres lus par l'utilisateur
        $booksRead = $this->bookReadRepository->findByUserId($userId, false);

        // ensuite on récup tout depuis la bdd
        $books = $this->bookRepository->findAll();

        return $this->render('pages/home.html.twig', [
            'booksRead' => $booksRead,
            'books'     => $books,
            'name'      => 'Accueil',
        ]);
    }

    #[Route('/add-book-read', name: 'app.add_book_read', methods: ['POST'])]
    public function addBookRead(Request $request): JsonResponse
    {
        $userId = 1; // Id de l'utilisateur, à ajuster selon la session ou l'utilisateur connecté

        // Récupérer les données envoyées
        $bookId = $request->request->get('bookId');
        $description = $request->request->get('description');
        $rating = $request->request->get('rating');
        $read = $request->request->get('read') == 'on' ? true : false;

        // Vérifier que le livre existe
        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], Response::HTTP_BAD_REQUEST);
        }

        // Créez l'objet BookRead (votre entité liée à la table book_read)
        $bookRead = new BookRead();
        $bookRead->setUserId($userId);
        $bookRead->setBook($book);
        $bookRead->setDescription($description);
        $bookRead->setRating($rating);
        $bookRead->setRead($read);
        $bookRead->setCreatedAt(new \DateTime());
        $bookRead->setUpdatedAt(new \DateTime());

        // Sauvegarder les données en base de données
        $this->entityManager->persist($bookRead);
        $this->entityManager->flush();

        // Récupérer le nom du livre et la date mise à jour
        // $book = $this->bookRepository->find($bookId);

        // Réponse JSON pour renvoyer les informations de l'élément ajouté
        return new JsonResponse([
            'bookName' => $book->getName(),
            'description' => $description,
            'updatedAt' => $bookRead->getUpdatedAt()->format('d/m/Y à H:i'),
        ]);
    }
}
