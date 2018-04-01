<?php

namespace App\Tests\Functional;

use App\Entity\WidgetOrder;
use App\Entity\WidgetOrderStatus;
use App\Tests\DataFixtures\ColorData;
use App\Tests\DataFixtures\UserData;
use App\Tests\DataFixtures\WidgetOrderData;
use App\Tests\DataFixtures\WidgetOrderStatusData;
use App\Tests\DataFixtures\WidgetTypeData;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class WidgetOrderAdminControllerTest extends WebTestCase
{
    /**
     * Make sure an order will be added when submitted data is correct,
     * user will be redirected and a confirmation message is displayed
     */
    public function testIndexEmptyList()
    {
        $client = $this->createClient();

        $this->loadFixtures([]);

        $url = $this->getContainer()->get('router')->generate('app_widgetorderadmin_index');
        //Go to widget order page
        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Make sure the page has a form
        $table = $crawler->filter('table.orders-admin');
        $this->assertEmpty($table);

        $this->assertEquals(
            1,
            $crawler->filter('div:contains("There are no orders at the moment.")')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('a:contains("Home")')->count()
        );
    }

    /**
     * Make sure an order will be added when submitted data is correct,
     * user will be redirected and a confirmation message is displayed
     */
    public function testIndex()
    {
        $client = $this->createClient();

        $fixtures = $this->loadFixtures([
            UserData::class,
            ColorData::class,
            WidgetTypeData::class,
            WidgetOrderStatusData::class,
            WidgetOrderData::class,
        ])->getReferenceRepository();

        $url = $this->getContainer()->get('router')->generate('app_widgetorderadmin_index');
        //Go to widget order page
        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Make sure the page has a form
        $table = $crawler->filter('table.orders-admin');
        $this->assertNotEmpty($table);

        $this->assertEquals(2, $table->filter('tr')->count());
        $header = $table->filter('tr')->eq(0);
        $this->assertEquals(6, $header->filter('th')->count());
        $this->assertContains('id', $header->filter('th')->eq(0)->text());

        //Click on the first edit link and check if a form is created
        $firstRow = $table->filter('tr')->eq(1);
        $this->assertEquals(6, $firstRow->filter('td')->count());
        $link = $firstRow->filter('td')->eq(5)->filter('a')->link();
        $this->assertNotEmpty($link);

        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $forms = $crawler->filter('form');
        $this->assertEquals(1, $forms->count());
    }


    /**
     * Make sure an order will be added when submitted data is correct,
     * user will be redirected and a confirmation message is displayed
     */
    public function testSetStatus()
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
        $oldStatus = $widgetOrder->getStatus();

        $url = $this->getContainer()
            ->get('router')
            ->generate('app_widgetorderadmin_setstatus', ['id' => $widgetOrder->getId()]);
        //Go to widget order page
        $crawler = $client->request('GET', $url);

        //Check for form presence
        $forms = $crawler->filter('form');
        $this->assertEquals(1, $forms->count());

        $selectButton = $crawler->selectButton('Send');
        $this->assertNotEmpty($selectButton);

        /**
         * @var WidgetOrderStatus $color
         */
        $newStatus = $fixtures->getReference('WidgetOrderStatus-SHIPPED');
        $this->assertNotEquals($oldStatus, $newStatus);
        $formData = [
            'Status' => $newStatus->getId(),
        ];
        $client->submit($selectButton->form(), ['order_status_form' => $formData]);
        $this->assertTrue($client->getResponse()->isRedirect());
        /**
         * @var EntityManager $em
         */
        $em = $this->getContainer()->get("doctrine")->getManager();
        //Clear the orders from the manager to make sure the one in memory is not returned
        $em->clear(WidgetOrder::class);
        $freshWidgetOrder = $em->getRepository(WidgetOrder::class)->find($widgetOrder->getId());
        //Check if the status has change
        $this->assertEquals($newStatus->getId(), $freshWidgetOrder->getStatus()->getId());

        //Follow the redirect and check for confirmation message
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('div.flash-notice')->count());
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
            ->generate('app_widgetorderadmin_orderdetails', ['id' => $widgetOrder->getId()]);
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