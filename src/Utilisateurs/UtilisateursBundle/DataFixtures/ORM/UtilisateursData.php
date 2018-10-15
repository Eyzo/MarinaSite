<?php
 
namespace Utilisateurs\UtilisateursBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Utilisateurs\UtilisateursBundle\Entity\Utilisateurs;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;

class UtilisateursData extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface,FixtureInterface
{


    private $container;

   public function setContainer(ContainerInterface $container = null)
   {
    $this->container = $container;
   }

    public function load(ObjectManager $manager)
    {
        

        $utilisateurs1 = new Utilisateurs();
        $utilisateurs1->setUsername('Tony');
        $utilisateurs1->setEmail('duhameltonyeyzo@gmail.com');
        $utilisateurs1->setEnabled(1);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($utilisateurs1);
        $utilisateurs1->setPassword($encoder->encodePassword('test', $utilisateurs1->getSalt()));

        $manager->persist($utilisateurs1);
        $manager->flush();

        $this->addReference('utilisateurs1',$utilisateurs1);

    }

    public function getOrder()
    {
        return 5;
    }
}