<?php

namespace App\Tests\Functional;


use Liip\FunctionalTestBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();
        //Go to index page
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $links = $crawler->filter('a:contains("Place order")');
        //Make sure the link to order form is present
        $this->assertGreaterThan(0, $links->count());

        //Click on the link
        $crawler = $client->click($links->eq(0)->link());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Make sure the page has a form
        $forms = $crawler->filter('form');
        $this->assertEquals(1, $forms->count());
    }
}