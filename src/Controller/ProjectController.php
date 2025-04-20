<?php

namespace App\Controller;

use App\Entity\Project;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    ){}


    #[Route('/project/{keyCode}', name: 'project_show')]
    public function show(?Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/projects', name: 'project_list')]
    public function list(): Response
    {
        dump($this->getUser());
        dump($this->getUser()->getProjects());
        dump($this->projectService->getProjectsList($this->getUser()));

        return $this->render('project/list.html.twig', [
            'projects' => $this->projectService->getProjectsList($this->getUser())
        ]);
    }
}
