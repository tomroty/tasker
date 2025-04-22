<?php

namespace App\Twig\Components;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class SelectIssueReporter
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public \App\Entity\Issue $issue;

    /** @var User[] */
    #[LiveProp]
    public array $people = [];

    #[LiveProp(writable: true)]
    public ?User $assignee = null;

    #[LiveAction]
    public function updateAssignee(EntityManagerInterface $em): void
    {
        $this->validate();
        $this->issue->setAssignee($this->assignee);
        $em->flush();
    }
}