<?php 
namespace Ecommerce\EcommerceBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RechercheType extends AbstractType
{

	 public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('recherche','search',array('label'=> false,'attr' => array('class' => 'form-control','placeholder' => 'recherche ...')));
    }

    public function getName()
    {
    	return 'ecommerce_ecommercebundle_recherche';
    }

}