<?php
namespace App\Tests\DataFixtures;

use App\Entity\WidgetOrder;
use App\Entity\WidgetType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class WidgetOrderData extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return array(
            UserData::class,
            ColorData::class,
            WidgetTypeData::class,
            WidgetOrderStatusData::class,
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
        foreach ($source as $number => $data) {
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
             * @var Color
             */
            $status = $this->getReference('WidgetOrderStatus-'.$data['Status']);

            $record->setQuantity(rand(1, 100));
            $record->setNeededBy((new \DateTime())->modify(sprintf('+%d day', rand(8, 30))));
            $record->setColor($color);
            $record->setWidgetType($widgetType);
            $record->setStatus($status);
            $this->setReference("WidgetOrder-$number", $record);
            $manager->persist($record);
        }
        $manager->flush();
    }
}