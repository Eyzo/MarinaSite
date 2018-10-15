<?php
 
namespace Utilisateurs\UtilisateursBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;

class UtilisateursAdressesData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        

        $utilisateurs_adresses1 = new UtilisateursAdresses();
        $utilisateurs_adresses1->setNom('Duhamel');
        $utilisateurs_adresses1->setPrenom('Tony');
        $utilisateurs_adresses1->setTelephone('06.45.45.74.83');
        $utilisateurs_adresses1->setAdresse('rue de moi même');
        $utilisateurs_adresses1->setCp('62600');
        $utilisateurs_adresses1->setPays('France');
        $utilisateurs_adresses1->setVille('Berck');
        $utilisateurs_adresses1->setComplement('face a la mère');
        $utilisateurs_adresses1->setUtilisateur($this->getReference('utilisateurs1'));

        $manager->persist($utilisateurs_adresses1);

        $utilisateurs_adresses2 = new UtilisateursAdresses();
        $utilisateurs_adresses2->setNom('Duhamel');
        $utilisateurs_adresses2->setPrenom('Marina');
        $utilisateurs_adresses2->setTelephone('06.25.23.89.84');
        $utilisateurs_adresses2->setAdresse('rue jean miche');
        $utilisateurs_adresses2->setCp('62630');
        $utilisateurs_adresses2->setPays('France');
        $utilisateurs_adresses2->setVille('Etaples');
        $utilisateurs_adresses2->setComplement('face contre terre');
        $utilisateurs_adresses2->setUtilisateur($this->getReference('utilisateurs1'));

        $manager->persist($utilisateurs_adresses2);

        $manager->flush();


    }

    public function getOrder()
    {
        return 6;
    }
}