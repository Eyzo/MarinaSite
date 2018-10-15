<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Form\RechercheType;
use Ecommerce\EcommerceBundle\Entity\Categories;

class ProduitController extends Controller
{
    const MAX_PAGINATION = 6;

    // Page liste des produits avec pagination
    public function produitsAction(Categories $categorie = null)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        if ($categorie != null)
        {
            $pagination = $this->paginationProduit(self::MAX_PAGINATION,'categorie',$categorie);

            $page = $pagination['page'];

            $NbTotalPage = $pagination['NbTotalPage'];
            

            $produits = $em->getRepository('EcommerceBundle:Produits')->paginatedByCategorie($pagination['offset'],$pagination['max'],$categorie);
        }
        else
        {
            $pagination = $this->paginationProduit(self::MAX_PAGINATION);

            $page = $pagination['page'];

            $NbTotalPage = $pagination['NbTotalPage'];

            $produits = $em->getRepository('EcommerceBundle:Produits')->paginated($pagination['offset'],$pagination['max']);
        } 
        if ($session->has('panier'))
        {
            $panier = $session->get('panier');
        }
        else
        {
            $panier = false;
        }
        return $this->render('EcommerceBundle:Default:produits/layout/produit.html.twig',compact('produits','NbTotalPage','page','panier','categorie'));
    }  

    //Fiche perso produit
    public function presentationAction($id)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EcommerceBundle:Produits')->find($id);
        
        if (!$produit)
        { 
            throw $this->createNotFoundException('La page n\'existe pas.');
        }
        if ($session->has('panier'))
        {
            $panier = $session->get('panier');
        }
        else
        {
            $panier = false;
        }
        
        return $this->render('EcommerceBundle:Default:produits/layout/presentation.html.twig',compact('produit','panier'));
        
    }  

    //Formulaire de recherche
    public function rechercheAction()
    {
        $form = $this->createForm(new RechercheType());
        $form = $form->createView();

        return $this->render('EcommerceBundle:Default:recherche/modulesUsed/recherche.html.twig',compact('form'));
    }

    //Traitement de la recherche
    public function rechercheTraitementAction()
    {
        $request = $this->get('request');
        $session = $this->getRequest()->getSession();

        if ($request->getMethod() == 'POST') 
        {

        $form = $this->createForm(new RechercheType());
        $em = $this->getDoctrine()->getManager();

        $form->bind($request);
        
        $recherche = '%'.$form['recherche']->getData().'%';


        $produits = $em->getRepository('EcommerceBundle:Produits')->recherche($recherche);

        if ($session->has('panier')) 
        {
        	$panier = $session->get('panier');
        }
        else
        {
        	$panier = false;
        }

        return $this->render('EcommerceBundle:Default:produits/layout/search.html.twig',compact('produits','panier'));

        }
        else
        {
            throw $this->createNotFoundException('La recherche n\'est pas valide');
        }
    
    }

    //retourne les valeurs max,offset,page,NbTotalPage en fonction du maximum donné

    public function paginationProduit(int $max,string $type = 'normal',$id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $page = intval($this->getRequest()->query->get('p'));

        if (!$page) 
        {
            $page = 1;
        }

        $max = $max;

        $offset = ($page - 1) * $max; 

        if ($type == 'categorie') 
        {
            $total = count($em->getRepository('EcommerceBundle:Produits')->byCategorie($id));
        }
        if($type == 'normal')
        {
            $total = count($em->getRepository('EcommerceBundle:Produits')->findAll(array('disponible = 1')));  
        }
        

        $NbTotalPage = $total / $max;

        $NbTotalPage = intval(ceil($NbTotalPage));

        $pagination['page'] = $page ;

        $pagination['NbTotalPage'] = $NbTotalPage;

        $pagination['offset'] = $offset;

        $pagination['max'] = $max;

        return $pagination;
    }


}
