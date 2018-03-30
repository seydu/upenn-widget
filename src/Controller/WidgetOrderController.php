<?php
/**
 * Created by PhpStorm.
 * User: seydu
 * Date: 3/26/18
 * Time: 3:31 PM
 */

namespace App\Controller;

use App\Entity\WidgetOrder;
use App\Form\CreateOrderFormType;
use App\Manager\WidgetOrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class WidgetOrderController extends Controller
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
     * @Route("/order")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function order(Request $request)
    {
        $widgetOrder = new WidgetOrder();
        $form = $this->createForm(CreateOrderFormType::class, $widgetOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($widgetOrder);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                $this->widgetOrderManager->generateConfirmationMessage($widgetOrder)
            );


            return $this->redirectToRoute('app_index_index');
        }
        return $this->render('order.html.twig', [
            'form' => $form->createView()
        ]);
    }
}