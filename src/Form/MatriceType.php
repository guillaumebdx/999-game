<?php

namespace App\Form;

use App\Entity\Block;
use App\Entity\Matrice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blocks', EntityType::class, [
                'choice_label' => 'number',
                'class'        => Block::class,
                'multiple'     => true,
                'expanded'     => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matrice::class,
        ]);
    }
}
