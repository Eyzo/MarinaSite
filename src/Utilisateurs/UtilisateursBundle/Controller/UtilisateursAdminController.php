<?php

namespace Utilisateurs\UtilisateursBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UtilisateursAdminController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('UtilisateursBundle:Utilisateurs')->findAll();

        return $this->render('UtilisateursBundle:Administration:index.html.twig',array('entities' => $entities));
    }

    public function utilisateurAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity =$em->getRepository('UtilisateursBundle:Utilisateurs')->find($id);

        if ($this->container->get('request')->get('_route') == 'admin_utilisateurs_adresses') 
        {
            return $this->render('UtilisateursBundle:Administration:adresses.html.twig',array('entity' => $entity));
        }
        elseif ($this->container->get('request')->get('_route') == 'admin_utilisateurs_factures') 
        {
            return $this->render('UtilisateursBundle:Administration:factures.html.twig',array('entity' => $entity));
        }
        else
        {
            throw $this->createNotFoundException('La page recherché n\'existe pas');
        }

        
    }


}
