<?php
/**
 * Created by PhpStorm.
 * User: seydu
 * Date: 3/27/18
 * Time: 11:02 AM
 */

namespace App\Tests\Form;

use App\Entity\Color;
use App\Entity\WidgetOrder;
use App\Entity\WidgetType;
use App\Form\CreateOrderFormType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Test\Traits\ValidatorExtensionTrait;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class CreateOrderFormTypeTest extends TypeTestCase
{
    use ValidatorExtensionTrait;
    public function testCreateForm()
    {
        $formData = array(
            'quantity' => 3,
            'Color' => new Color(),
            new \DateTime(),
            'WidgetType' => new WidgetType(),
        );

        $object = new WidgetOrder();
        $form = $this->factory->create(CreateOrderFormType::class, $object);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        //$this->assertFalse($form->isValid());
        $errors = [];
        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }
        fwrite(STDERR, var_dump($errors));

    }
}