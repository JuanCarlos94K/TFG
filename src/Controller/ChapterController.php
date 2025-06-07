<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Form\ChapterForm;
use App\Repository\ChapterRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chapter')]
final class ChapterController extends AbstractController
{
    #[Route(name: 'app_chapter_index', methods: ['GET'])]
    public function index(ChapterRepository $chapterRepository): Response
    {
        return $this->render('chapter/index.html.twig', [
            'chapters' => $chapterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chapter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $chapter = new Chapter();
    $request->getSession()->set('test', 'foo');
    dump($request->getSession()->get('test'));
    $form = $this->createForm(ChapterForm::class, $chapter);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
    dump($form->getErrors(true));
}
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($chapter);
        $entityManager->flush();

        return $this->redirectToRoute('chapters');
    }

    return $this->render('chapter/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/{slug}', name: 'chapter_show', methods: ['GET'])]
    public function show(ChapterRepository $chapterRepository, string $slug): Response
    {
        $chapter = $chapterRepository->findOneBy(['slug' => $slug]);

        if (!$chapter) {
            throw $this->createNotFoundException('Capítulo no encontrado');
        }

        $previousChapter = $chapterRepository->findOneBy([
            'book' => $chapter->getBook(),
            'number' => $chapter->getNumber() - 1,
        ]);

        $nextChapter = $chapterRepository->findOneBy([
            'book' => $chapter->getBook(),
            'number' => $chapter->getNumber() + 1,
        ]);

        return $this->render('chapter/show.html.twig', [
            'chapter' => $chapter,
            'previousChapter' => $previousChapter,
            'nextChapter' => $nextChapter,
        ]);
    }


    #[Route('/chapters', name: 'chapter_list')]
    public function list(ChapterRepository $chapterRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $chapterRepository->createQueryBuilder('c')
            ->orderBy('c.number', 'ASC');

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('chapter/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    

}
