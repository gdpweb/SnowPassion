<?php

namespace AppBundle\Form;

use AppBundle\Entity\Trick;
use AppBundle\Repository\GroupeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'rows' => 10
                )
            ))
            ->add('groupe', EntityType::class, array(
                'class' => 'AppBundle\Entity\Groupe',
                'choice_label' => 'nom',
                'multiple' => false,
                'query_builder' => function (GroupeRepository $repository) {
                    return $repository->getListGroupes();
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Trick::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_trick';
    }
}
