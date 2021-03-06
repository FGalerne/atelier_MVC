<?php

namespace MvcBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use MvcBundle\Form\TodoType;

/**
 * Todo controller.
 *
 */
class TodoController extends Controller
{
    /**
     * Lists all Todo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $todos = $em->getRepository('MvcBundle:Todo')->findAll();

        return $this->render('MvcBundle:todo:index.html.twig', array(
            'todos' => $todos,
        ));
    }

    /**
     * Creates a new Todo entity.
     *
     */
    public function newAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createForm('MvcBundle\Form\TodoType', $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_delete', array('id' => $todo->getId()));
        }

        return $this->render('MvcBundle:todo:new.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Todo entity.
     *
     */
    public function showAction(Todo $todo)
    {
        $deleteForm = $this->createDeleteForm($todo);

        return $this->render('MvcBundle:todo:show.html.twig', array(
            'todo' => $todo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Todo entity.
     *
     */
    public function editAction(Request $request, Todo $todo)
    {
        $deleteForm = $this->createDeleteForm($todo);
        $editForm = $this->createForm('MvcBundle\Form\TodoType', $todo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_edit', array('id' => $todo->getId()));
        }

        return $this->render('MvcBundle:todo:edit.html.twig', array(
            'todo' => $todo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Todo entity.
     *
     */
    public function deleteAction(Request $request, Todo $todo)
    {
        $form = $this->createDeleteForm($todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($todo);
            $em->flush();
        }

        return $this->redirectToRoute('todo_index');
    }

    /**
     * Creates a form to delete a Todo entity.
     *
     * @param Todo $todo The Todo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Todo $todo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('todo_delete', array('id' => $todo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
