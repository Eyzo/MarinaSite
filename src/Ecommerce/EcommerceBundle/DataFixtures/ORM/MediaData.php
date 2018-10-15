<?php 
namespace Ecommerce\EcommerceBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ecommerce\EcommerceBundle\Entity\Media;
use Doctrine\Common\DataFixtures\AbstractFixture;

class MediaData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
            $media1 = new Media();
            $media1->setPath('https://aubonfromage.re/wp-content/uploads/2016/03/Tomme-aux-fleurs-sauvages-2-e1460005909350.jpg');
            $media1->setAlt('Tomme aux fleurs sauvage');
            $manager->persist($media1);
        
            $media2 = new Media();
            $media2->setPath('https://www.coop-de-yenne.fr/147-tm_thickbox_default/tomme-de-montagne-au-lait-cru-25.jpg');
            $media2->setAlt('Tomme de montagne');
            $manager->persist($media2);


        $manager->flush();

        $this->addReference('media1',$media1);
        $this->addReference('media2',$media2);
    }

    public function getOrder()
    {
        return 1;
    }
}