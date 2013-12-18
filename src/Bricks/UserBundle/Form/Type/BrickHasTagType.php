<?php
namespace Bricks\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BrickHasTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tag', 'entity', array(
            'class' => 'BricksSiteBundle:Tag',
            'property' => 'title'
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Bricks\SiteBundle\Entity\BrickHasTag',
        );
    }

    public function getName()
    {
        return 'bricks_userbundle_brick_has_tagtype';
    }
}