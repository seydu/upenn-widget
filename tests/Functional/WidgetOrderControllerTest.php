<?php
/**
 * Created by PhpStorm.
 * User: seydu
 * Date: 3/29/18
 * Time: 4:06 PM
 */

namespace App\Tests\Functional;


use App\Tests\DataFixtures\ColorData;
use App\Tests\DataFixtures\UserData;
use App\Tests\DataFixtures\WidgetTypeData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class WidgetOrderControllerTest extends WebTestCase
{
    public function testIndex()
    {
        // add all your fixtures classes that implement
        // Doctrine\Common\DataFixtures\FixtureInterface
        $this->loadFixtures([
            UserData::class,
            ColorData::class,
            WidgetTypeData::class
        ]);

        // you can now run your functional tests with a populated database
        $client = $this->createClient();
        // ...
    }
}