<?php

namespace Pages\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PagesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',null,array('attr'=>array('style' => 'width:100%')))
            ->add('contenu','textarea',array('attr' => array('rows' => 10,'style' => 'width: 100%')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pages\PagesBundle\Entity\Pages'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pages_pagesbundle_pages';
    }
}
