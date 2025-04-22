<?php

namespace App\Twig\Components;

use App\Enum\IssueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class SelectIssueType
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public \App\Entity\Issue $issue;

    /** @var IssueType[] */
    #[LiveProp]
    public array $types = [];

    #[LiveProp(writable: true)]
    public IssueType $type;

    #[LiveAction]
    public function updateType(EntityManagerInterface $em): void
    {
        $this->validate();


        $this->issue->setType($this->type);

        $em->flush();
    }

}
