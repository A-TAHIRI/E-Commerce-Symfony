<?php

namespace App\Form;
use App\Entity\Newsletters\Categories;
use App\Entity\Newsletters\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;




class NewslettersUsersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=>'Adresse E-mail'
                ]
               
                ])
                ->add('categories', EntityType::class, [
                    'class' => Categories::class,
                    'choice_label' => 'name',
                     'multiple' => true,
                      'expanded' => true
                ])

           
          
            ->add('je_mabonne', SubmitType::class,[
              
                'attr' => [
                    'class' => 'btn btn-primary',
                   
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}