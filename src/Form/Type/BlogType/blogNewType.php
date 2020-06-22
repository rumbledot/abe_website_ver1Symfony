<?php
namespace App\Form\Type\BlogType;

use Doctrine\DBAL\Types\TextType as TypesTextType;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class blogNewType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('state', ChoiceType::class, array(
            'label'         => 'State',
            'required'      => true,
            'empty_data'    => '',
            'choices'       => $options['states'],
            'attr'          => array(
                'class'         => 'form-control',
                'placeholder'   => 'give your new blog a title',
            ),
        ));

        $builder->add('title', TextType::class, array(
            'label'         => 'Title',
            'required'      => true,
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
                'placeholder'   => 'give your new blog a title',
            ),
        ));

        $builder->add('body', TextareaType::class, array(
            'label'         => 'Content',
            'required'      => true,
            'empty_data'    => '',
            'attr'          => array(
                'class'         => 'form-control',
                'placeholder'   => 'write something about it',
                'rows'          => '8',
            ),
        ));
    }

    public function getBlockPrefix()
    {
        return ('blogNew');
    }

    public function configureOptions(OptionsResolver $res)
    {
        $res->setDefaults(array(
            'states'        => null,
            'user_id'       => null,
            'blog'          => null,
        ));
    }
}