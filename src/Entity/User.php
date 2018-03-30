<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WidgetOrder", mappedBy="User")
     */
    private $WidgetOrders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->WidgetOrders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUsername() ?: '-';
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param WidgetOrder $widgetOrder
     * @return User
     */
    public function addWidgetOrder(WidgetOrder $widgetOrder)
    {
        $this->WidgetOrders[] = $widgetOrder;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return WidgetOrder
     */
    public function getWidgetOrders()
    {
        return $this->WidgetOrders;
    }
}