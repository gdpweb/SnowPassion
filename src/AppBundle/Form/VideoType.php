<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Saisir un Url valide',
                ))

        );

//        $builder->get('url')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($iframeToUrl) {
//                    preg_match("/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/im", $iframeToUrl, $matches);
//                    dump($matches);
//                    return $matches[0];
//                },
//                function ($urlToIframe) {
//                    // transform the string back to an array
//                    return $urlToIframe;
//                }
//            ))
//        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Video'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_video';
    }


}
