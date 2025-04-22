<?php

namespace App\Service;

use App\Entity\Attachment;
use App\Entity\Issue; // Changé de App\Twig\Components\Issue
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request; // Corrigé de http\Env\Request
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AttachmentService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        #[Autowire('%absolute_attachments_dir%')] private readonly string $absoluteAttachmentsDir,
        #[Autowire('%attachments_dir%')] private readonly string $attachmentsDir,
    ) {
    }

    public function handleUploadedAttachment(Issue $issue, Request $request): ?Attachment
    {
        $uploadedAttachment = $request->files->get('attachment');

        if (!$uploadedAttachment) {
            return null;
        }

        $fileName = $this->uniqueFileName($uploadedAttachment);

        $attachment = new Attachment();
        $attachment->setIssue($issue);
        $attachment->setOriginalName($uploadedAttachment->getClientOriginalName());
        $attachment->setPath($this->absoluteAttachmentsDir . DIRECTORY_SEPARATOR . $fileName);

        $uploadedAttachment->move($this->attachmentsDir, $fileName); // Corrigé pour utiliser le dossier physique

        $this->entityManager->persist($attachment);
        $this->entityManager->flush();

        return $attachment;
    }

    private function uniqueFileName($uploadedFile): string
    {
        return uniqid(more_entropy: true) . '.' . $uploadedFile->guessExtension();
    }

    public function delete(Attachment $attachment): void
    {
        $filename = $this->attachmentsDir
            . DIRECTORY_SEPARATOR
            . pathinfo($attachment->getPath(), PATHINFO_FILENAME)
            . '.'
            . pathinfo($attachment->getPath(), PATHINFO_EXTENSION);

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

}