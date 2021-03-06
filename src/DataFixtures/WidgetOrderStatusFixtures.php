<?php
namespace App\DataFixtures;

use App\Entity\WidgetOrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class WidgetOrderStatusFixtures extends Fixture
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
            $this->setReference("WidgetOrderStatus-{$data['code']}", $item);
            $manager->persist($item);
        }
        $manager->flush();
    }
}