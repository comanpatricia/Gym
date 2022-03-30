<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordResetRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', \Symfony\Component\Form\Extension\Core\Type\EmailType::class, ['label' => 'Email'])
            ->add('submit', SubmitType::class);
    }
}
