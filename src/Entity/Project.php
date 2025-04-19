<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[Assert\Length(min: 2, max: 10)]
    #[Assert\NotBlank]
    #[ORM\Column(name: '`key_code`', length: 10, unique: true)]
    private ?string $keyCode = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Issue::class, orphanRemoval: true)]
    private Collection $issues;

    #[ORM\ManyToOne(inversedBy: 'leadedProjects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $leadUser = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    private Collection $people;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
        $this->people = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getKeyCode(): ?string
    {
        return $this->keyCode;
    }

    public function setKeyCode(string $keyCode): static
    {
        $this->keyCode = $keyCode;

        return $this;
    }

    public function getLeadUser(): ?User
    {
        return $this->leadUser;
    }

    public function setLeadUser(?User $leadUser): static
    {
        $this->leadUser = $leadUser;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }

    /**
     * @return Collection<int, Issue>
     */
    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function addIssue(Issue $issue): static
    {
        if (!$this->issues->contains($issue)) {
            $this->issues->add($issue);
            $issue->setProject($this);
        }

        return $this;
    }

    public function removeIssue(Issue $issue): static
    {
        if ($this->issues->removeElement($issue)) {
            // set the owning side to null (unless already changed)
            if ($issue->getProject() === $this) {
                $issue->setProject(null);
            }
        }

        return $this;
    }
}
