<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\IssueStatus;
use App\Repository\IssueRepository;
use Symfony\Bundle\SecurityBundle\Security;

class IssueService
{
    public function __construct(
        private readonly Security $security
    ) {}

    public function getReadyIssues(): array
    {
        return $this->getIssuesByStatus([IssueStatus::READY]);
    }

    public function getInProgressIssues(): array
    {
        return $this->getIssuesByStatus([IssueStatus::IN_DEVELOPMENT, IssueStatus::IN_REVIEW]);
    }

    public function getResolvedIssues(): array
    {
        return $this->getIssuesByStatus([IssueStatus::RESOLVED]);
    }


    private function getIssuesByStatus(array $statuses): array
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user
            ->getSelectedProject()
            ->getIssues()
            ->filter(fn ($issue) => in_array($issue->getStatus(), $statuses))
            ->toArray();
    }
}
