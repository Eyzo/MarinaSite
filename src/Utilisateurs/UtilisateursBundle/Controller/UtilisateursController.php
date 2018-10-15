<?php

namespace Utilisateurs\UtilisateursBundle\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spipu\Html2Pdf\Html2Pdf;

class UtilisateursController extends Controller
{
    public function facturesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('EcommerceBundle:Commandes')->byFacture($this->getUser());


        return $this->render('UtilisateursBundle:Default:layout/facture.html.twig',compact('factures'));
    }

    public function facturePDFAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('EcommerceBundle:Commandes')->findOneBy(array('utilisateur' => $this->getUser(),'valider' => 1,'id' => $id));

        if(!$facture)
        {
           $session = $this->getRequest()->getSession();
           $session->getFlashBag()->add('error','la facture recherché n\'existe pas');
           return $this->redirect($this->generateUrl('facture'));
        }

        $this->container->get('setNewFacture')->facture($facture);

    }


}
