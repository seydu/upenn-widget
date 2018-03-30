<?php
namespace App\DataFixtures;

use App\Entity\WidgetType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class WidgetTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $source = [
            ['name' => 'Widget'],
            ['name' => 'Widget Pro'],
            ['name' => 'Widget XTreme'],
        ];
        foreach ($source as $data) {
            $item = new WidgetType();
            $item->setName($data['name']);
            $manager->persist($item);
        }
        $manager->flush();
    }
}