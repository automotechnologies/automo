<?php

namespace Cocorico\CoreBundle\Controller\Frontend;

use Cocorico\CoreBundle\Entity\Booking;
use Cocorico\CoreBundle\Entity\Charge;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Booking Payment controller.
 *
 * @Route("/booking/payment")
 */
class BookingPaymentController extends Controller
{
    /**
     * Payment page.
     *
     * @Route("/{booking_id}/new",
     *      name="cocorico_booking_payment_new",
     *      requirements={
     *          "booking_id" = "\d+"
     *      },
     * )
     *
     * @Security("is_granted('create', booking) and not has_role('ROLE_ADMIN') and has_role('ROLE_USER')")
     *
     * @ParamConverter("booking", class="CocoricoCoreBundle:Booking", options={"id" = "booking_id"})
     *
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Booking $booking
     *
     * @return Response
     */
    public function newAction(Booking $booking, Request $request)
    {
        //Breadcrumbs
        $breadcrumbs = $this->get('cocorico.breadcrumbs_manager');
        $breadcrumbs->addBookingNewItems($request, $booking);

        return $this->render('CocoricoCoreBundle:Frontend/BookingPayment:new.html.twig', ['booking' => $booking]);
    }

    /**
     * @param Request $request
     * @param Booking $booking
     *
     * @Method({"POST"})
     *
     * @return RedirectResponse
     *
     * @Route("/{booking}/check-payment", name="cocorico_check_payment", requirements={"booking" = "\d+"})
     * @Security("is_granted('update_status', booking) and not has_role('ROLE_ADMIN') and has_role('ROLE_USER')")
     *
     * @throws \Exception
     */
    public function paymentCheck(Request $request, Booking $booking)
    {
        if ($booking->getStatus() === Booking::STATUS_PAYED) {
            $url = $this->generateUrl('cocorico_home');
            $this->addFlash('message', 'Payed!!!');

            return $this->redirect($url, 302);
        }

        /**
         * @var bool
         */
        $isProd = $this->get('kernel')->getEnvironment() === 'prod';

        // secret key
        $secretKey = $isProd ? 'stripe_live_secret_key' : 'stripe_test_secret_key';
        \Stripe\Stripe::setApiKey($this->getParameter($secretKey));

        /**
         * @var $stripeCharge \Stripe\ApiResource
         */
        $stripeCharge = \Stripe\Charge::create([
            'amount' => $booking->getAmount() * 100,
            'currency' => $this->getParameter('cocorico.currency'),
            'source' => $request->request->get('stripeToken', ''),
            'receipt_email' => $this->getUser()->getEmail(),
            'metadata' => [
              'booking_id' => $booking->getId(),
              'user' => $booking->getUser()->getId(),
              'booking_status' => $booking->getStatus(),
              'booking_status_text' => $booking->getStatusText(),
            ],
        ]);

        if ($stripeCharge->status === \Stripe\Charge::STATUS_SUCCEEDED && $stripeCharge->paid === true) {
            $charge = new Charge($isProd, $stripeCharge);
            $charge->setBooking($booking);

            $em = $this->getDoctrine()->getManager();
            $booking->setCharge($charge);
            $em->persist($charge);
            $em->flush();

            if ($em->contains($charge)) {
                $booking->setStatus(Booking::STATUS_PAYED);
                $em->persist($booking);
                $em->flush();
            }
        }

        $this->get('session')->getFlashBag()->add(
            'success',
            'Thank you, your payment was successful.'
        );

        return $this->redirectToRoute('cocorico_home');

    }
}
