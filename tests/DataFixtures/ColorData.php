<?php
namespace App\Tests\DataFixtures;

use App\Entity\Color;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class ColorData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $source = [
            ['name' => 'Red', 'code' => 'red'],
            ['name' => 'Blue', 'code' => 'blue'],
            ['name' => 'Yellow', 'code' => 'yellow'],
        ];
        foreach ($source as $position => $data) {
            $record = new Color();
            $record->setName($data['name']);
            $record->setWeight($data['weight'] ?? $position);
            $manager->persist($record);
            $this->setReference("Color-{$data['code']}", $record);
        }
        $manager->flush();
    }
}