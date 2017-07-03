<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/php_version", name="php_version")
     */
    /*public function indexAction()
    {
        phpinfo();
        return Response::create("sadsada",200,array("sitenin-sahibi"=>"burak"));
    }*/

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('todo_list');
    }

}
