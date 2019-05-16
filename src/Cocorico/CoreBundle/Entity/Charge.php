<?php

namespace Cocorico\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Charge
 * @package Cocorico\CoreBundle\Entity
 *
 * @ORM\Table(name="charge", indexes={
 *     @ORM\Index(name="charge_idx", columns={"charge_id"})
 * })
 * @ORM\Entity()
 */
class Charge
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @var Booking
     * @ORM\OneToOne(targetEntity="Cocorico\CoreBundle\Entity\Booking", mappedBy="charge")
     */
    private $booking;

    /**
     * @var string
     * @ORM\Column(name="charge_id", type="string", nullable=false, unique=true)
     */
    private $chargeId;

    /**
     * @var string
     * @ORM\Column(name="object", type="string", nullable=true)
     */
    private $object;

    /**
     * @var integer
     * @ORM\Column(name="amount", type="integer", nullable=true)
     */
    private $amount = 0;

    /**
     * @var int
     * @ORM\Column(name="amount_refunded", type="integer", nullable=true)
     */
    private $amount_refunded = 0;

    /**
     * @var string
     * @ORM\Column(name="application", type="string", nullable=true)
     */
    private $application;

    /**
     * @var string
     * @ORM\Column(name="application_fee", type="string", nullable=true)
     */
    private $application_fee;

    /**
     * @var int
     * @ORM\Column(name="application_fee_amount", type="integer", nullable=true)
     */
    private $application_fee_amount;

    /**
     * @var string
     * @ORM\Column(name="balance_transaction", type="string", nullable=true)
     */
    private $balance_transaction;

    /**
     * @var array
     * @ORM\Column(name="billing_details", type="array", nullable=true)
     */
    private $billing_details;

    /**
     * @var boolean
     * @ORM\Column(name="captured", type="boolean", nullable=true)
     */
    private $captured;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var string
     * @ORM\Column(name="currency", type="string", nullable=true)
     */
    private $currency;

    /**
     * @var string
     * @ORM\Column(name="customer", type="string", nullable=true)
     */
    private $customer;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="dispute", type="string", nullable=true)
     */
    private $dispute;

    /**
     * @var string
     * @ORM\Column(name="failure_code", type="string", nullable=true)
     */
    private $failure_code;

    /**
     * @var string
     * @ORM\Column(name="failure_message", type="string", nullable=true)
     */
    private $failure_message;

    /**
     * @var array
     * @ORM\Column(name="fraud_details", type="array", nullable=true)
     */
    private $fraud_details;

    /**
     * @var string
     * @ORM\Column(name="invoice", type="string", nullable=true)
     */
    private $invoice;

    /**
     * @var boolean
     * @ORM\Column(name="livemode", type="boolean", nullable=true)
     */
    private $livemode;

    /**
     * @var array
     * @ORM\Column(name="metadata", type="array", nullable=true)
     */
    private $metadata;

    /**
     * @var string
     * @ORM\Column(name="on_behalf_of", type="string", nullable=true)
     */
    private $on_behalf_of;

    /**
     * @var string
     * @ORM\Column(name="stripe_order", type="string", nullable=true)
     */
    private $order;

    /**
     * @var array
     * @ORM\Column(name="outcome", type="array", nullable=true)
     */
    private $outcome;

    /**
     * @var boolean
     * @ORM\Column(name="paid", type="boolean", nullable=true)
     */
    private $paid = false;

    /**
     * @var string
     * @ORM\Column(name="payment_intent", type="string", nullable=true)
     */
    private $payment_intent;

    /**
     * @var string
     * @ORM\Column(name="payment_method", type="string", nullable=true)
     */
    private $payment_method;

    /**
     * @var array
     * @ORM\Column(name="payment_method_details", type="array", nullable=true)
     */
    private $payment_method_details;

    /**
     * @var string
     * @ORM\Column(name="receipt_email", type="string", nullable=true)
     */
    private $receipt_email;

    /**
     * @var string
     * @ORM\Column(name="receipt_number", type="string", nullable=true)
     */
    private $receipt_number;

    /**
     * @var string
     * @ORM\Column(name="receipt_url", type="string", nullable=true)
     */
    private $receipt_url;

    /**
     * @var boolean
     * @ORM\Column(name="refunded", type="boolean", nullable=true)
     */
    private $refunded;

    /**
     * @var array
     * @ORM\Column(name="refunds", type="array", nullable=true)
     */
    private $refunds;

    /**
     * @var string
     * @ORM\Column(name="review", type="string", nullable=true)
     */
    private $review;

    /**
     * @var array
     * @ORM\Column(name="shipping", type="array", nullable=true)
     */
    private $shipping;

    /**
     * @var array
     * @ORM\Column(name="source", type="array", nullable=true)
     */
    private $source;

    /**
     * @var string
     * @ORM\Column(name="source_transfer", type="string", nullable=true)
     */
    private $source_transfer;

    /**
     * @var string
     * @ORM\Column(name="statement_descriptor", type="string", nullable=true)
     */
    private $statement_descriptor;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", nullable=true, options={"comment": "The status of the payment is either succeeded, pending, or failed."})
     */
    private $status;

    /**
     * @var array
     * @ORM\Column(name="transfer_data", type="array", nullable=true)
     */
    private $transfer_data;

    /**
     * @var string
     * @ORM\Column(name="transfer_group", type="string", nullable=true)
     */
    private $transfer_group;

    public function __construct($stripeCharge = null)
    {
        if($stripeCharge instanceof \Stripe\Charge) {
            $this->setChargeId($stripeCharge->id);
            $this->setObject($stripeCharge->object);
            $this->setAmount($stripeCharge->amount);
            $this->setAmountRefunded($stripeCharge->amount_refunded);
            $this->setApplication($stripeCharge->application);
            $this->setApplicationFee($stripeCharge->application_fee);
            $this->setBalanceTransaction($stripeCharge->balance_transaction);
            $this->setCaptured($stripeCharge->captured);

            $date = new \DateTime();
            $date->setTimestamp($stripeCharge->created);

            $this->setCreated($date);
            $this->setCurrency($stripeCharge->currency);
            $this->setCustomer($stripeCharge->customer);
            $this->setDescription($stripeCharge->description);
            $this->setDispute($stripeCharge->dispute);
            $this->setFailureCode($stripeCharge->failure_code);
            $this->setFailureMessage($stripeCharge->failure_message);
            $this->setFraudDetails($stripeCharge->fraud_details);
            $this->setInvoice($stripeCharge->invoice);
            $this->setLivemode($stripeCharge->livemode);
            $this->setMetadata($stripeCharge->metadata->jsonSerialize());
            $this->setOnBehalfOf($stripeCharge->on_behalf_of);
            $this->setOrder($stripeCharge->order);
            $this->setOutcome($stripeCharge->outcome->values());

            /**
             * @var $paymentMethod \Stripe\StripeObject
             */
            $paymentMethod = $stripeCharge->payment_method_details;

            $this->setPaymentMethod($paymentMethod);
            $this->setPaymentMethodDetails($paymentMethod->jsonSerialize());
            $this->setPaid($stripeCharge->paid);
            $this->setPaymentIntent($stripeCharge->payment_intent);
            $this->setReceiptEmail($stripeCharge->receipt_email);
            $this->setReceiptUrl($stripeCharge->receipt_url);
            $this->setRefunded($stripeCharge->refunded);
            $this->setRefunds($stripeCharge->refunds->values());
            $this->setReview($stripeCharge->review);
            $this->setShipping($stripeCharge->shipping);
            $this->setSourceTransfer($stripeCharge->source_transfer);
            $this->setStatementDescriptor($stripeCharge->statement_descriptor);
            $this->setStatus($stripeCharge->status);
            $this->setTransferData($stripeCharge->transfer_data ?? []);
            $this->setTransferGroup($stripeCharge->transfer_group);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @return string
     */
    public function getChargeId()
    {
        return $this->chargeId;
    }

    /**
     * @param string $chargeId
     */
    public function setChargeId(string $chargeId)
    {
        $this->chargeId = $chargeId;
    }

    /**
     * @param Booking $booking
     */
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmountRefunded()
    {
        return $this->amount_refunded;
    }

    /**
     * @param int $amount_refunded
     */
    public function setAmountRefunded($amount_refunded)
    {
        $this->amount_refunded = $amount_refunded;
    }

    /**
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param string $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @return string
     */
    public function getApplicationFee()
    {
        return $this->application_fee;
    }

    /**
     * @param string $application_fee
     */
    public function setApplicationFee($application_fee)
    {
        $this->application_fee = $application_fee;
    }

    /**
     * @return int
     */
    public function getApplicationFeeAmount()
    {
        return $this->application_fee_amount;
    }

    /**
     * @param int $application_fee_amount
     */
    public function setApplicationFeeAmount($application_fee_amount)
    {
        $this->application_fee_amount = $application_fee_amount;
    }

    /**
     * @return string
     */
    public function getBalanceTransaction()
    {
        return $this->balance_transaction;
    }

    /**
     * @param string $balance_transaction
     */
    public function setBalanceTransaction($balance_transaction)
    {
        $this->balance_transaction = $balance_transaction;
    }

    /**
     * @return array
     */
    public function getBillingDetails()
    {
        return $this->billing_details;
    }

    /**
     * @param array $billing_details
     */
    public function setBillingDetails($billing_details)
    {
        $this->billing_details = $billing_details;
    }

    /**
     * @return bool
     */
    public function isCaptured()
    {
        return $this->captured;
    }

    /**
     * @param bool $captured
     */
    public function setCaptured($captured)
    {
        $this->captured = $captured;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param string $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDispute()
    {
        return $this->dispute;
    }

    /**
     * @param string $dispute
     */
    public function setDispute($dispute)
    {
        $this->dispute = $dispute;
    }

    /**
     * @return string
     */
    public function getFailureCode()
    {
        return $this->failure_code;
    }

    /**
     * @param string $failure_code
     */
    public function setFailureCode($failure_code)
    {
        $this->failure_code = $failure_code;
    }

    /**
     * @return string
     */
    public function getFailureMessage()
    {
        return $this->failure_message;
    }

    /**
     * @param string $failure_message
     */
    public function setFailureMessage($failure_message)
    {
        $this->failure_message = $failure_message;
    }

    /**
     * @return array
     */
    public function getFraudDetails()
    {
        return $this->fraud_details;
    }

    /**
     * @param array $fraud_details
     */
    public function setFraudDetails($fraud_details)
    {
        $this->fraud_details = $fraud_details;
    }

    /**
     * @return string
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param string $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return bool
     */
    public function isLivemode()
    {
        return $this->livemode;
    }

    /**
     * @param bool $livemode
     */
    public function setLivemode($livemode)
    {
        $this->livemode = $livemode;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return string
     */
    public function getOnBehalfOf()
    {
        return $this->on_behalf_of;
    }

    /**
     * @param string $on_behalf_of
     */
    public function setOnBehalfOf($on_behalf_of)
    {
        $this->on_behalf_of = $on_behalf_of;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return array
     */
    public function getOutcome()
    {
        return $this->outcome;
    }

    /**
     * @param array $outcome
     */
    public function setOutcome($outcome)
    {
        $this->outcome = $outcome;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->paid;
    }

    /**
     * @param bool $paid
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }

    /**
     * @return string
     */
    public function getPaymentIntent()
    {
        return $this->payment_intent;
    }

    /**
     * @param string $payment_intent
     */
    public function setPaymentIntent($payment_intent)
    {
        $this->payment_intent = $payment_intent;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * @param string $payment_method
     */
    public function setPaymentMethod($payment_method)
    {
        $this->payment_method = $payment_method;
    }

    /**
     * @return array
     */
    public function getPaymentMethodDetails()
    {
        return $this->payment_method_details;
    }

    /**
     * @param array $payment_method_details
     */
    public function setPaymentMethodDetails($payment_method_details)
    {
        $this->payment_method_details = $payment_method_details;
    }

    /**
     * @return string
     */
    public function getReceiptEmail()
    {
        return $this->receipt_email;
    }

    /**
     * @param string $receipt_email
     */
    public function setReceiptEmail($receipt_email)
    {
        $this->receipt_email = $receipt_email;
    }

    /**
     * @return string
     */
    public function getReceiptNumber()
    {
        return $this->receipt_number;
    }

    /**
     * @param string $receipt_number
     */
    public function setReceiptNumber($receipt_number)
    {
        $this->receipt_number = $receipt_number;
    }

    /**
     * @return string
     */
    public function getReceiptUrl()
    {
        return $this->receipt_url;
    }

    /**
     * @param string $receipt_url
     */
    public function setReceiptUrl($receipt_url)
    {
        $this->receipt_url = $receipt_url;
    }

    /**
     * @return bool
     */
    public function isRefunded()
    {
        return $this->refunded;
    }

    /**
     * @param bool $refunded
     */
    public function setRefunded($refunded)
    {
        $this->refunded = $refunded;
    }

    /**
     * @return array
     */
    public function getRefunds()
    {
        return $this->refunds;
    }

    /**
     * @param array $refunds
     */
    public function setRefunds($refunds)
    {
        $this->refunds = $refunds;
    }

    /**
     * @return string
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param string $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * @return array
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param array $shipping
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param array $source
     */
    public function setSource($source)
    {
        $this->source = $source ?? [];
    }

    /**
     * @return string
     */
    public function getSourceTransfer()
    {
        return $this->source_transfer;
    }

    /**
     * @param string $source_transfer
     */
    public function setSourceTransfer($source_transfer)
    {
        $this->source_transfer = $source_transfer;
    }

    /**
     * @return string
     */
    public function getStatementDescriptor()
    {
        return $this->statement_descriptor;
    }

    /**
     * @param string $statement_descriptor
     */
    public function setStatementDescriptor($statement_descriptor)
    {
        $this->statement_descriptor = $statement_descriptor;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getTransferData()
    {
        return $this->transfer_data;
    }

    /**
     * @param array $transfer_data
     */
    public function setTransferData($transfer_data)
    {
        $this->transfer_data = $transfer_data;
    }

    /**
     * @return string
     */
    public function getTransferGroup()
    {
        return $this->transfer_group;
    }

    /**
     * @param string $transfer_group
     */
    public function setTransferGroup($transfer_group)
    {
        $this->transfer_group = $transfer_group;
    }
}