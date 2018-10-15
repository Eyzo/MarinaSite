<?php
 
namespace Ecommerce\EcommerceBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;

class CommandesData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        

        $commande1 = new Commandes();
        $commande1->setValider('1');
        $commande1->setDate(new \DateTime());
        $commande1->setReference('1');
        $commande1->setCommande(array(
            '0'=>array('1'=>'2'),
            '1'=>array('2'=>'1'),
            '2'=>array('4'=>'5')
        ));
        $commande1->setUtilisateur($this->getReference('utilisateurs1'));

        $manager->persist($commande1);

        $commande2 = new Commandes();
        $commande2->setValider('1');
        $commande2->setDate(new \DateTime());
        $commande2->setReference('2');
        $commande2->setCommande(array(
            '0'=>array('6'=>'7'),
            '1'=>array('9'=>'8')
        ));
        $commande2->setUtilisateur($this->getReference('utilisateurs1'));

        $manager->persist($commande2);

        $manager->flush();


    }

    public function getOrder()
    {
        return 7;
    }
}