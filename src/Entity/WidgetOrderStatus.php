<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class WidgetOrderStatus
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
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WidgetOrder", mappedBy="Status")
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
        return $this->getName() ?: '-';
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param WidgetOrder $widgetOrder
     * @return WidgetType
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return WidgetOrder[]
     */
    public function getWidgetOrders()
    {
        return $this->WidgetOrders;
    }
}