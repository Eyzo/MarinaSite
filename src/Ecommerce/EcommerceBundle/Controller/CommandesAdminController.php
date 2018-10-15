<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CommandesAdminController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


        if ($this->getRequest()->getMethod() == 'POST')
        {
            $post = $this->getRequest()->request->get('trier');

            if ($post == 'DESC' || $post == 'ASC')
            {
                $commandes = $em->getRepository('EcommerceBundle:Commandes')->findAllOrderDate($post);
            }
            else
                {
                throw $this->createNotFoundException('le tri recherché n\'existe pas');
                }
        }
        else
        {
            $post = null;
            $commandes = $em->getRepository('EcommerceBundle:Commandes')->findAllValidate();
        }


        return $this->render('EcommerceBundle:Administration:Commandes/index.html.twig',array('commandes' => $commandes,'post' => $post));
    }

    public function showFactureAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('EcommerceBundle:Commandes')->find($id);

        if (!$facture)
        {
            return $this->redirect($this->generateUrl('commande_admin_index'));
        }

        $this->container->get('setNewFacture')->facture($facture);
    }

}
