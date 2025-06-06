<?php

namespace App\Form;

use App\Entity\book;
use App\Entity\Chapter;
use App\Entity\role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('slug')
            ->add('content')
            ->add('publishedAt')
            ->add('book', EntityType::class, [
                'class' => book::class,
                'choice_label' => 'title',
            ])
            ->add('role', EntityType::class, [
                'class' => role::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Chapter::class,
        ]);
    }
}
