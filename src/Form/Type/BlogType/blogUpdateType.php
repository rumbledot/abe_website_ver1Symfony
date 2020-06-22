<?php
namespace App\Form\Type\BlogType;

use App\Entity\Blog;

use Doctrine\DBAL\Types\TextType as TypesTextType;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class blogUpdateType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('state', ChoiceType::class, array(
            'label'         => 'State',
            'required'      => true,
            'data'          => $options['curr_state'],
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
            'data'          => $options['blog']->getTitle(),
            'attr'          => array(
                'class'         => 'form-control',
            ),
        ));

        $builder->add('body', TextareaType::class, array(
            'label'         => 'Content',
            'required'      => true,
            'empty_data'    => '',
            'data'          => $options['blog']->getBody(),
            'attr'          => array(
                'class'         => 'form-control',
                'rows'          => '8',
            ),
        ));
    }

    public function getBlockPrefix()
    {
        return ('blogUpdate');
    }

    public function configureOptions(OptionsResolver $res)
    {
        $res->setDefaults(array(
            'curr_state'    => null,
            'states'        => Blog::BLOG_STATE_DRAFT,
            'blog'          => null,
        ));
    }
}