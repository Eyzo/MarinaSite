<?php

namespace Pages\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,array('label' => 'Votre nom','attr'=>array('style' => 'width:100%')))
            ->add('email','email',array('label' => 'Votre email','attr'=>array('style' => 'width:100%')))
            ->add('contenu','textarea',array('label' => 'Votre message','attr' => array('rows' => 10,'style' => 'width: 100%')))
            ->add('envoyer','submit',array('label' => 'envoyer','attr' => array('class' => 'button-success','style' => 'margin-top:10px;')))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pages_pagesbundle_contact';
    }
}