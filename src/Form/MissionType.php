<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Weak' => 0,
                    'Medium' => 1,
                    'Difficult' => 2,
                ],
            ])
            /*->add('publishedAt', DateType::class, [
                'widget' => 'choice',
                'input' => 'date_immutable'
            ])*/
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'To do' => 0,
                    'In progress' => 1,
                    'Done' => 2,
                ],
            ])
            /*->add('heroes', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => false,
                'expanded' => false,
            ])*/
            ->add('heroes', TextareaType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Create / Update'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
