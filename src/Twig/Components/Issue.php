<?php

namespace App\Twig\Components;

use App\Repository\AttachmentRepository;
use App\Service\AttachmentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
class Issue
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: ['description', 'summary'])]
    public \App\Entity\Issue $issue;

    #[LiveProp(writable: true)]
    /** @var \App\Entity\Attachment[] */
    public array $attachments = [];

    #[LiveProp]
    public bool $isEditingSummary = false;

    #[LiveProp]
    public bool $isEditingDescription = false;

    public function __construct(
        private readonly AttachmentService $attachmentService,
        private readonly EntityManagerInterface $em
    ) {}

    #[LiveAction]
    public function activateEditingSummary(): void
    {
        $this->isEditingSummary = true;
    }

    #[LiveAction]
    public function activateEditingDescription(): void
    {
        $this->isEditingDescription = true;
    }

    #[LiveAction]
    public function saveSummary(): void
    {
        $this->validate();
        $this->isEditingSummary = false;
        $this->em->flush();
    }

    #[LiveAction]
    public function saveDescription(): void
    {
        $this->validate();
        $this->isEditingDescription = false;
        $this->em->flush();
    }

    #[LiveAction]
    public function addAttachment(Request $request): void
    {
        $attachment = $this->attachmentService->handleUploadedAttachment($this->issue, $request);
        if ($attachment) {
            $this->attachments[] = $attachment;
        }
    }
}