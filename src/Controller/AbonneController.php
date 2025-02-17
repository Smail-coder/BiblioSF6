<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Repository\AbonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/abonne')]
final class AbonneController extends AbstractController
{
    // Injection du service de hachage de mot de passe via le constructeur
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route(name: 'app_abonne_index', methods: ['GET'])]
    public function index(AbonneRepository $abonneRepository): Response
    {
        return $this->render('abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_abonne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $abonne = new Abonne();
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hachage du mot de passe avant la persistance
            $hashedPassword = $this->passwordHasher->hashPassword(
                $abonne,
                $abonne->getPassword()
            );
            $abonne->setPassword($hashedPassword);

            $entityManager->persist($abonne);
            $entityManager->flush();

            $this->addFlash('success', 'Le compte a été créé avec succès.');
            return $this->redirectToRoute('app_abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abonne/new.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abonne_show', methods: ['GET'])]
    public function show(Abonne $abonne): Response
    {
        return $this->render('abonne/show.html.twig', [
            'abonne' => $abonne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_abonne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonne $abonne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification si un nouveau mot de passe a été soumis
            $plainPassword = $form->get('password')->getData();
            if (!empty($plainPassword)) {
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $abonne,
                    $plainPassword
                );
                $abonne->setPassword($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le compte a été modifié avec succès.');
            return $this->redirectToRoute('app_abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abonne/edit.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abonne_delete', methods: ['POST'])]
    public function delete(Request $request, Abonne $abonne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($abonne);
            $entityManager->flush();
            $this->addFlash('success', 'Le compte a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_abonne_index', [], Response::HTTP_SEE_OTHER);
    }
}
