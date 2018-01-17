<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactController extends Controller
{
    /**
     * @Route("/contact/new", name="new_contact")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
            ->add('name', TextType::class, [
                'label' => 'nom',
                'attr' => [
                    'class' => 'control-animate'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'class' => 'control-animate'
                ]
            ])
            ->add('Create', SubmitType::class, [
                'label' => 'Créer une demande',
                'attr' => [
                    'class' => 'control-animate'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact = $form->getData();

             $em = $this->getDoctrine()->getManager();
             $em->persist($contact);
             $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/contact/list", name="list_contact")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository('AppBundle:Contact')->findAll();

        return $this->render('default/list.html.twig', array(
            'contacts' => $contacts
        ));
    }
}