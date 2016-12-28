<?php

namespace Bean\Bundle\LocationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BeanLocationBundle:Default:index.html.twig');
    }
}
