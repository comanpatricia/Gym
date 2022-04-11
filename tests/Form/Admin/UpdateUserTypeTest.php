<?php

namespace App\Tests\Form\Admin;

use App\Form\Type\Admin\UpdateUserType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateUserTypeTest extends TestCase
{
    private UpdateUserType $updateUserType;

    protected function setUp(): void
    {
        $this->updateUserType = new UpdateUserType();
    }

    private function testBuildForm(): void
    {
        $form = $this->createMock(FormBuilderInterface::class)
            ->expects($this->exactly(5))
            ->method('add')
            ->withConsecutive(
                [
                    'firstName',
                    TextType::class
                ],
                [
                    'lastName',
                    TextType::class
                ],
                [
                    'email',
                    EmailType::class
                ],
                [
                    'phoneNumber',
                    NumberType::class
                ],
                [
                    'save',
                    SubmitType::class
                ],
            )
            ->willReturnSelf();
        $this->updateUserType->buildForm($form, []);
    }
}
