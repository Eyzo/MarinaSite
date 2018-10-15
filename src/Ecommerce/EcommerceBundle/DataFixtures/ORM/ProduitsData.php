<?php
 
namespace Ecommerce\EcommerceBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ecommerce\EcommerceBundle\Entity\Produits;
use Doctrine\Common\DataFixtures\AbstractFixture;

class ProduitsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
          
           $produits = new Produits();
           $produits->setDescription('Tomme de fromage parsemé de fleurs');
           $produits->setDisponible('1');
           $produits->setNom('Tomme aux fleurs sauvage');
           $produits->setPrix('34.90');
           $produits->setImage($this->getReference('media1'));
           $produits->setCategorie($this->getReference('categorie1'));
           $produits->setPoids('6000');

           $manager->persist($produits);

           $produits2 = new Produits();
           $produits2->setDescription('Tomme de montagne d\'origine alsacienne');
           $produits2->setDisponible('1');
           $produits2->setNom('Tomme de montagne');
           $produits2->setPrix('29.90');
           $produits2->setImage($this->getReference('media2'));
           $produits2->setCategorie($this->getReference('categorie2'));
           $produits2->setPoids('8250');

           $manager->persist($produits2);

           $manager->flush();

    }

    public function getOrder()
    {
        return 4;
    }
}