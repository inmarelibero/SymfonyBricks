<?php

namespace Bricks\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('current_password');

        $builder->add('emailpolicy_send_on_new_message', 'checkbox', array(
            'label' => 'profile.emailpolicy_send_on_new_message.label',
            'translation_domain' => 'FOSUserBundle',
        ));

        $builder->add('profileImage', null, array(
            'label' => 'Profile image'
        ));

        /**
         * Event subscriber for generated email
         *
         * When a user registers via OAuth providers, an invalid email is stored in database.
         * The email value begins with "generated_" and has no "@". In this case the original value is replaced
         * with "" to let user enter an email
         */
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event)  {
                $form = $event->getForm();
                $data = $event->getData();

                // Got no idea why I have to add this to make it work.
                if ($data == null) {
                    return;
                }

                if (
                    preg_match("/^generated_/", $data->getEmail())
                    &&
                    !preg_match("/@/", $data->getEmail())
                ) {
                    $form->get('email')->setData('');
                }
            }
        );
    }

    public function getName()
    {
        return 'bricks_user_profile';
    }
}