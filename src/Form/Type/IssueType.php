<?php

namespace App\Form\Type;

use App\Entity\Project;
use App\Entity\User;
use App\Enum\IssueStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class IssueType extends AbstractType

{
    public function __construct(
        private readonly Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $project = $this->security->getUser()?->getSelectedProject();
        
        $builder
            ->add('project', EntityType::class, [
                'attr' => [
                    'class' => 'd-none'
                ],
                'class' => Project::class,
                'data' => $project,
                'label' => false
            ])
            ->add('type', EnumType::class, [
                'choice_label' => fn (\App\Enum\IssueType $type) => $type->label(),
                'class' => \App\Enum\IssueType::class,
                'label' => 'Type'
            ])
            ->add('status', EnumType::class, [
                'choice_label' => fn (IssueStatus $status) => $status->label(),
                'class' => IssueStatus::class,
                'label' => 'Status'
            ])
            ->add('summary', TextType::class, [
                'label' => 'Summary',
            ])
            ->add('assignee', EntityType::class, [
                'class' => User::class,
                'choices' => $project?->getMembers()?->toArray() ?? [],
                'label' => 'Assignee',
            ])
            ->add('reporter', EntityType::class, [
                'class' => User::class,
                'choices' => $project?->getMembers()?->toArray() ?? [],
                'label' => 'Reporter',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

    }
}
