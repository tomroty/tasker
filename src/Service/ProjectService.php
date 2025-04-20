<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProjectRepository $projectRepo
    ) {}

    public function getProjectsList(User $user): array
    {
        $projects = [];

        foreach ($user->getProjects() as $project) {
            $projects[$project->getKeyCode()] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'keyCode' => $project->getKeyCode(),
                'lead' => (string) $project->getLeadUser(),
            ];
        }

        return $projects;
    }

    public function findOneByKeyCode(string $keyCode)
    {
        return $this->projectRepo->findOneBy(['keyCode' => $keyCode]);
    }

    public function remove(Project $project): void
    {
  
        $users = $this->entityManager->getRepository(\App\Entity\User::class)
            ->findBy(['selectedProject' => $project]);
        

        foreach ($users as $user) {
            $user->setSelectedProject(null);
        }
        

        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }
}