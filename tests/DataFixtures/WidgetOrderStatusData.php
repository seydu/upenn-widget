<?php
namespace App\Tests\DataFixtures;

use App\Entity\WidgetOrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class WidgetOrderStatusData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $source = [
            ['name' => 'Placed', 'code' => 'PLACED'],
            ['name' => 'Shipped', 'code' => 'SHIPPED'],
            ['name' => 'Delivered', 'code' => 'DELIVERED'],
        ];
        foreach ($source as $data) {
            $item = new WidgetOrderStatus();
            $item->setName($data['name']);
            $item->setCode($data['code']);
            $manager->persist($item);
        }
        $manager->flush();
    }
}