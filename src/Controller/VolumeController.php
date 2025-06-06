<?php

namespace App\Controller;

use App\Entity\Volume;
use App\Form\VolumeForm;
use App\Repository\VolumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/volume')]
final class VolumeController extends AbstractController
{
    #[Route(name: 'app_volume_index', methods: ['GET'])]
    public function index(VolumeRepository $volumeRepository): Response
    {
        return $this->render('volume/index.html.twig', [
            'volumes' => $volumeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_volume_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $volume = new Volume();
        $form = $this->createForm(VolumeForm::class, $volume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($volume);
            $entityManager->flush();

            return $this->redirectToRoute('app_volume_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('volume/new.html.twig', [
            'volume' => $volume,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_volume_show', methods: ['GET'])]
    public function show(Volume $volume): Response
    {
        return $this->render('volume/show.html.twig', [
            'volume' => $volume,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_volume_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Volume $volume, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VolumeForm::class, $volume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_volume_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('volume/edit.html.twig', [
            'volume' => $volume,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_volume_delete', methods: ['POST'])]
    public function delete(Request $request, Volume $volume, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$volume->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($volume);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_volume_index', [], Response::HTTP_SEE_OTHER);
    }
}
