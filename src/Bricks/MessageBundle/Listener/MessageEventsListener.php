<?php
namespace Bricks\MessageBundle\Listener;

use FOS\MessageBundle\Event\MessageEvent;

/**
 * Listener for events  of FOS\MessageBundle\Event\MessageEvent class
 */
class MessageEventsListener
{
    protected $mailer;
    protected $templating;
    protected $configSymfonyBricks;

    public function __construct(\Swift_Mailer $mailer, $templating, $configSymfonyBricks)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->configSymfonyBricks = $configSymfonyBricks;
    }

    /**
     * Listener to event 'fos_message.post_send'
     *
     * @param \FOS\MessageBundle\Event\MessageEvent $event
     */
    public function onPostSend(MessageEvent $event)
    {
        $message = $event->getMessage();

        $sender = $message->getSender();

        foreach ($message->getThread()->getOtherParticipants($sender) as $recipient) {

            if (true === $recipient->getEmailpolicySendOnNewMessage()) {

                $email = \Swift_Message::newInstance()
                    ->setSubject("SymfonyBricks: {$sender->getUsername()} sent you a new message")
                    ->setFrom($this->configSymfonyBricks['email_addresses']['contact'])
                    ->setTo($recipient->getEmail())
                    ->setBody(
                        $this->templating->render(
                            'BricksMessageBundle:Email:emailOnNewMessage.html.twig', array(
                                'sender' => $sender,
                                'userMessage' => $message
                            )
                        ), 'text/html'
                    )
                ;

                $this->mailer->send($email);
            }

        }
    }
}
