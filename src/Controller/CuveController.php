<?php

namespace App\Controller;

use App\Entity\Cuve;
use App\Form\CuveType;
use App\Repository\CuveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cuve')]
final class CuveController extends AbstractController
{
    #[Route(name: 'cuve_index', methods: ['GET'])]
    public function index(CuveRepository $cuveRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $cuveRepository->findAllOrderedQuery();

        $cuves = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('cuve/index.html.twig', [
            'cuves' => $cuves,
            'current_menu' => 'cuve',
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_cuve_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cuve = new Cuve();
        $form = $this->createForm(CuveType::class, $cuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cuve);
            $entityManager->flush();

            return $this->redirectToRoute('cuve_index');
        }

        return $this->render('cuve/new.html.twig', [
            'cuve' => $cuve,
            'form' => $form,
            'current_menu' => 'cuve',
        ]);
    }

    #[Route('/{id}', name: 'app_cuve_show', methods: ['GET'])]
    public function show(Cuve $cuve): Response
    {
        return $this->render('cuve/show.html.twig', [
            'cuve' => $cuve,
            'current_menu' => 'cuve',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_cuve_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cuve $cuve, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CuveType::class, $cuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('cuve_index');
        }

        return $this->render('cuve/edit.html.twig', [
            'cuve' => $cuve,
            'form' => $form,
            'current_menu' => 'cuve',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_cuve_delete', methods: ['POST'])]
    public function delete(Request $request, Cuve $cuve, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cuve->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cuve);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cuve_index');
    }
}
