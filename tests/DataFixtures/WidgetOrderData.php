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
             * @var Color
             */
            $status = $this->getReference('WidgetOrderStatus-'.$data['Status']);

            $widgetOrder = new WidgetOrder();
            $widgetOrder->setQuantity(rand(1, 100));
            $widgetOrder->setNeededBy((new \DateTime())->modify(sprintf('+%d day', rand(8, 30))));
            $widgetOrder->setColor($color);
            $widgetOrder->setWidgetType($widgetType);
            $widgetOrder->setStatus($status);
            $manager->persist($record);
        }
        $manager->flush();
    }
}