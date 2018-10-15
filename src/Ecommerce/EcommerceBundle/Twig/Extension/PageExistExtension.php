<?php
namespace Ecommerce\EcommerceBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;

class PageExistExtension extends \Twig_Extension
{

private $em;

public function __construct(EntityManager $em)
{
$this->em = $em;
}

public function getFunctions()
{
	return [
		new \Twig_SimpleFunction('pageExistPrevious',[$this,'pageExistPrevious']),
		new \Twig_SimpleFunction('pageExistNext',[$this,'pageExistNext'])
	];
}

public function pageExistPrevious($page):bool
{
	if (($page - 1) == 0) 
	{
		return false; 
	}
	
	return true;
}

public function pageExistNext($page,$nbTotalPage):bool
{
	if (($page + 1) > $nbTotalPage) 
	{
		return false;	
	}

	return true;
}

public function getName()
{
	return 'page_exist_extension';
}


}