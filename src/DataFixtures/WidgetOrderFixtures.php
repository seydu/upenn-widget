<?php
namespace App\DataFixtures;

use App\Entity\Color;
use App\Entity\WidgetOrder;
use App\Entity\WidgetOrderStatus;
use App\Entity\WidgetType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class WidgetOrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return array(
            ColorFixtures::class,
            WidgetTypeFixtures::class,
            WidgetOrderStatusFixtures::class,
        );
    }

    public function load(ObjectManager $manager)
    {
        $source = [
            [
                'Color' => 'red',
                'WidgetType' => 'widget',
                'Status' => 'PLACED'
            ]
        ];
        foreach ($source as $data) {
            $record = new WidgetOrder();
            /**
             * @var Color
             */
            $color = $this->getReference('Color-'.$data['Color']);
            /**
             * @var WidgetType
             */
            $widgetType = $this->getReference('WidgetType-'.$data['WidgetType']);
            /**
             * @var WidgetOrderStatus
             */
            $status = $this->getReference('WidgetOrderStatus-'.$data['Status']);

            $record->setQuantity(rand(1, 100));
            $record->setNeededBy((new \DateTime())->modify(sprintf('+%d day', rand(8, 30))));
            $record->setColor($color);
            $record->setWidgetType($widgetType);
            $record->setStatus($status);
            $manager->persist($record);
        }
        $manager->flush();
    }
}