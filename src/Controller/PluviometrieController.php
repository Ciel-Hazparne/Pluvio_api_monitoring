<?php
namespace App\Controller;

use App\Entity\Pluviometrie;
use App\Form\PluviometrieType;
use App\Repository\PluviometrieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/pluviometrie')]
final class PluviometrieController extends AbstractController
{
    #[Route(name: 'app_pluviometrie_index', methods: ['GET'])]
    public function index(PluviometrieRepository $pluviometrieRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $pluviometrieRepository->findAllOrderedQuery();

        $pluviometries = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('pluviometrie/index.html.twig', [
            'pluviometries' => $pluviometries,
            'current_menu' => 'pluviometrie',
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_pluviometrie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pluviometrie = new Pluviometrie();
        $form = $this->createForm(PluviometrieType::class, $pluviometrie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pluviometrie);
            $entityManager->flush();

            return $this->redirectToRoute('app_pluviometrie_index');
        }

        return $this->render('pluviometrie/new.html.twig', [
            'pluviometrie' => $pluviometrie,
            'form' => $form,
            'current_menu' => 'pluviometrie',
        ]);
    }

    #[Route('/{id}', name: 'app_pluviometrie_show', methods: ['GET'])]
    public function show(Pluviometrie $pluviometrie): Response
    {
        return $this->render('pluviometrie/show.html.twig', [
            'pluviometrie' => $pluviometrie,
            'current_menu' => 'pluviometrie',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_pluviometrie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pluviometrie $pluviometrie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PluviometrieType::class, $pluviometrie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pluviometrie_index');
        }

        return $this->render('pluviometrie/edit.html.twig', [
            'pluviometrie' => $pluviometrie,
            'form' => $form,
            'current_menu' => 'pluviometrie',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_pluviometrie_delete', methods: ['POST'])]
    public function delete(Request $request, Pluviometrie $pluviometrie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pluviometrie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pluviometrie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pluviometrie_index');
    }
}


