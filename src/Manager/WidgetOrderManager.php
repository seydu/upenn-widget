<?php

namespace App\Manager;


use App\Entity\User;
use App\Entity\WidgetOrder;
use App\Entity\WidgetOrderStatus;
use Doctrine\Common\Persistence\ObjectManager;
use Twig\Environment;

class WidgetOrderManager
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Environment
     */
    private $templating;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * WidgetOrderManager constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager, Environment $templating, \Swift_Mailer $mailer)
    {
        $this->objectManager = $objectManager;
        $this->templating    = $templating;
        $this->mailer        = $mailer;
    }

    /**
     * @param WidgetOrder $widgetOrder
     * @param $userEmail
     * @return User|null|object
     */
    private function processUser(WidgetOrder $widgetOrder, $userEmail)
    {
        if($userEmail instanceof User) {
            return $userEmail;
        }
        $user = $this->objectManager->getRepository(User::class)
            ->findOneBy(['email' => $userEmail]);
        if(!$user) {
            $user = new User();
            $user->setEmail($userEmail);
            $user->setUsername($userEmail);
            $this->objectManager->persist($user);
            $this->objectManager->flush();
        }
        return $user;
    }

    /**
     * @return WidgetOrderStatus
     * @throws \Exception
     */
    private function getDafaultStatus()
    {
        $status = $this->objectManager->getRepository(WidgetOrderStatus::class)
            ->findOneBy(['code' => 'PLACED']);
        if(!$status) {
            throw new \Exception("No order status with code 'PLACED' found");
        }
        return $status;
    }
    /**
     * @param WidgetOrder $widgetOrder
     * @param null $user
     */
    public function save(WidgetOrder $widgetOrder, $user = null)
    {
        if($user) {
            $widgetOrder->setUser($this->processUser($widgetOrder, $user));
        }
        //When no status is set, set the default one (PLACED)
        if(!$widgetOrder->getStatus()) {
            $widgetOrder->setStatus($this->getDafaultStatus());
        }
        $this->objectManager->persist($widgetOrder);
        $this->objectManager->flush();
    }

    /**
     * @param WidgetOrder $widgetOrder
     * @return string
     */
    public function generateConfirmationMessage(WidgetOrder $widgetOrder)
    {
        return sprintf(
            'Your order of %d %s %s has been placed with the ID %d and will be ready by %s',
            $widgetOrder->getQuantity(),
            $widgetOrder->getColor(),
            $widgetOrder->getWidgetType(),
            $widgetOrder->getId(),
            $widgetOrder->getNeededBy()->format('D, d M Y')
        );
    }


    public function sendConfirmationEmail(WidgetOrder $widgetOrder)
    {
        $user = $widgetOrder->getUser();
        if(!$user) {
            return;
        }
        $message = (new \Swift_Message('Widget Order Confirmation'))
            ->setFrom('sender@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'order_confirmation_email.html.twig',
                    ['order' => $widgetOrder]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}