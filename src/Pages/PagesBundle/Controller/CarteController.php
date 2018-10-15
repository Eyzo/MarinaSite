<?php

namespace Pages\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class CarteController extends Controller
{

    public function indexAction()
    {
        return $this->render('PagesBundle:Default:pages/layout/carte.html.twig');
    }

}
