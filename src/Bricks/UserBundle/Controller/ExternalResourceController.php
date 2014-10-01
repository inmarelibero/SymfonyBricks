<?php

namespace Bricks\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Bricks\SiteBundle\Entity\ExternalResource;
use Bricks\UserBundle\Form\Type\ExternalResourceType;

/**
 * ExternalResource controller.
 *
 * @Route("/user/externalresource")
 */
class ExternalResourceController extends Controller
{
    /**
     * Lists all ExternalResource entities related to the currently authenticated user
     *
     * @Route("/", name="user_external_resource")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BricksSiteBundle:ExternalResource')->findBy(
            array('user' => $user->getId()),
            array('title' => 'ASC')
        );

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Lists all starred ExternalResource entities related to the currently authenticated user
     *
     * @Route("/starred", name="user_external_resource_starred")
     * @Template()
     */
    public function starredAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        return array(
            'entities' => $user->getStarredExternalResources()
        );
    }

    /**
     * Displays a form to create a new ExternalResource entity.
     *
     * @Route("/new", name="user_external_resource_new", options={"expose"=true})
     * @Template("BricksUserBundle:ExternalResource:edit.html.twig")
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new ExternalResource();

        /*
         * process the "content" parameter, prepopulate the "content" ExternalResource field
         */
        if ($this->getRequest()->getMethod() == 'POST') {
            $c = $this->getRequest()->get('content');

            $c = html_entity_decode($c);

            $entity->setContent($c);
        }

        $form   = $this->createForm(new ExternalResourceType($em), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new ExternalResource entity.
     *
     * @Route("/create", name="user_external_resource_create")
     * @Method("POST")
     * @Template("BricksUserBundle:ExternalResource:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity  = new ExternalResource();

        $form = $this->createForm(new ExternalResourceType(), $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            // set reporter
            $user = $this->get('security.context')->getToken()->getUser();
            $entity->setReporter($user);

            // persist entity
            $em->persist($entity);
            $em->flush();

            // persist tags
            $tagManager = $this->get('fpn_tag.tag_manager');
            $tags = $tagManager->loadOrCreateTags(explode(",", $form->get('tags')->getData()));

            $tagManager->replaceTags($tags, $entity);
            $tagManager->saveTagging($entity);

            // set flash message
            $this->get('session')->getFlashBag()->add('success', 'alert.external_resource.create.success');

            return $this->redirect($this->generateUrl('user_external_resource_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('danger', 'alert.external_resource.create.error');

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ExternalResource entity.
     *
     * @Route("/{id}/edit", name="user_external_resource_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:ExternalResource')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ExternalResource entity.');
        }

        // check user permissions on this external_resource
        $this->checkUserCanEditExternalResource($entity);

        // load external_resource tags
        $tagManager = $this->get('fpn_tag.tag_manager');
        $tagManager->loadTagging($entity);

        $editForm = $this->createForm(new ExternalResourceType(), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing ExternalResource entity.
     *
     * @Route("/{id}/update", name="user_external_resource_update")
     * @Method("POST")
     * @Template("BricksUserBundle:ExternalResource:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:ExternalResource')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ExternalResource entity.');
        }

        // check user permissions on this external_resource
        $this->checkUserCanEditExternalResource($entity);

        $form = $this->createForm(new ExternalResourceType(), $entity);

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
            $this->get('session')->getFlashBag()->add('success', 'alert.external_resource.update.success');

            return $this->redirect($this->generateUrl('user_external_resource_edit', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error', 'alert.external_resource.update.error');

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
     * @Route("/_render-markdown", name="_user_external_resource_renderMarkdown")
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
     * Deletes a ExternalResource entity.
     *
     * @Route("/{id}/delete", name="user_external_resource_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BricksSiteBundle:ExternalResource')->find($id);
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ExternalResource entity.');
            }

            // check user permissions on this external_resource
            $this->checkUserCanEditExternalResource($entity);

            // remove the entity
            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'alert.external_resource.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'alert.external_resource.delete.error');
        }

        return $this->redirect($this->generateUrl('user_external_resource'));
    }
    
    /**
     * returns a partial template to delete a external_resource
     * 
     * @Template
     */
    public function _deleteFormAction($id)
    {
        $form = $this->createDeleteForm($id);

        return array(
            'form' => $form->createView(),
            'id' => $id
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * check if a uer can edit a external_resource
     *
     * //@TODO: refactor to a service
     * 
     * @param unknown_type $external_resource
     * @throws AccessDeniedException
     */
    private function checkUserCanEditExternalResource(ExternalResource $external_resource)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        // grant accesso to admin
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return true;
        }
        
        if (!$external_resource->getUser() || $external_resource->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('You are not allowed to access this content');
        }
    }
    
    /**
     * Toggle the "published" state of a external_resource
     * 
     * @Route("/toggle-published/{id}", name="user_external_resource_toggle_published")
     * 
     * @param unknown_type $id
     */
    public function togglePublishedAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:ExternalResource')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ExternalResource entity.');
        }
        
        // check user permissions on this external_resource
        $this->checkUserCanEditExternalResource($entity);

        // toggle "published"
        $entity->setPublished(!$entity->getPublished());
        
        // saves the entity
        $em->persist($entity);
        $em->flush();
        
        if ($entity->getPublished()) {
            $this->get('session')->getFlashBag()->add('success', 'alert.external_resource.togglePublished.published');
        } else {
            $this->get('session')->getFlashBag()->add('success', 'alert.external_resource.togglePublished.unpublished');
        }
        
        return $this->redirect($this->generateUrl('user_external_resource'));
    }
}