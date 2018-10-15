<?php
 
namespace Pages\PagesBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Pages\PagesBundle\Entity\Pages;
use Doctrine\Common\DataFixtures\AbstractFixture;

class PagesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
           $pages1 = new Pages();
           $pages1->setTitre('CGV');
           $pages1->setContenu('<div><h2>Ici c\'est la page des CGV</h2><p>bonjour je suis le contenu de la page des CGV<p></div>');

           $manager->persist($pages1);

           $pages2 = new Pages();
           $pages2->setTitre('Mentions légales');
           $pages2->setContenu('<div><h2>Ici c\'est la page des mentions légales</h2><p>bonjour je suis le contenu de la page des mentions légales<p></div>');

           $manager->persist($pages2);

           $manager->flush();


    }

    public function getOrder()
    {
        return 8;
    }
}