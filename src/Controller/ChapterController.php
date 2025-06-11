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
use App\Entity\Comment;
use App\Form\CommentTypeForm;
use Symfony\Component\Security\Core\Security;

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
  
    $form = $this->createForm(ChapterForm::class, $chapter);
    $form->handleRequest($request);
   
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($chapter);
        $entityManager->flush();

        return $this->redirectToRoute('app_chapter_index');
    }

    return $this->render('chapter/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/{slug}', name: 'chapter_show', methods: ['GET', 'POST'])]
public function show(
    ChapterRepository $chapterRepository,
    string $slug,
    Request $request,
    EntityManagerInterface $entityManager,
): Response {
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

    // 🚩 Aquí creamos el formulario de comentario
    $comment = new Comment();
    $comment->setChapter($chapter);
    $comment->setAuthor($this->getUser());

    $form = $this->createForm(CommentTypeForm::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $comment->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Comentario enviado correctamente.');

        // Redirige para evitar reenvío de formulario
        return $this->redirectToRoute('chapter_show', ['slug' => $chapter->getSlug()]);
    }

    return $this->render('chapter/show.html.twig', [
        'chapter' => $chapter,
        'previousChapter' => $previousChapter,
        'nextChapter' => $nextChapter,
        'commentForm' => $form->createView(), // 🚩 Aquí pasamos commentForm
    ]);
}

    #[Route('/{id}/edit', name: 'app_chapter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chapter $chapter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChapterForm::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chapter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chapter/edit.html.twig', [
            'chapter' => $chapter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chapter_delete', methods: ['POST'])]
    public function delete(Request $request, Chapter $chapter, EntityManagerInterface $entityManager): Response
    {
        // El token CSRF para el borrado debe coincidir con este id único
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$chapter->getId(), $submittedToken)) {
            $entityManager->remove($chapter);
            $entityManager->flush();
        } else {
            // Opcional: lanzar excepción o mostrar mensaje si el token no es válido
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        return $this->redirectToRoute('app_chapter_index', [], Response::HTTP_SEE_OTHER);
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
