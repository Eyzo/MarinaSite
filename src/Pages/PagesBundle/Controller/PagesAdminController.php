<?php

namespace Pages\PagesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pages\PagesBundle\Entity\Pages;
use Pages\PagesBundle\Form\PagesType;

/**
 * Pages controller.
 *
 */
class PagesAdminController extends Controller
{

    /**
     * Lists all Pages entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PagesBundle:Pages')->findAll();

        return $this->render('PagesBundle:Administration:Pages/index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Pages entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Pages();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adminPages_show', array('id' => $entity->getId())));
        }

        return $this->render('PagesBundle:Administration:Pages/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Pages entity.
     *
     * @param Pages $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pages $entity)
    {
        $form = $this->createForm(new PagesType(), $entity, array(
            'action' => $this->generateUrl('adminPages_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter','attr' => array('class' => 'button-success','style'=>'margin-top:10px;')));

        return $form;
    }

    /**
     * Displays a form to create a new Pages entity.
     *
     */
    public function newAction()
    {
        $entity = new Pages();
        $form   = $this->createCreateForm($entity);

        return $this->render('PagesBundle:Administration:Pages/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Pages entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PagesBundle:Pages')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pages entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PagesBundle:Administration:Pages/show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Pages entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PagesBundle:Pages')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pages entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PagesBundle:Administration:Pages/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Pages entity.
    *
    * @param Pages $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Pages $entity)
    {
        $form = $this->createForm(new PagesType(), $entity, array(
            'action' => $this->generateUrl('adminPages_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier','attr' => array('class' => 'button-success','style' => 'margin-top:10px')));

        return $form;
    }
    /**
     * Edits an existing Pages entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PagesBundle:Pages')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pages entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('adminPages_edit', array('id' => $id)));
        }

        return $this->render('PagesBundle:Administration:Pages/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Pages entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PagesBundle:Pages')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pages entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adminPages'));
    }

    /**
     * Creates a form to delete a Pages entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminPages_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer','attr' => array('class' => 'button-danger','style' => 'float:right')))
            ->getForm()
        ;
    }
}
