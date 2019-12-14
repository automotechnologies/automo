<?php

namespace Cocorico\CoreBundle\Entity;

use Cocorico\CoreBundle\Model\BaseBookingPayingRefund;
use Cocorico\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * BookingPayingRefund
 *
 * @ORM\Entity(repositoryClass="Cocorico\CoreBundle\Repository\BookingPayingRefundRepository")
 *
 * @ORM\Table(name="booking_payin_refund",indexes={
 *    @ORM\Index(name="status_pr_idx", columns={"status"}),
 *    @ORM\Index(name="created_at_pr_idx", columns={"createdAt"})
 *  })
 */
class BookingPayingRefund extends BaseBookingPayingRefund
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Cocorico\CoreBundle\Model\CustomIdGenerator")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cocorico\UserBundle\Entity\User", inversedBy="bookingPayinRefunds")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Cocorico\CoreBundle\Entity\Booking", inversedBy="payingRefund")
     * @ORM\JoinColumn(name="booking_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Booking
     */
    private $booking;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param mixed $booking
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function __toString()
    {
        $booking = $this->getBooking();

        return $this->getId() . " (" . $booking->getListing() . ":" . $booking->getStart()->format('d-m-Y') . ")";
    }
}
