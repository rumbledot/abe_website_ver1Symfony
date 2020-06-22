<?php
namespace App\Form\Type\ProfileType;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class profileNewType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', TextType::class, array(
            'label'         => 'First name',
            'required'      => true,
            'data'          => '',
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('last_name', TextType::class, array(
            'label'         => 'Last name',
            'required'      => true,
            'data'          => '',
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('address', TextareaType::class, array(
            'label'         => 'Current address',
            'required'      => false,
            'data'          => '',
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('postcode', NumberType::class, array(
            'label'         => 'Postcode',
            'required'      => false,
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('phone', NumberType::class, array(
            'label'         => 'Phone',
            'required'      => false,
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('mobile', NumberType::class, array(
            'label'         => 'Mobile',
            'required'      => false,
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('birthday', DateType::class, array(
            'label'         => 'Birthday',
            'required'      => false,
            'empty_data'    => '',
            'widget' => 'choice',
            'years' => range(date('Y')-100, date('Y')),
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('bio', TextareaType::class, array(
            'label'         => 'Notes',
            'required'      => false,
            'data'          => '',
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
                'rows'          => '8',
            ),
        ));
    }

    public function getBlockPrefix()
    {
        return ('profileNew');
    }

    public function configureOptions(OptionsResolver $res)
    {
        $res->setDefaults(array(
            'profile'   => "",
        ));
    }
}