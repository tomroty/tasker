<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Form\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class ProfileForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    public ?User $user = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            UserType::class,
            $this->user ?: $this->getUser()
        );
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        $this->validate();
        $this->submitForm();
        $entityManager->flush();
    }
}
