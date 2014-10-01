<?php

namespace Bricks\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Bricks\UserBundle\Form\DataTransformer\TagsToIdsTransformer;

class ExternalResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
            ->add('url', 'text')
            ->add('published', 'checkbox')
        ;

        /*
         * add tags field
         */
        $builder->add('tags', 'text', array(
            'mapped' => false,
            'data' => implode(",", $entity->getTags()->toArray())
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bricks\SiteBundle\Entity\ExternalResource'
        ));
    }

    public function getName()
    {
        return 'bricks_userbundle_external_resourcetype';
    }
}