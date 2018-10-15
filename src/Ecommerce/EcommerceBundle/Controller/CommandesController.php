<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Ecommerce\EcommerceBundle\Entity\Produits;
use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Ecommerce\EcommerceBundle\GoogleMap\Distance;


class CommandesController extends Controller
{
    const ADRESSE = '76 rue saint pierre';
    const CP = '62630';
    const VILLE = 'etaples';

    const DISTANCE_MAX = 30;

    //Data de la commande
    public function facture()
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $generator = $this->container->get('security.secure_random');
        $adresse = $session->get('adresse');
        $panier = $session->get('panier');
        $reception = $session->get('reception-type')['type'];
        $commande = array();
        $totalHT = 0;

        $facturation = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['facturation']);
        $livraison = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['livraison']);
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($panier));

        foreach ($produits as $produit)
        {
            $prix = ($produit->getPrix() * ($panier[$produit->getId()]['poids'] * 1 /1000));
            $prixTotale = $prix * $panier[$produit->getId()]['quantité'];
            $totalHT += $prixTotale;

            $commande['produit'][$produit->getId()] = array(
                'reference'=> $produit->getNom(),
                'quantite'=> $panier[$produit->getId()]['quantité'],
                'poids'=>$panier[$produit->getId()]['poids'],
                'prix'=>round($prix,2,PHP_ROUND_HALF_DOWN),
                'image'=>$produit->getImage(),
                'prixTotale'=> round($prixTotale,2,PHP_ROUND_HALF_DOWN)
            );
        }

        $commande['livraison'] = array(
            'nom'=>$livraison->getNom(),
            'prenom'=>$livraison->getPrenom(),
            'telephone'=>$livraison->getTelephone(),
            'adresse'=>$livraison->getAdresse(),
            'ville'=>$livraison->getVille(),
            'cp'=>$livraison->getCp(),
            'pays'=>$livraison->getPays(),
            'complement'=>$livraison->getComplement()
            );

        $commande['facturation'] = array(
            'nom'=>$facturation->getNom(),
            'prenom'=>$facturation->getPrenom(),
            'telephone'=>$facturation->getTelephone(),
            'adresse'=>$facturation->getAdresse(),
            'ville'=>$facturation->getVille(),
            'cp'=>$facturation->getCp(),
            'pays'=>$facturation->getPays(),
            'complement'=>$facturation->getComplement()
            );

        $commande['prixHT'] = round($totalHT,2,PHP_ROUND_HALF_DOWN);
        $commande['token'] = bin2hex($generator->nextBytes(20));

        $commande['reception'] = $reception;

        if ($reception == 'reservation') 
        {
            $commande['type'] = 'reservation';
            $commande['prixlivraison'] = round(($commande['prixHT'] * 0.1),PHP_ROUND_HALF_DOWN);
            $commande['Totale'] = $commande['prixHT'] - $commande['prixlivraison'];
        }
        
        if ($reception == 'livraison') 
        {

            $adresse1 = new Distance(self::ADRESSE,self::CP,self::VILLE);
            $adresse2 = new Distance($livraison->getAdresse(),$livraison->getCp(),$livraison->getVille());

            if ($adresse2->valideAdresse()) 
            {
                if($adresse1->distanceCheck($adresse2) <= self::DISTANCE_MAX) 
                {
                    $commande['type'] = 'livraison maison';
                    $commande['prixlivraison'] = 5;
                    $commande['Totale'] = $commande['prixHT'] + $commande['prixlivraison'];  
                }
                else
                {
                    $commande['type'] = 'chronofresh';
                    $commande['prixlivraison'] = 18;
                    $commande['Totale'] = $commande['prixHT'] + $commande['prixlivraison'];
                }

            }
            else
            {
                $commande['type'] = 'chronofresh';
                $commande['prixlivraison'] = 18;
                $commande['Totale'] = $commande['prixHT'] + $commande['prixlivraison'];
            }
        }

        return $commande;
    }

    //mise en place de la commande
    public function prepareCommandeAction()
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        if (!$session->has('commande')) 
        {
            $commande = new Commandes();
        }
        else
        {
            $commande = $em->getRepository('EcommerceBundle:Commandes')->find($session->get('commande'));
        }

        $commande->setDate(new \DateTime());
        $commande->setUtilisateur($this->container->get('security.context')->getToken()->getUser());
        $commande->setValider(0);
        $commande->setReference(0);
        $commande->setCommande($this->facture());

        if (!$session->has('commande')) 
        {
            $em->persist($commande);
            $session->set('commande',$commande);
        }

        $em->flush();

        return new Response($commande->getId());
    }

    /*
    * cette methode remplace l'api banque
    */
    public function validationCommandeAction($id)
    {
    
       \Stripe\Stripe::setApiKey("sk_test_flqOo6x1iR1OwuevXv1RfUYI");

       
        $token = $this->getRequest()->request->get('stripeToken');

        $amount = floatval($this->getRequest()->request->get('total')) * 100;

        $email = $this->getRequest()->request->get('email');

                // Create a Customer:
        $customer = \Stripe\Customer::create([
            'source' => $token,
            'email' => $email,
        ]);

        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'eur',
            'description' => 'Achat de fromage',
            'customer' => $customer->id,
        ]);
    
         
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($id);
        
        if (!$commande || $commande->getValider() == 1) 
        {
          throw $this->createNotFoundException('La commande est invalide');
        }

        $commande->setValider(1);
        $commande->setReference($this->container->get('setNewReference')->reference());//service
        $em->flush();
        
        $session = $this->getRequest()->getSession();
        $session->remove('adresse');
        $session->remove('panier');
        $session->remove('commande');

        $session->getFlashBag()->add('success','Votre commande a bien était validée avec succés');

        //ici le mail de validation
        $message = \Swift_Message::newInstance()
            ->setSubject('Validation de votre commande')
            ->setFrom(array('duhameltonysmtp@gmail.com' => 'FromagerieChristophe'))
            ->setTo($commande->getUtilisateur()->getEmailCanonical())
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody($this->renderView('EcommerceBundle:Default:Mail/validationMail.html.twig',array('utilisateur' => $commande->getUtilisateur())));

        $this->get('mailer')->send($message);

        return $this->redirect($this->generateUrl('facture'));
        
        
    }

   

}