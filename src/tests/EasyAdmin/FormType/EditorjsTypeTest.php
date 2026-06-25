<?php

namespace App\Tests\EasyAdmin\FormType;

use App\EasyAdmin\FormType\EditorjsType;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use Symfony\Component\Form\Test\TypeTestCase;

#[AllowMockObjectsWithoutExpectations]
class EditorjsTypeTest extends TypeTestCase
{
    public function testNoInitialData()
    {
        $form = $this->factory->create(EditorjsType::class);

        $this->assertEquals(
            [],
            $form->getViewData()
        );

        $form->submit('[{"data": "test"}]');

        $this->assertTrue($form->isValid());
        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();

        $this->assertEquals('[{"data": "test"}]', $data);
    }

    public function testInitialDataEmptyString()
    {
        $form = $this->factory->create(EditorjsType::class, '');

        $this->assertEquals(
            [],
            $form->getViewData()
        );
    }

    public function testWithInitialData()
    {
        $form = $this->factory->create(EditorjsType::class, '[{"data":"test"}]');

        $this->assertEquals(
            [
                ['data' => 'test'],
            ],
            $form->getViewData()
        );
    }
}
