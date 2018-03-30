<?php
namespace App\DataFixtures;

use App\Entity\Color;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class ColorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $source = [
            ['name' => 'Red'],
            ['name' => 'Blue'],
            ['name' => 'Yellow'],
        ];
        foreach ($source as $data) {
            $item = new Color();
            $item->setName($data['name']);
            $manager->persist($item);
        }
        $manager->flush();
    }
}