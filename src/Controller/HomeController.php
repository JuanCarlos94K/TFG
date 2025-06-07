<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        $volumes = [];

        foreach ($books as $book) {
            $volume = $book->getVolume();
            $volumeKey = $volume ? 'Volumen ' . $volume->getNumber() : 'Sin volumen';

            if (!isset($volumes[$volumeKey])) {
                $volumes[$volumeKey] = [];
            }

            $volumes[$volumeKey][] = $book;
        }

        return $this->render('home/index.html.twig', [
            'volumes' => $volumes,
        ]);
    }
}
