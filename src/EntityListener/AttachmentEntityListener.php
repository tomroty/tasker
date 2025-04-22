<?php

namespace App\EntityListener;

use App\Entity\Attachment;
use App\Service\AttachmentService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::postRemove, entity: Attachment::class)]
class AttachmentEntityListener
{
    public function __construct(
        private readonly AttachmentService $attachmentService
    ) {
    }

    public function postRemove(Attachment $attachment, LifecycleEventArgs $event): void
    {
        $this->attachmentService->delete($attachment);
    }
}
