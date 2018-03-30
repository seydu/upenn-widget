<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class WidgetType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=60, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WidgetOrder", mappedBy="WidgetType")
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
     * @return WidgetType
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $weight
     * @return WidgetType
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWidgetOrders()
    {
        return $this->WidgetOrders;
    }
}