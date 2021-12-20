<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MissionController extends AbstractController
{
    /** @var MissionRepository */
    private $missionRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->missionRepository = $entityManager->getRepository(Mission::class);
    }

    #[Route('/missions/create', name: 'create_mission')]
    public function createMission(Request $request): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mission = $form->getData();
            $this->missionRepository->save($mission);

            return $this->redirectToRoute('missions_list');
        }

        return $this->renderForm('mission/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/missions/{id}/update', name: 'update_mission')]
    public function updtateMission(int $id, Request $request): Response
    {
        $mission = $this->missionRepository->find($id);

        if (null === $mission)
        {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mission = $form->getData();
            $this->missionRepository->save($mission);

            return $this->redirectToRoute('missions_list');
        }

        return $this->renderForm('mission/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/missions', name: 'missions_list')]
    public function missionsList(): Response
    {
        $missions = $this->missionRepository->findAll();

        return $this->render('mission/index.html.twig', [
            'missions' => $missions,
        ]);
    }

    #[Route('/missions/{id}', name: 'mission_details')]
    public function missionDetails(int $id): Response
    {
        $mission = $this->missionRepository->find($id);

        if (null === $mission) {
            throw new NotFoundHttpException();
        }

        return $this->render('mission/details.html.twig', [
            'mission' => $mission
        ]);
    }

    #[Route('/missions/{id}/delete', name: 'delete_mission')]
    public function deleteMission(int $id): Response
    {
        $mission = $this->missionRepository->find($id);

        if (null === $mission) {
            throw new NotFoundHttpException();
        }

        $this->missionRepository->delete($mission);

        return $this->redirectToRoute('missions_list');
    }

}
