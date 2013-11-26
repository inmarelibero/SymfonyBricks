<?php

namespace Bricks\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Bricks\UserBundle\Form\DataTransformer\TagsToIdsTransformer;

class BrickType extends AbstractType
{
    private $em;
    
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $brick = $builder->getData();
        
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
            ->add('canonical_url', 'text')
            ->add('content', 'textarea')
            ->add('brick_license', 'entity', array(
                'class' => 'BricksSiteBundle:BrickLicense',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.title', 'ASC');
                },
                'property' => 'title',
                'empty_value' => '== no license =='
            ))
        ;
        
        // data transformer for brickHasTags field
        $transformer = new TagsToIdsTransformer($this->em, $brick);
        
        // add brickHasTags field, with Data Transformer
        $builder->add(
            $builder->create('brickHasTags', 'hidden')->addModelTransformer($transformer)
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bricks\SiteBundle\Entity\Brick'
        ));
    }

    public function getName()
    {
        return 'bricks_userbundle_bricktype';
    }
}