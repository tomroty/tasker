<?php

namespace App\Twig\Components;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class InputStoryPointEstimate
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: ['storyPointEstimate'])]
    public \App\Entity\Issue $issue;

    #[LiveAction]
    public function updateStoryPointEstimate(EntityManagerInterface $em): void
    {
        $this->validate();

        $em->flush();
    }
}
