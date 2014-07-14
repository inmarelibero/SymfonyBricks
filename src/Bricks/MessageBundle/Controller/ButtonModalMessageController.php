<?php

namespace Bricks\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Bricks\SiteBundle\Entity\Brick;

/**
 *
 */
class ButtonModalMessageController extends Controller
{
    /**
     * Show the UserMessage form to send a message to a specified user
     *
     * @Template()
     */
    public function _buttonModalMessageToUserAction(Brick $brick)
    {
        $form = $this->createForm($this->container->get('message_bundle.bricks_message_new_thread_message_from_brick_form.type'), array(
            'brick' => $brick,
            'recipient' => $brick->getUser()
        ));

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Show the UserMessage form to send a message to the admin, claiming the ownership of a brick
     *
     * @Template()
     */
    public function _buttonModalMessageClaimBrickAction(Brick $brick)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $form = $this->createForm($this->container->get('message_bundle.bricks_message_new_thread_message_from_brick_form.type'), array(
            'brick' => $brick,
            'recipient' => $userManager->findUserByUsername('inmarelibero'),
            'body' => $this->get('translator')->trans('fos_message_bundle.form.claim_brick.body', array(), 'FOSMessageBundle')
        ));

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Sends a message
     *
     * @Route("/ajax-send", name="message_ajax_send")
     * @method("POST")
     * @Template()
     */
    public function ajaxSendAction(Request $request)
    {
        /*
         * set the sender
         * check if user is logged
         */
        $sender = $this->container->get('security.context')->getToken()->getUser();
        if (!$sender) {
            return new AccessDeniedException();
        }

        $form = $this->createForm($this->container->get('message_bundle.bricks_message_new_thread_message_from_brick_form.type'));

        // bind the request
        $form->handleRequest($request);

        // send a message
        if ($form->isValid()) {

            // retrieve form data
            $data = $form->getData();

            // UserMessage composer
            $composer = $this->container->get('fos_message.composer');

            // compose a new message, creating a new thread
            $message = $composer->newThread()
                ->setSender($sender)
                ->addRecipient($data['recipient'])
                ->setSubject(
                    (strlen($data['body']) > 50) ? substr($data['body'], 0, 47).'...' : $data['body']
                )
                ->setBody($data['body'])
                ->getMessage()
            ;

            /*
             * set the Brick linked to the thread
             */
            $brick = $data['brick'];
            if ($brick) {
                $message->getThread()->setBrick($brick);
            }

            // send the message
            $this->container->get('fos_message.sender')->send($message);

            return new JsonResponse();
        }

        // initialize json empty array
        $json = array('errors' => array());

        // build json errors array
        $fields = $form->all();
        foreach ($fields as $field) {

            $errors = $field->getErrors();

            if (count($errors) > 0) {
                $json['errors'][$field->getName()] = array();
                foreach ($errors as $error) {
                    $json['errors'][$field->getName()][] = $error->getMessageTemplate();
                }
            }
        }

        return new JsonResponse($json, 400);
    }
}
