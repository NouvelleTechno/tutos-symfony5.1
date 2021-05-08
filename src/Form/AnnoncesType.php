<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\Departements;
use App\Entity\Regions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', HiddenType::class)
            ->add('categories', EntityType::class, [
                'class' => Categories::class
            ])
            // On ajoute le champ "images" dans le formulaire
            // Il n'est pas lié à la base de données (mapped à false)
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])

            ->add('regions', EntityType::class, [
                'mapped' => false,
                'class' => Regions::class,
                'choice_label' => 'name',
                'placeholder' => 'Région',
                'label' => 'Région',
                'required' => false
            ])

            ->add('departements', ChoiceType::class, [
                'placeholder' => 'Département (Choisir une région)',
                'required' => false
            ])

            ->add('Valider', SubmitType::class)
        ;

        $formModifier = function (FormInterface $form, Regions $regions = null) {
            $departements = null === $regions ? [] : $regions->getDepartements();

            $form->add('departements', EntityType::class, [
                'class' => Departements::class,
                'choices' => $departements,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Département (Choisir une région)',
                'attr' => ['class' => 'custom-select'],
                'label' => 'Département'
            ]);
        };

        $builder->get('regions')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $region = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $region);
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
