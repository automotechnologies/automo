<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\CoreBundle\Controller\Frontend;

use Cocorico\CoreBundle\Entity\Booking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Security("not has_role('ROLE_ADMIN') and has_role('ROLE_USER')")
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
     * @Route("/{booking}/check-payment", name="cocorico_check_payment")
     * @Method("POST")
     */
    public function paymentCheck(Request $request, Booking $booking)
    {
        die;
        // secret key
        //\Stripe\Stripe::setApiKey('sk_live_kaRVCycILWqAzQdm3SYhqoFu');
        //\Stripe\Stripe::setApiKey('sk_test_0aHnYPXK1RWb6eKgi54d0hjo');

        //$charge = \Stripe\Charge::create([
        //    'amount' => 20,
        //    'currency' => 'usd'
        //]);

        //$charge = \Stripe\Charge::create([
        //    'amount' => 999,
        //    'currency' => 'usd',
        //    'source' => 'tok_visa',
        //    'receipt_email' => 'jenny.rosen@example.com',
        //]);

        //$product = \Stripe\Product::create([
        //    'name' => 'My SaaS Platform',
        //    'type' => 'service',
        //]);

        //dump($product);
        //dump($product->id);
        //dump($booking);
        dump($request->request->all());
        die;

    }
}
