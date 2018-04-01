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
            ['name' => 'Red', 'code' => 'red'],
            ['name' => 'Blue', 'code' => 'blue'],
            ['name' => 'Yellow', 'code' => 'yellow'],
        ];
        foreach ($source as $data) {
            $record = new Color();
            $record->setName($data['name']);
            $manager->persist($record);
            $this->setReference("Color-{$data['code']}", $record);
        }
        $manager->flush();
    }
}