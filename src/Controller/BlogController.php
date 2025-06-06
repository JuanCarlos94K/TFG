<?php

namespace App\Controller;

use App\Repository\ChapterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'blog_home')]
    public function index(ChapterRepository $chapterRepository): Response
    {
        $chapters = $chapterRepository->findBy([], ['number' => 'ASC']); // ordenados por número

        return $this->render('blog/index.html.twig', [
            'chapters' => $chapters,
        ]);
    }
}
