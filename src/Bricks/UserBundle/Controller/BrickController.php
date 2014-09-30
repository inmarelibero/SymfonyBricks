<?php

namespace Bricks\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Bricks\SiteBundle\Entity\Brick;
use Bricks\UserBundle\Form\Type\BrickType;

/**
 * Brick controller.
 *
 * @Route("/user/brick")
 */
class BrickController extends Controller
{
    /**
     * Lists all Brick entities related to the currently authenticated user
     *
     * @Route("/", name="user_brick")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BricksSiteBundle:Brick')->findBy(
            array('user' => $user->getId()),
            array('title' => 'ASC')
        );

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Lists all starred Brick entities related to the currently authenticated user
     *
     * @Route("/starred", name="user_brick_starred")
     * @Template()
     */
    public function starredAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        return array(
            'entities' => $user->getStarredBricks()
        );
    }

    /**
     * Displays a form to create a new Brick entity.
     *
     * @Route("/new", name="user_brick_new", options={"expose"=true})
     * @Template("BricksUserBundle:Brick:edit.html.twig")
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Brick();

        /*
         * process the "content" parameter, prepopulate the "content" Brick field
         */
        if ($this->getRequest()->getMethod() == 'POST') {
            $c = $this->getRequest()->get('content');

            $c = html_entity_decode($c);

            $entity->setContent($c);
        }

        $form   = $this->createForm(new BrickType($em), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Brick entity.
     *
     * @Route("/create", name="user_brick_create")
     * @Method("POST")
     * @Template("BricksUserBundle:Brick:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity  = new Brick();

        $form = $this->createForm(new BrickType(), $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // set user
            $user = $this->get('security.context')->getToken()->getUser();
            $entity->setUser($user);

            // persist entity
            $em->persist($entity);
            $em->flush();

            // persist tags
            $tagManager = $this->get('fpn_tag.tag_manager');
            $tags = $tagManager->loadOrCreateTags(explode(",", $form->get('tags')->getData()));

            $tagManager->replaceTags($tags, $entity);
            $tagManager->saveTagging($entity);

            // set flash message
            $this->get('session')->getFlashBag()->add('success', 'alert.brick.create.success');

            return $this->redirect($this->generateUrl('user_brick_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('danger', 'alert.brick.create.error');

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Brick entity.
     *
     * @Route("/{id}/edit", name="user_brick_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }

        // check user permissions on this brick
        $this->checkUserCanEditBrick($entity);

        // load brick tags
        $tagManager = $this->get('fpn_tag.tag_manager');
        $tagManager->loadTagging($entity);

        $editForm = $this->createForm(new BrickType(), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Brick entity.
     *
     * @Route("/{id}/update", name="user_brick_update")
     * @Method("POST")
     * @Template("BricksUserBundle:Brick:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }

        // check user permissions on this brick
        $this->checkUserCanEditBrick($entity);

        $form = $this->createForm(new BrickType(), $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {

            // persis tentity
            $em->persist($entity);
            $em->flush();

            // persist tags
            $tagManager = $this->get('fpn_tag.tag_manager');
            $tags = $tagManager->loadOrCreateTags(explode(",", $form->get('tags')->getData()));

            $tagManager->replaceTags($tags, $entity);
            $tagManager->saveTagging($entity);

            // set flash message
            $this->get('session')->getFlashBag()->add('success', 'alert.brick.update.success');

            return $this->redirect($this->generateUrl('user_brick_edit', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error', 'alert.brick.update.error');

        return array(
            'entity'  => $entity,
            'form'    => $form->createView(),
        );
    }
    
    /**
     * Return the markdown formattation of an input text
     * 
     * //@TODO: refactor this function to some general utility class
     * 
     * @Route("/_render-markdown", name="_user_brick_renderMarkdown")
     * @Template()
     * @method("POST")
     * 
     * @param unknown_type $content
     */
    public function _renderMarkdownAction()
    {
        $content = $this->getRequest()->get('content');
        
        return array(
            'content' => $content
        );
    }

    /**
     * Deletes a Brick entity.
     *
     * @Route("/{id}/delete", name="user_brick_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Brick entity.');
            }

            // check user permissions on this brick
            $this->checkUserCanEditBrick($entity);

            // remove the entity
            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'alert.brick.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'alert.brick.delete.error');
        }

        return $this->redirect($this->generateUrl('user_brick'));
    }
    
    /**
     * returns a partial template to delete a brick
     * 
     * @Template
     */
    public function _deleteFormAction($id)
    {
        $form = $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;

        return array(
            'form' => $form->createView(),
            'id' => $id
        );
    }

    /**
     * check if a uer can edit a brick
     *
     * //@TODO: refactor to a service
     * 
     * @param unknown_type $brick
     * @throws AccessDeniedException
     */
    private function checkUserCanEditBrick(Brick $brick)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        if (!$brick->getUser() || $brick->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('Yo are not allowed to access this content');
        }
    }
    
    /**
     * Toggle the "published" state of a brick
     * 
     * @Route("/toggle-published/{id}", name="user_brick_toggle_published")
     * 
     * @param unknown_type $id
     */
    public function togglePublishedAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }
        
        // check user permissions on this brick
        $this->checkUserCanEditBrick($entity);

        // toggle "published"
        $entity->setPublished(!$entity->getPublished());
        
        // saves the entity
        $em->persist($entity);
        $em->flush();
        
        if ($entity->getPublished()) {
            $this->get('session')->getFlashBag()->add('success', 'alert.brick.togglePublished.published');
        } else {
            $this->get('session')->getFlashBag()->add('success', 'alert.brick.togglePublished.unpublished');
        }
        
        return $this->redirect($this->generateUrl('user_brick'));
    }
}