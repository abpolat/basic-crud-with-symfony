<?php
/**
 * Created by PhpStorm.
 * User: BlackSide
 * Date: 24.05.2017
 * Time: 21:19
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ApiController extends Controller
{
    /**
     * @Route("/api/todos", name="api_todos")
     */
    public function indexAction()
    {

        // if you know the data to send when creating the response
        $response = new JsonResponse(array('status' => 200,'message'=>'Api works!'));

        // if the data to send is already encoded in JSON

        return $response;


    }

    /**
     * @Route("/api/todos/getAll", name="api_todosAll")
     */
    public function getAllTodosAction(){

        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findAll();


        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new
        JsonEncoder()));

        $todos = $serializer->serialize($todos, 'json');

        return new JsonResponse(array('status'=>200,'data'=>json_decode($todos)));


    }


}