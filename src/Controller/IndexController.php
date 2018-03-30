<?php

namespace App\Controller;

use App\Entity\WidgetOrder;
use App\Form\CreateOrderFormType;
use App\Manager\WidgetOrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @var WidgetOrderManager
     */
    private $widgetOrderManager;

    /**
     * WidgetController constructor.
     * @param WidgetOrderManager $widgetOrderManager
     */
    public function __construct(WidgetOrderManager $widgetOrderManager)
    {
        $this->widgetOrderManager = $widgetOrderManager;
    }

    /**
     * @Route("/")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        return $this->render('index.html.twig', [

        ]);
    }
}