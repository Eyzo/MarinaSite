<?php 
namespace Ecommerce\EcommerceBundle\Twig\Extension;



class TvaExtension extends \Twig_Extension
{

	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('tva',[$this,'calculTva'])
		];
	}

	public function calculTva($prixHt,$tva)
	{
		return round($prixHt / $tva,2);
	}

	public function getName()
	{
		return 'tva_extension';
	}


}