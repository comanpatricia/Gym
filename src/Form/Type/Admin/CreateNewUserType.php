<?php

namespace App\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateNewUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'First name'])
            ->add('lastName', TextType::class, ['label' => 'Last name'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('phoneNumber', TextType::class, ['label' => 'Phone number'])
            ->add('cnp', TextType::class, ['label' => 'CNP'])
            ->add('plainPassword', TextType::class, ['label' => 'Password'])
            ->add('Save', SubmitType::class);
    }
}
