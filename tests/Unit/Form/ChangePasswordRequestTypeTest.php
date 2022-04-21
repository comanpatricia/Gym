<?php

namespace App\Tests\Unit\Form;

use App\Form\Type\ChangePasswordRequestType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordRequestTypeTest extends TestCase
{
    private ChangePasswordRequestType $changePasswordRequestType;

    protected function setUp(): void
    {
        $this->changePasswordRequestType = new ChangePasswordRequestType();
    }

    private function testBuildForm(): void
    {
        $form = $this->createMock(FormBuilderInterface::class)
            ->expects($this->exactly(5))
            ->method('add')
            ->withConsecutive(
                [
                    'newPassword',
                    RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'The password fields must match.',
                        'required' => true,
                        'first_options' => ['label' => 'Password'],
                        'second_options' => ['label' => 'Confirm password']
                    ],
                ],
                [
                    'save',
                    SubmitType::class
                ],
            )
            ->willReturnSelf();
        $this->changePasswordRequestType->buildForm($form, []);
    }
}
