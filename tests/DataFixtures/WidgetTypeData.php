<?php
namespace App\Tests\DataFixtures;

use App\Entity\WidgetType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class WidgetTypeData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $source = [
            ['name' => 'Widget', 'code' => 'widget'],
            ['name' => 'Widget Pro', 'code' => 'widget-pro'],
            ['name' => 'Widget XTreme', 'code' => 'widget-xtreme'],
        ];
        foreach ($source as $data) {
            $record = new WidgetType();
            $record->setName($data['name']);
            $manager->persist($record);
            $this->setReference("WidgetType-{$data['code']}", $record);
        }
        $manager->flush();
    }
}