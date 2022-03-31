<?php

namespace App\Tests\Form;

use App\Form\Type\PasswordResetRequestType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordResetRequestTypeTest extends TestCase
{
    private PasswordResetRequestType $passwordResetRequestType;

    protected function setUp(): void
    {
        $this->passwordResetRequestType = new PasswordResetRequestType();
    }

    private function testBuildForm(): void
    {
        $form = $this->createMock(FormBuilderInterface::class)
            ->expects($this->exactly(5))
            ->method('add')
            ->withConsecutive(
                [
                    'email',
                    EmailType::class
                ],
                [
                    'submit',
                    SubmitType::class
                ],
            )
            ->willReturnSelf();
        $this->passwordResetRequestType->buildForm($form, []);
    }
}
