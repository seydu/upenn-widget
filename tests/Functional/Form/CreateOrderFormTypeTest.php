<?php

namespace App\Tests\Functional\Form;

use App\Form\CreateOrderFormType;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Form\FormInterface;
use App\Tests\DataFixtures\ColorData;
use App\Tests\DataFixtures\UserData;
use App\Tests\DataFixtures\WidgetOrderStatusData;
use App\Tests\DataFixtures\WidgetTypeData;


class CreateOrderFormTypeTest extends WebTestCase
{
    private function setColorPositions(array $colors)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getContainer()->get("doctrine")->getManager();
        foreach ($colors as $item) {
            $color = $item[1];
            $color->setWeight($item[0]);
            $em->persist($color);
        }
        $em->flush();
    }

    /**
     * Make sure the form fields are present.
     * We trust the underlying framework for the rest
     */
    public function testCreateForm()
    {
        $fixtures = $this->loadFixtures([
            UserData::class,
            ColorData::class,
            WidgetTypeData::class,
            WidgetOrderStatusData::class,
        ])->getReferenceRepository();
        $colorRed = $fixtures->getReference('Color-red');
        $colorBlue = $fixtures->getReference('Color-blue');
        $colorYellow = $fixtures->getReference('Color-yellow');
        $this->setColorPositions(
            [
                [1, $colorRed],
                [2, $colorBlue],
                [3, $colorYellow]
            ]
        );

        /**
         * @var FormInterface $form
         */

        $form = $this->getContainer()->get('form.factory')
            ->create(CreateOrderFormType::class);
        $this->assertTrue($form->has('quantity'));
        $this->assertTrue($form->has('neededBy'));
        $this->assertTrue($form->has('Color'));
        $this->assertTrue($form->has('WidgetType'));
        $formView = $form->createView();
        $choices = array_values($formView['Color']->vars['choices']);
        $this->assertCount(3, $choices);
        $this->assertEquals($colorRed->getId(), $choices[0]->value);
        $this->assertEquals($colorBlue->getId(), $choices[1]->value);
        $this->assertEquals($colorYellow->getId(), $choices[2]->value);
    }



    /**
     * Make sure the form fields are present.
     * We trust the underlying framework for the rest
     */
    public function testCreateFormWithNewColorOrder()
    {
        $fixtures = $this->loadFixtures([
            UserData::class,
            ColorData::class,
            WidgetTypeData::class,
            WidgetOrderStatusData::class,
        ])->getReferenceRepository();
        $colorRed = $fixtures->getReference('Color-red');
        $colorBlue = $fixtures->getReference('Color-blue');
        $colorYellow = $fixtures->getReference('Color-yellow');
        $this->setColorPositions(
            [
                [3, $colorRed],
                [2, $colorBlue],
                [1, $colorYellow]
            ]
        );

        /**
         * @var FormInterface $form
         */

        $form = $this->getContainer()->get('form.factory')
            ->create(CreateOrderFormType::class);
        $this->assertTrue($form->has('quantity'));
        $this->assertTrue($form->has('neededBy'));
        $this->assertTrue($form->has('Color'));
        $this->assertTrue($form->has('WidgetType'));
        $formView = $form->createView();
        $choices = array_values($formView['Color']->vars['choices']);
        $this->assertCount(3, $choices);
        $this->assertEquals($colorRed->getId(), $choices[2]->value);
        $this->assertEquals($colorBlue->getId(), $choices[1]->value);
        $this->assertEquals($colorYellow->getId(), $choices[0]->value);
    }
}