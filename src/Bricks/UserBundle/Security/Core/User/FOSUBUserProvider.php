<?php
namespace Bricks\UserBundle\Security\Core\User;

use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;

class FOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        /*
         * Load user by connector field
         *
         * eg: $user = $this->userManager->findUserBy(array('githubId' => '375734252'));
         */
        try {
            //if user exists - go with the HWIOAuth way
            $user = parent::loadUserByOAuthUserResponse($response);

            $serviceName = $response->getResourceOwner()->getName();
            $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

            //update access token
            $user->$setter($response->getAccessToken());

            return $user;

        } catch (AccountNotLinkedException $e) {
            /*
             * User is registrating
             */
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';

            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($response->getUsername());
            $user->$setter_token($response->getAccessToken());


            // set username
            $user->setUsername($response->getNickname());
            for ($i = 2; $i < 500; $i++) {
                if ($this->userManager->findUserByUsername($user->getUsername()) !== null) {
                    $user->setUsername($response->getNickname().$i);
                } else {
                    break;
                }
            }

            /*
             * set email
             *
             * email is set random. see comment below
             */
            $user->setEmail("generated_".uniqid("", true));

            /**
             * This should be an invalid password
             */
            $user->setPassword("?".uniqid(true));

            $user->setEnabled(true);

            $this->userManager->updateUser($user);
            return $user;
        }

        /*
         * Load user by email
         *
         * INSECURE: a user could set an arbitrary email in eg. github, then logging
         * automatically in with it
         */
        /*
        if ($response->getEmail() != '') {
            return $this->userManager->findUserByEmail($response->getEmail());
        }
        */

        throw new \Exception("User could not be neither retrieved nor created.");
    }
}