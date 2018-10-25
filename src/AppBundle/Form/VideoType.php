<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use AppBundle\Entity\Video;
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
        $builder->add('url', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Coller la balise embed de la video',
            ], ]);

        $builder->get('url')
            ->addModelTransformer(new CallbackTransformer(
                function ($url) {
                    return $url;
                },
                function ($embedToUrl) {
                    // transform the embed tag into url
                    preg_match(
                        "/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})\/(embed)\/([\/\w \.-]*)*\/?/im",
                        $embedToUrl,
                        $matches
                    );
                    if ($matches) {
                        return $matches[0];
                    }

                    return $embedToUrl;
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_video';
    }
}
