<?php
namespace App\Tests\DataFixtures;

use App\Entity\WidgetOrder;
use App\Entity\WidgetType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class WidgetOrderData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $source = [

        ];
        foreach ($source as $data) {
            $record = new WidgetOrder();
            $manager->persist($record);
        }
        $manager->flush();
    }
}