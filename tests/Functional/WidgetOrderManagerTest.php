<?php

namespace App\Tests\Functional;

use App\Entity\Color;
use App\Entity\User;
use App\Entity\WidgetOrder;
use App\Entity\WidgetType;
use App\Manager\WidgetOrderManager;
use App\Tests\DataFixtures\ColorData;
use App\Tests\DataFixtures\UserData;
use App\Tests\DataFixtures\WidgetTypeData;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class WidgetOrderManagerTest extends WebTestCase
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var ReferenceRepository
     */
    private $fixtures;

    /**
     * @var WidgetOrderManager
     */
    private $widgetOrderManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->om = $this->getContainer()->get("doctrine")->getManager();
        $this->widgetOrderManager = $this->getContainer()->get(WidgetOrderManager::class);
        $this->fixtures = $this->loadFixtures([
            UserData::class,
            ColorData::class,
            WidgetTypeData::class

        ])->getReferenceRepository();
    }

    public function testSave()
    {
        /**
         * @var Color
         */
        $color = $this->fixtures->getReference('Color-red');
        /**
         * @var WidgetType
         */
        $widgetType = $this->fixtures->getReference('WidgetType-widget');

        $widgetOrder = new WidgetOrder();
        $widgetOrder->setQuantity(3);
        $widgetOrder->setNeededBy(new \DateTime());
        $widgetOrder->setColor($color);
        $widgetOrder->setWidgetType($widgetType);
        $this->widgetOrderManager->save($widgetOrder);
        $this->assertEmpty($widgetOrder->getUser());
    }


    public function testSaveWithUserEmail()
    {
        /**
         * @var Color
         */
        $color = $this->fixtures->getReference('Color-red');
        /**
         * @var WidgetType
         */
        $widgetType = $this->fixtures->getReference('WidgetType-widget');

        $widgetOrder = new WidgetOrder();
        $widgetOrder->setQuantity(3);
        $widgetOrder->setNeededBy(new \DateTime());
        $widgetOrder->setColor($color);
        $widgetOrder->setWidgetType($widgetType);
        $this->widgetOrderManager->save($widgetOrder, 'email@email.com');
        $this->assertNotEmpty($widgetOrder->getUser());
        $this->assertEquals('email@email.com', $widgetOrder->getUser()->getEmail());
        $this->assertEquals('email@email.com', $widgetOrder->getUser()->getUsername());
        $readUser = $this->om->getRepository(User::class)
            ->findOneBy(['email' => $widgetOrder->getUser()->getEmail()]);
        $this->assertNotEmpty($readUser);
        $this->assertInstanceOf(User::class, $readUser);
        $this->assertEquals($readUser->getId(), $widgetOrder->getUser()->getId());
    }

    public function testSaveWithUser()
    {
        /**
         * @var USer
         */
        $user = $this->fixtures->getReference('User-superuser');
        /**
         * @var Color
         */
        $color = $this->fixtures->getReference('Color-red');
        /**
         * @var WidgetType
         */
        $widgetType = $this->fixtures->getReference('WidgetType-widget');

        $widgetOrder = new WidgetOrder();
        $widgetOrder->setQuantity(3);
        $widgetOrder->setNeededBy(new \DateTime());
        $widgetOrder->setColor($color);
        $widgetOrder->setWidgetType($widgetType);
        $this->widgetOrderManager->save($widgetOrder, $user);
        $this->assertNotEmpty($widgetOrder->getUser());
        $this->assertEquals($user, $widgetOrder->getUser());
    }
}