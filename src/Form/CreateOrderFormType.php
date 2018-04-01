<?php

namespace App\Form;

use App\Entity\WidgetOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateOrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add(
                'Color',
                null,
                ['required' => true]
            )
            ->add(
            'neededBy',
            DateType::class,
                [
                    'widget' => 'single_text',
                    'label' => 'Date needed by'
                ]
            )
            ->add(
                'WidgetType',
                null,
                ['required' => true]
            )
            ->add(
                'user_email',
                EmailType::class,
                [
                    'label' => 'Your email address',
                    'mapped' => false
                ]
            )
            ->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => WidgetOrder::class,
        ));
    }
}