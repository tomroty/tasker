<?php

namespace App\Twig\Components;

use App\Service\ProjectService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;


#[AsLiveComponent]
class ProjectsList
{
    use DefaultActionTrait;

    #[LiveProp]
    public array $projects = [];

    public function __construct(
        private readonly ProjectService $projectService
    ) {}


    #[LiveAction]
    public function deleteProject(#[LiveArg] string $keyCode): void
    {
        $project = $this->projectService->findOneByKeyCode($keyCode);

        if (!$project) {
            return;
        }

        $this->projectService->remove($project);

        unset($this->projects[$keyCode]);


    }
    
}
