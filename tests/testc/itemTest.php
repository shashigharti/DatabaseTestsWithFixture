<?php
require_once ("LoadUserData.php");
use Doctrine\Common\DataFixtures\Loader;
use MyDataFixtures\LoadUserData;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class ItemTest extends PHPUnit_Framework_TestCase
{

    protected $CI;

    protected $itemID;

    function setUp()
    {
        $this->CI = & get_instance();
        $this->CI->load->model('itemsModel');
        $em = $this->CI->doctrine->em;
        
        $loader = new Loader();
        $this->itemID = $loader->addFixture(new MyDataFixtures\LoadUserData());
        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures(), false);
    }

    function testInsert()
    {
        $expecteditem = new Entities\Items();
        $expecteditem->setItemId(151);
        $expecteditem->setModelNumber('dell123');
        $expecteditem->setName('Dell');
        $expecteditem->setReorderLevel(20);
        
        $metadata = $this->CI->doctrine->em->getClassMetaData(get_class($expecteditem));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        
        $this->CI->itemsModel->insertItem($expecteditem);
        
        $itemID = $expecteditem->getItemId();
        echo "ItemID:" . $itemID;
        $resultitem = $this->CI->doctrine->em->getRepository('Entities\Items')->findOneBy(array(
            'itemId' => $itemID
        ));
        
        $this->assertEquals($expecteditem->__toString(), $resultitem->__toString());
    }

    function testEdit()
    {
        $expectedItem = $this->CI->doctrine->em->getRepository('Entities\Items')->findOneBy(array(
            'itemId' => 150
        ));
        
        $expectedItem->setModelNumber('efgh');
        
        $this->CI->itemsModel->insertItem($expectedItem);
        
        $resultItem = $this->CI->doctrine->em->getRepository('Entities\Items')->findOneBy(array(
            'itemId' => 150
        ));
        
        $this->assertEquals($expectedItem->__toString(), $resultItem->__toString());
    }

    function testDelete()
    {
        $this->CI->itemsModel->delete(150);
        $resultitem = $this->CI->doctrine->em->getRepository('Entities\Items')->findOneBy(array(
            'itemId' =>  150
        ));
        
        $this->assertNull($resultitem);
    } 
}