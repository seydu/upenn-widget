<?php

namespace App\Tests\Functional;

use App\Entity\Color;
use App\Entity\WidgetOrder;
use App\Entity\WidgetType;
use App\Tests\DataFixtures\ColorData;
use App\Tests\DataFixtures\UserData;
use App\Tests\DataFixtures\WidgetOrderData;
use App\Tests\DataFixtures\WidgetOrderStatusData;
use App\Tests\DataFixtures\WidgetTypeData;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class WidgetOrderControllerTest extends WebTestCase
{

    /**
     * @return int
     */
    private function getWidgetOrdersCount()
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getContainer()->get("doctrine")->getManager();
        return $em->getRepository(WidgetOrder::class)->count([]);

    }

    /**
     * Make sure an order will be added when submitted data is correct,
     * user will be redirected and a confirmation message is displayed
     */
    public function testCreateOrder()
    {
        $client = $this->createClient();
        $client->enableProfiler();

        $fixtures = $this->loadFixtures([
            UserData::class,
            ColorData::class,
            WidgetTypeData::class,
            WidgetOrderStatusData::class,
        ])->getReferenceRepository();
        $widgetOrdersCount = $this->getWidgetOrdersCount();
        $this->assertEquals(0, $widgetOrdersCount);

        $url = $this->getContainer()->get('router')->generate('app_widgetorder_order');
        //Go to widget order page
        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Make sure the page has a form
        $forms = $crawler->filter('form');
        $this->assertEquals(1, $forms->count());
        $selectButton = $crawler->selectButton('Send');
        $this->assertNotEmpty($selectButton);

        /**
         * @var Color $color
         */
        $color = $fixtures->getReference('Color-red');
        /**
         * @var WidgetType $widgetType
         */
        $widgetType = $fixtures->getReference('WidgetType-widget');

        $formData = [
            'quantity' => 3,
            'Color' => $color->getId(),
            'neededBy' => (new \DateTime())->modify('+8 day')->format('Y-m-d'),
            'WidgetType' => $widgetType->getId(),
            'user_email' => 'example@example.com'
        ];
        $client->submit($selectButton->form(), ['create_order_form' => $formData]);
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertEquals($widgetOrdersCount+1, $this->getWidgetOrdersCount());
        //Make sure the email has been sent
        $profiler = $client->getProfile();
        $mailCollector = $profiler->getCollector('swiftmailer');

        // checks that an email was sent
        $this->assertSame(1, $mailCollector->getMessageCount());

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Check for flash message (its formatting has been tested elsewhere)
        $this->assertGreaterThan(0, $crawler->filter('div.flash-notice')->count());
    }

    /**
     * Constraints are defined in entity class and have been tested.
     * This test guarantees that the validator is called by the form
     * and that it has been configured correctly.
     *
     * It sets correct values for all fields but the date it is needed
     */
    public function testFormValidation()
    {
        $fixtures = $this->loadFixtures([
            UserData::class,
            ColorData::class,
            WidgetTypeData::class
        ])->getReferenceRepository();
        $widgetOrdersCount = $this->getWidgetOrdersCount();
        $this->assertEquals(0, $widgetOrdersCount);

        $client = $this->createClient();
        $url = $this->getContainer()->get('router')->generate('app_widgetorder_order');
        //Go to index page
        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Make sure the page has a form
        $forms = $crawler->filter('form');
        $this->assertEquals(1, $forms->count());
        $selectButton = $crawler->selectButton('Send');
        $this->assertNotEmpty($selectButton);

        /**
         * @var Color $color
         */
        $color = $fixtures->getReference('Color-red');
        /**
         * @var WidgetType $widgetType
         */
        $widgetType = $fixtures->getReference('WidgetType-widget');

        $formData = [
            'quantity' => 3,
            'Color' => $color->getId(),
            'neededBy' => (new \DateTime())->modify('+4 day')->format('Y-m-d'),
            'WidgetType' => $widgetType->getId(),
        ];
        $client->submit($selectButton->form(), ['create_order_form' => $formData]);
        //Only the invalid date will cause submission failure
        $this->assertFalse($client->getResponse()->isRedirect());
    }

    /**
     * Make sure an order will be added when submitted data is correct,
     * user will be redirected and a confirmation message is displayed
     */
    public function testOrderDetails()
    {
        $client = $this->createClient();

        $fixtures = $this->loadFixtures([
            UserData::class,
            ColorData::class,
            WidgetTypeData::class,
            WidgetOrderStatusData::class,
            WidgetOrderData::class,
        ])->getReferenceRepository();
        /**
         * @var WidgetOrder $widgetOrder
         */
        $widgetOrder = $fixtures->getReference('WidgetOrder-0');

        $url = $this->getContainer()
            ->get('router')
            ->generate('app_widgetorder_orderdetails', ['id' => $widgetOrder->getId()]);
        //Go to widget order page
        $crawler = $client->request('GET', $url);

        //Check for presence of the details ul
        $containerUl = $crawler->filter('ul.order-details');
        $this->assertEquals(1, $containerUl->count());

        //Test for the presence of the link to go back
        $backLinks = $crawler->filter('a.back-link');
        $this->assertGreaterThan(0, $backLinks->count());

        $client->click($backLinks->link());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}