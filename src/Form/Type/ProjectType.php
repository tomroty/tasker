<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\SecurityBundle\Security;





class ProjectType extends AbstractType
{

    public function __construct(
        private readonly Security $security,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Project Name',
            ])
            ->add('keyCode', TextType::class, [
                'label' => 'Project Key Code',
            ])
            ->add('leadUser', EntityType::class, [
                'attr' => [
                    'class' => 'd-none',
                ],

                'label' => FALSE,
                'data' => $this->security->getUser(),
                'class' => User::class,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Project',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
