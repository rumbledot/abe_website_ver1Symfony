<?php
namespace App\Form\Type\UserType;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class userNewType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
            'label'         => 'Username',
            'required'      => true,
            'data'          => $options['username'],
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
                'placeholder'   => 'username 6-12 long no special chars',
            ),
        ));

        $builder->add('password1', PasswordType::class, array(
            'label'         => 'Password',
            'required'      => true,
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
                'placeholder'   => 'password 6-12 long, may contains ! .',
                'autocomplete'  => 'on',
            ),
        ));

        $builder->add('password2', PasswordType::class, array(
            'label'         => 'Re-type Password',
            'required'      => true,
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
                'placeholder'   => 're-type password',
                'disabled'      => 'true',
                'autocomplete'  => 'on',
            ),
        ));

        $builder->add('email', EmailType::class, array(
            'label'         => 'Email',
            'required'      => true,
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
                'placeholder'   => 'user@email.com',
            ),
        ));
    }

    public function getBlockPrefix()
    {
        return ('userNew');
    }

    public function configureOptions(OptionsResolver $res)
    {
        $res->setDefaults(array(
            'username'  => "",
            'error'     => null,
        ));
    }
}