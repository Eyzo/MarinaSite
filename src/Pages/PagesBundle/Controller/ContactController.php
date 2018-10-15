<?php

namespace Pages\PagesBundle\Controller;

use Pages\PagesBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ContactController extends Controller
{

    public function indexAction()
    {
        $form = $this->createForm(new ContactType(),null,array(
            'action' => $this->generateUrl('contactTraitement'),'method' => 'POST'
        ));

        return $this->render('PagesBundle:Default:pages/layout/contact.html.twig',array('form' => $form->createView()));
    }

    public function FormTraitementAction(Request $request)
    {
        if ($request->getMethod() == 'POST')
        {
            $form = $this->createForm(new ContactType());

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $nom = $form->get('nom')->getData();
                $email = $form->get('email')->getData();
                $contenu = $form->get('contenu')->getData();

                $html = $this->renderView('PagesBundle:Default:Email/contactMail.html.twig',array('nom' => $nom,'content' => $contenu,'email' => $email));

                $message = new \Swift_Message();

                $message->setSubject('Formulaire de contacte site Fromagerie Christophe')
                    ->setFrom($email)
                    ->setTo('duhameltonysmtp@gmail.com')
                    ->setCharset('utf-8')
                    ->setContentType('text/html')
                    ->setBody($html);

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('success','votre message a bien été envoyé merci');

                return $this->redirect($this->generateUrl('contactPages'));
            }
        }
        else
            {
                throw $this->createNotFoundException('Erreur d\'envoie du formulaire');
            }
    }

}
