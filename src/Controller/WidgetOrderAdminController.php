<?php

namespace App\Controller;

use App\Entity\WidgetOrder;
use App\Form\CreateOrderFormType;
use App\Form\OrderStatusFormType;
use App\Manager\WidgetOrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class WidgetOrderAdminController extends Controller
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
     * @Route("/admin/orders")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {

        $orders = $widgetOrder = $this->getDoctrine()
            ->getRepository(WidgetOrder::class)->findAll();
        return $this->render('admin_order_list.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/admin/order/{id}")
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function setStatus(Request $request, $id)
    {
        /**
         * @var WidgetOrder $widgetOrder
         */
        $widgetOrder = $this->getDoctrine()
            ->getRepository(WidgetOrder::class)->find($id);
        if(!$widgetOrder) {
            throw new NotFoundHttpException("Cannot find a widget order with id '$id'");
        }
        $oldStatus = $widgetOrder->getStatus();
        $form = $this->createForm(OrderStatusFormType::class, $widgetOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->widgetOrderManager->save($widgetOrder);
            if($oldStatus == $widgetOrder->getStatus()) {
                $message = sprintf(
                    "The status of order #%d was not changed",
                    $widgetOrder->getId()
                );
            } else {
                $message = sprintf(
                    "The status of order #%d was changed from '%s' to '%s'",
                    $widgetOrder->getId(),
                    $oldStatus,
                    $widgetOrder->getStatus()
                );
            }
            $this->addFlash('notice', $message);


            return $this->redirectToRoute('app_widgetorderadmin_index');
        }
        return $this->render('admin_order_set_status.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/order/details/{id}")
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function orderDetails(Request $request, $id)
    {
        $widgetOrder = $this->getDoctrine()
            ->getRepository(WidgetOrder::class)->find($id);
        if(!$widgetOrder) {
            throw new NotFoundHttpException("Cannot find a widget order with id '$id'");
        }

        return $this->render('order_details.html.twig', [
            'order' => $widgetOrder,
            'back' => [
                'label' => 'Orders',
                'url' => $this->get('router')->generate('app_widgetorderadmin_index'),
                'title' => 'Back to the list of orders',
            ]
        ]);
    }
}