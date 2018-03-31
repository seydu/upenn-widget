<?php

namespace App\Tests\Functional\Form;

use App\Form\CreateOrderFormType;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Form\FormInterface;


class CreateOrderFormTypeTest extends WebTestCase
{
    /**
     * Make sure the form fields are present.
     * We trust the underlying framework for the rest
     */
    public function testCreateForm()
    {
        /**
         * @var FormInterface $form
         */

        $form = $this->getContainer()->get('form.factory')
            ->create(CreateOrderFormType::class);
        $this->assertTrue($form->has('quantity'));
        $this->assertTrue($form->has('neededBy'));
        $this->assertTrue($form->has('Color'));
        $this->assertTrue($form->has('WidgetType'));
    }
}