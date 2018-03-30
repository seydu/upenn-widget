<?php
/**
 * Created by PhpStorm.
 * User: seydu
 * Date: 3/29/18
 * Time: 9:28 AM
 */

namespace App\Manager;


use App\Entity\User;
use App\Entity\WidgetOrder;
use Doctrine\Common\Persistence\ObjectManager;

class WidgetOrderManager
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * WidgetOrderManager constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
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
     * @param WidgetOrder $widgetOrder
     * @param null $user
     */
    public function save(WidgetOrder $widgetOrder, $user = null)
    {
        if($user) {
            $widgetOrder->setUser($this->processUser($widgetOrder, $user));
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
}