<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class WidgetOrder
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @Assert\NotNull
     * @Assert\GreaterThan(0)
     */
    private $quantity;

    /**
     * @ORM\Column(type="date", nullable=false)
     *
     * @Assert\NotNull
     * @Assert\GreaterThan("+7 days")
     */
    private $neededBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="WidgetOrders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Color", inversedBy="WidgetOrders")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id", nullable=false)
     */
    private $Color;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WidgetType", inversedBy="WidgetOrders")
     * @ORM\JoinColumn(name="widget_type_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotNull
     */
    private $WidgetType;

    /**
     * @param mixed $quantity
     * @return WidgetOrder
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param mixed $neededBy
     * @return WidgetOrder
     */
    public function setNeededBy($neededBy)
    {
        $this->neededBy = $neededBy;
        return $this;
    }

    /**
     * @param User $User
     * @return WidgetOrder
     */
    public function setUser(User $User)
    {
        $this->User = $User;
        return $this;
    }

    /**
     * @param Color $Color
     * @return WidgetOrder
     */
    public function setColor(Color $Color)
    {
        $this->Color = $Color;
        return $this;
    }

    /**
     * @param WidgetType $WidgetType
     * @return WidgetOrder
     */
    public function setWidgetType(WidgetType $WidgetType)
    {
        $this->WidgetType = $WidgetType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return \DateTime
     */
    public function getNeededBy()
    {
        return $this->neededBy;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->Color;
    }

    /**
     * @return WidgetType
     */
    public function getWidgetType()
    {
        return $this->WidgetType;
    }


}