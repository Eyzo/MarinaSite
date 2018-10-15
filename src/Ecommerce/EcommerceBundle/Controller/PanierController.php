<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Ecommerce\EcommerceBundle\Form\UtilisateursAdressesType;
use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;

class PanierController extends Controller
{

    //Menu panier

    public function menuAction()
    {

    $session = $this->getRequest()->getSession();

    if (!$session->get('panier')) 
    {
        $article = 0;
    }
    else
    {
        $article = count($session->get('panier'));
    }

     return $this->render('EcommerceBundle:Default:panier/modulesUsed/PanierMenu.html.twig',compact('article'));   
    }

    //Page panier

    public function panierAction()
    {
        $session = $this->getRequest()->getSession();

        if (!$session->has('panier')) 
        {
            $session->set('panier',array());
        }

        $panier = $session->get('panier');

        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));

        return $this->render('EcommerceBundle:Default:panier/layout/panier.html.twig',compact('produits','panier'));
    }

    //Page livraison

    public function livraisonAction()
    {
        
        $utilisateur = $this->container->get('security.context')->getToken()->getUser();
        $entity = new UtilisateursAdresses();
        $form = $this->createForm(new UtilisateursAdressesType(), $entity);
        

        if ($this->get('request')->getMethod() == 'POST')
        {
            $form->handleRequest($this->getRequest());
            
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity->setUtilisateur($utilisateur);
                $em->persist($entity);
                $em->flush();
                
                return $this->redirect($this->generateUrl('livraison'));
            }
            
        }

        $form = $form->createView();
        return $this->render('EcommerceBundle:Default:panier/layout/livraison.html.twig',compact('form','utilisateur'));
    }

    //supprimer une adresse de livraison

    public function livraisonSupprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $adresse = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($id);
        
        if ($this->get('security.context')->getToken()->getUser() != $adresse->getUtilisateur() || !$adresse) 
        {
            throw $this->createNotFoundException('suppression demandée invalide');
        }

        $em->remove($adresse);
        $em->flush();  

        return $this->redirect($this->generateUrl('livraison'));
    }

    //met en place le tableau d'adresses de facturation et livraison dans la session
    public function setLivraisonOnSession()
    {
        $session = $this->getRequest()->getSession();

        if (!$session->has('adresse')) 
        {
            $session->set('adresse',array());
        }

        $adresse = $session->get('adresse');

        if ($this->getRequest()->request->get('livraison') != null && $this->getRequest()->request->get('facturation') != null) 
        {
            $adresse['livraison'] = $this->getRequest()->request->get('livraison');
            $adresse['facturation'] = $this->getRequest()->request->get('facturation');
        } 
        else 
        {
            return $this->redirect($this->generateUrl('livraisonchoix'));
        }

        $session->set('adresse',$adresse);
        return $this->redirect($this->generateUrl('livraisonchoix'));
    }

    //Page livraison choix 
    public function livraisonChoixAction()
    {
        if ($this->getRequest()->getMethod() == 'POST')
        {
            $this->setLivraisonOnSession();
        }

    	return $this->render('EcommerceBundle:Default:panier/layout/livraison-choix.html.twig');
    }

    
    //insere le type de reception en session
    public function setReceptionTypeOnSession()
    {
        $session = $this->getRequest()->getSession();

        if (!$session->has('reception-type')) 
        {
            $session->set('reception-type',array());
        }

        $receptiontype = $session->get('reception-type');

        if ($this->getRequest()->request->get('reservation_x') != null)
        {
            $reception['type'] = 'reservation';
        }
        if ($this->getRequest()->request->get('livraison_x') != null) 
        {
            $reception['type'] = 'livraison';
        }

        $session->set('reception-type',$reception);

        return $this->redirect($this->generateUrl('validation'));

    }


    // Page Validation
    public function validationAction()
    {
        if ($this->getRequest()->getMethod() == 'POST') 
        {
            $this->setReceptionTypeOnSession();
        }

        $em = $this->getDoctrine()->getManager();

        $response = $this->forward('EcommerceBundle:Commandes:prepareCommande');

        $user = $this->getUser();

        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($response->getContent());

        return $this->render('EcommerceBundle:Default:panier/layout/validation.html.twig',compact('commande','user'));
        
    }


    //Ajout d'article dans le panier

    public function ajouterAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();

        if (!$session->has('panier')) 
        {
            $session->set('panier',array());
        }

        $panier = $session->get('panier');

        //$panier[ID DU PRODUIT] => QUANTITE       
        
        if (array_key_exists($id,$panier))
        {
             if ($this->getRequest()->query->get('qte') != null) 
            {
                $panier[$id] = ['quantité' => intval($this->getRequest()->query->get('qte')),'poids' => intval($this->getRequest()->query->get('poids'))];

                $session->getFlashBag()->add('success','Quantité bien modifié'); 
            }
        }
        else
        {
            if ($this->getRequest()->query->get('qte') != null) 
            {
                $panier[$id] = ['quantité' => intval($this->getRequest()->query->get('qte')),'poids' => intval($this->getRequest()->query->get('poids'))];

                $session->getFlashBag()->add('success','Article bien ajouté au panier avec la quantité demandée'); 
            }
            else
            {
              $produit = $em->getRepository('EcommerceBundle:Produits')->find($id);
              if (!$produit)
              {
                  throw $this->createNotFoundException('Le produit n\'existe pas');
              }
              else
              {
                  if ($produit->getPoids() < 250)
                  {
                      $panier[$id] = ['quantité' => 1 ,'poids' => $produit->getPoids() ];
                  }
                  else
                  {
                      $panier[$id] = ['quantité' => 1 ,'poids' => 250 ];
                  }

                  $session->getFlashBag()->add('success','Article bien ajouté au panier');
              }
            }
        }

        $session->set('panier',$panier);

        return $this->redirect($this->generateUrl('panier'));
    }

    //Suppression d'article dans le panier

    public function supprimerAction($id)
    {
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier');

        if (array_key_exists($id,$panier))
        {
         unset($panier[$id]);
         $session->set('panier',$panier);
         $session->getFlashBag()->add('success','Article supprimé avec succés');
        }

        return $this->redirect($this->generateUrl('panier'));
    }
}