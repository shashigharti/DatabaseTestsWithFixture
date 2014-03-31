<?php
namespace MyDataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Entities;

class LoadUserData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $this->CI = & get_instance();
        $this->CI->load->model('itemsModel');
        $em = $this->CI->doctrine->em;
        
        $item = new Entities\Items();
        $item->setItemId(150);
        $item->setModelNumber('dell123');
        $item->setName('Dell');
        $item->setReorderLevel(20);
        
        /* forces Doctrine to insert the specified value to the autoincrement field(item_id) */
        $metadata = $this->CI->doctrine->em->getClassMetaData(get_class($item));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        
        $this->CI->doctrine->em->persist($item);
        $this->CI->doctrine->em->flush(); 
    }
}