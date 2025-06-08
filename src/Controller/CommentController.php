<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Comment;
use App\Form\CommentTypeForm;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comments', name: 'comment_index')]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/comment/{id}', name: 'comment_show')]
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/chapter/{id}/comment', name: 'chapter_comment', methods: ['POST'])]
public function comment(Request $request, Chapter $chapter, EntityManagerInterface $entityManager, Security $security): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    $comment = new Comment();
    $comment->setChapter($chapter);
    $comment->setAuthor($security->getUser());

    $form = $this->createForm(CommentTypeForm::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Comentario enviado correctamente.');

        return $this->redirectToRoute('chapter_show', ['id' => $chapter->getId()]);
    }

    return $this->render('chapter/show.html.twig', [
        'chapter' => $chapter,
        'commentForm' => $form->createView(),
    ]);
}
}