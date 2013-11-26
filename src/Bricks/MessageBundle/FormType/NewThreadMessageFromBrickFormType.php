<?php

namespace Bricks\MessageBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use FOS\MessageBundle\Validator\SelfRecipient;

class NewThreadMessageFromBrickFormType extends AbstractType
{
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', 'textarea', array(
            ))
            ->add('recipient', 'fos_user_username', array(
            ))
            ->add('brick', 'entity', array(
                'class' => 'Bricks\SiteBundle\Entity\Brick',
                'property' => 'id'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'body' => new NotBlank(array(
                'message' => $this->translator->trans('fos_message_bundle.form.error.please_enter_a_body', array(), 'validators')
            )),
            'recipient' => array(
                new SelfRecipient(),
                new NotBlank(array(
                    'message' => $this->translator->trans('fos_message_bundle.form.error.no_recipient_specified', array(), 'validators')
                ))
            ),
            'brick' => array()
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint,
            //'validation_groups' => array('NewThreadMessageFromBrickFormType')
        ));
    }

    public function getName()
    {
        return 'bricks_message_new_thread_message_from_brick';
    }
}
