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

    private function validate(WidgetOrder $widgetOrder)
    {
        $errors = $this->getContainer()->get('validator')->validate($widgetOrder);
        return [count($errors) == 0, $errors];
    }

    public function testValidation()
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
        $widgetOrder->setNeededBy((new \DateTime())->modify('+8 day'));
        $widgetOrder->setColor($color);
        $widgetOrder->setWidgetType($widgetType);
        list($isValid, $errors) = $this->validate($widgetOrder);
        $this->assertTrue($isValid, (string)$errors);
        $this->widgetOrderManager->save($widgetOrder);
        $this->assertEmpty($widgetOrder->getUser());
    }

    /**
     * Makes sure when valid data is sumitted, it is persisted
     */
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
        $widgetOrder->setNeededBy((new \DateTime())->modify('+8 day'));
        $widgetOrder->setColor($color);
        $widgetOrder->setWidgetType($widgetType);
        list($isValid, $errors) = $this->validate($widgetOrder);
        $this->assertTrue($isValid, (string)$errors);
        $em = $this->getContainer()->get("doctrine")->getManager();
        $countWidgetOrders = $em->getRepository(WidgetOrder::class)->count([]);
        $this->widgetOrderManager->save($widgetOrder);
        $this->assertEquals($countWidgetOrders + 1, $em->getRepository(WidgetOrder::class)->count([]));
        $this->assertEmpty($widgetOrder->getUser());
    }

    /**
     * Make sure when an email is provided, a user is found or created and
     * associated with the created orders
     */
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
        $widgetOrder->setNeededBy((new \DateTime())->modify('+8 day'));
        $widgetOrder->setColor($color);
        $widgetOrder->setWidgetType($widgetType);
        list($isValid, $errors) = $this->validate($widgetOrder);
        $this->assertTrue($isValid, (string)$errors);
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

    /**
     * Make sure when a user is provided, the order is associated to them
     */
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
        $widgetOrder->setNeededBy((new \DateTime())->modify('+8 day'));
        $widgetOrder->setColor($color);
        $widgetOrder->setWidgetType($widgetType);
        list($isValid, $errors) = $this->validate($widgetOrder);
        $this->assertTrue($isValid, (string)$errors);
        $this->widgetOrderManager->save($widgetOrder, $user);
        $this->assertNotEmpty($widgetOrder->getUser());
        $this->assertEquals($user, $widgetOrder->getUser());
    }

    /**
     * Make sure generated message contains the required information
     */
    public function testGenerateConfirmationMessage()
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
        $widgetOrder->setNeededBy((new \DateTime())->modify('+8 day'));
        $widgetOrder->setColor($color);
        $widgetOrder->setWidgetType($widgetType);
        list($isValid, $errors) = $this->validate($widgetOrder);
        $this->assertTrue($isValid, (string)$errors);
        $this->widgetOrderManager->save($widgetOrder);
        $message = $this->widgetOrderManager->generateConfirmationMessage($widgetOrder);
        $this->assertContains('ID '.$widgetOrder->getId(), $message);
        $quantityAndNames = sprintf(
            '%d %s %s',
            $widgetOrder->getQuantity(),
            $widgetOrder->getColor(),
            $widgetOrder->getWidgetType()
        );
        $this->assertContains($quantityAndNames, $message);
        $this->assertContains('by '.$widgetOrder->getNeededBy()->format('D, d M Y'), $message);
    }
}