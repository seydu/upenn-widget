<?php

namespace App\Controller;

use App\Entity\WidgetOrder;
use App\Form\CreateOrderFormType;
use App\Manager\WidgetOrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function order(Request $request, \Swift_Mailer $mailer)
    {
        $widgetOrder = new WidgetOrder();
        $form = $this->createForm(CreateOrderFormType::class, $widgetOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEmail = $form->get('user_email')->getData();
            $this->widgetOrderManager->save($widgetOrder, $userEmail);
            $this->addFlash(
                'notice',
                $this->widgetOrderManager->generateConfirmationMessage($widgetOrder)
            );

            $this->sendConfirmationEmail($widgetOrder, $mailer);


            return $this->redirectToRoute('app_index_index');
        }
        return $this->render('order.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/order/{id}")
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
            'order' => $widgetOrder
        ]);
    }

    private function sendConfirmationEmail(WidgetOrder $widgetOrder, \Swift_Mailer $mailer)
    {
        $user = $widgetOrder->getUser();
        if(!$user) {
            return;
        }
        $message = (new \Swift_Message('Widget Order Confirmation'))
            ->setFrom('sender@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'order_confirmation_email.html.twig',
                    ['order' => $widgetOrder]
                ),
                'text/html'
            );
        $mailer->send($message);
    }
}