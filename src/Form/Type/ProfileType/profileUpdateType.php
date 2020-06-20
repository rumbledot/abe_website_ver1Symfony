<?php
namespace App\Form\Type\ProfileType;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class profileUpdateType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', TextType::class, array(
            'label'         => 'First name',
            'required'      => true,
            'data'          => $options['profile']->getFirstName(),
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('last_name', TextType::class, array(
            'label'         => 'Last name',
            'required'      => true,
            'data'          => $options['profile']->getLastName(),
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('address', TextareaType::class, array(
            'label'         => 'Current address',
            'required'      => false,
            'data'          => $options['profile']->getAddress(),
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('postcode', NumberType::class, array(
            'label'         => 'Password',
            'required'      => false,
            'data'          => $options['profile']->getPostcode(),
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('phone', NumberType::class, array(
            'label'         => 'Phone',
            'required'      => false,
            'data'          => $options['profile']->getPhone(),
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('mobile', NumberType::class, array(
            'label'         => 'Mobile',
            'required'      => false,
            'data'          => $options['profile']->getMobile(),
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('birthday', DateType::class, array(
            'label'         => 'Birthday',
            'required'      => false,
            'data'          => $options['profile']->getBirthday(),
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
            'data'          => $options['profile']->getBio(),
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));
    }

    public function getBlockPrefix()
    {
        return ('profileUpdate');
    }

    public function configureOptions(OptionsResolver $res)
    {
        $res->setDefaults(array(
            'profile'   => null,
        ));
    }
}