<?php
namespace Ecommerce\EcommerceBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;

class ListePoidsExtension extends \Twig_Extension
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('listePoids',[$this,'listePoids'])
        ];
    }

    public function listePoids($id)
    {
        $produit = $this->em->getRepository('EcommerceBundle:Produits')->find($id);

        $poids = $produit->getPoids();

        $listePoids = [];

        if ($poids > 250)
        {
            $nombreBoucle = floor($poids / 250);

            for ($i=1;$i <= $nombreBoucle;$i++)
            {

                $poidsCalcul = $i * 250;

                if ($poidsCalcul <= 2000)
                {
                    $listePoids[] = $poidsCalcul;
                }
            }
        }

        $listePoids[] = $poids;

        return $listePoids;
    }


    public function getName()
    {
        return 'liste_poids_extension';
    }


}