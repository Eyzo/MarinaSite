<?php
 
namespace Ecommerce\EcommerceBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ecommerce\EcommerceBundle\Entity\Categories;
use Doctrine\Common\DataFixtures\AbstractFixture;

class CategoriesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
           $categorie1 = new Categories();
           $categorie1->setNom('Fromage de montagne');
           $categorie1->setImage($this->getReference('media1'));

           $manager->persist($categorie1);

           $categorie2 = new Categories();
           $categorie2->setNom('Fromage Autrichien');
           $categorie2->setImage($this->getReference('media2'));

           $manager->persist($categorie2);

           $manager->flush();

           $this->addReference('categorie1',$categorie1);
           $this->addReference('categorie2',$categorie2);


    }

    public function getOrder()
    {
        return 2;
    }
}