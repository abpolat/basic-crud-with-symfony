<?php
/**
 * Created by PhpStorm.
 * User: BlackSide
 * Date: 18.05.2017
 * Time: 03:52
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TodoController extends Controller
{
    /**
     * @Route("/todo/{page}", defaults={"page" = 1}, requirements={"page": "\d+"}, name="todo_list")
     */
    public function listAction(Request $request, $page)
    {


        /*$todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findAll();*/

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT td FROM AppBundle:Todo td";
        $query = $em->createQuery($dql);
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator = $this->get('knp_paginator');
        $todos = $paginator->paginate(
            $query,
            $page,
            $request->query->getInt('limit', 5)
        );


        return $this->render('todo/index.html.twig', array(
            'todos' => $todos,
            'page' => $page
        ));
    }
    /**

     * @Route("/todo/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo;

        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, array('attr' => array('class'=>'form-control', 'style' => 'margin-bottom:15px;')))
            ->add('category', TextType::class, array('attr' => array('class'=>'form-control', 'style' => 'margin-bottom:15px;')))
            ->add('description', TextareaType::class, array('attr' => array('class'=>'form-control', 'style' => 'margin-bottom:15px;')))
            ->add('priority', ChoiceType::class, array('choices' => array('Low'=>'Low','Normal'=>'Normal','High'=>'High'),'attr' => array('class'=>'form-control', 'style' => 'margin-bottom:15px;')))
            ->add('due_date', DateTimeType::class, array('attr' => array('class'=>'formcontrol', 'style' => 'margin-bottom:15px;')))
            ->add('save', SubmitType::class, array('attr' => array('label'=>'Create Todo','class'=>'btn btn-primary', 'style' => 'margin-bottom:15px;')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Get Data
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();

            $now = new \DateTime('now');

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            $this->addFlash(
                'notice',
                'Todo Added'
            );

            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo/create.html.twig', array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/todo/edit/{id}", name="todo_edit")
     */
    public function editAction($id, Request $request)
    {

        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);
        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, array('attr' => array('class'=>'form-control', 'style' => 'margin-bottom:15px;')))
            ->add('category', TextType::class, array('attr' => array('class'=>'form-control', 'style' => 'margin-bottom:15px;')))
            ->add('description', TextareaType::class, array('attr' => array('class'=>'form-control', 'style' => 'margin-bottom:15px;')))
            ->add('priority', ChoiceType::class, array('choices' => array('Low'=>'Low','Normal'=>'Normal','High'=>'High'),'attr' => array('class'=>'form-control', 'style' => 'margin-bottom:15px;')))
            ->add('due_date', DateTimeType::class, array('attr' => array('class'=>'formcontrol', 'style' => 'margin-bottom:15px;')))
            ->add('update', SubmitType::class, array('attr' => array('label'=>'Update Todo','class'=>'btn btn-primary', 'style' => 'margin-bottom:15px;')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Get Data
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();

            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('AppBundle:Todo')->find($id);

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);


            $em->flush();

            $this->addFlash(
                'notice',
                'Todo Updated'
            );

            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo/edit.html.twig', array(
            'form' => $form->createView(),
            'todo' => $todo,
        ));


    }

    /**
     * @Route("/todo/details/{id}", name="todo_details")
     */
    public function detailsAction($id)
    {
        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);
        return $this->render('todo/details.html.twig', array(
            'todo' => $todo
        ));
        
        
    }

    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository('AppBundle:Todo')->find($id);

        $em->remove($todo);
        $em->flush();

        $this->addFlash(
            'notice',
            'Todo Removed'
        );

        return $this->redirectToRoute('todo_list');


    }


}