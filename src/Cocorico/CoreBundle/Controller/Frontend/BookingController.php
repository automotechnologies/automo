<?php

namespace Cocorico\CoreBundle\Controller\Frontend;

use Cocorico\CoreBundle\Entity\Booking;
use Cocorico\CoreBundle\Entity\Listing;
use Cocorico\CoreBundle\Event\BookingEvent;
use Cocorico\CoreBundle\Event\BookingEvents;
use Cocorico\CoreBundle\Form\Type\Frontend\BookingNewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Booking controller.
 *
 * @Route("/booking")
 */
class BookingController extends Controller
{
    /**
     * Creates a new Booking entity.
     *
     * @Route("/{listing_id}/{start}/{end}/new",
     *      name="cocorico_booking_new",
     *      requirements={
     *          "listing_id" = "\d+"
     *      },
     * )
     *
     *
     * @Security("is_granted('booking', listing) and not has_role('ROLE_ADMIN') and has_role('ROLE_USER')")
     *
     * @ParamConverter("listing", class="CocoricoCoreBundle:Listing", options={"id" = "listing_id"}, converter="doctrine.orm")
     * @ParamConverter("start", options={"format": "Y-m-d-H:i"}, converter="datetime")
     * @ParamConverter("end", options={"format": "Y-m-d-H:i"}, converter="datetime")
     *
     *
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param Listing   $listing
     * @param \DateTime $start format yyyy-mm-dd-H:i
     * @param \DateTime $end   format yyyy-mm-dd-H:i
     *
     * @return Response
     */
    public function newAction(Request $request, Listing $listing, \DateTime $start, \DateTime $end)
    {
        $bookingHandler = $this->get('cocorico.form.handler.booking');
        $booking = $bookingHandler->init($this->getUser(), $listing, $start, $end);
        //Availability is validated through BookingValidator and amounts are setted through Form Event PRE_SET_DATA
        $form = $this->createCreateForm($booking);

        $success = $bookingHandler->process($form);
        if ($success === 1) {//Success
            $event = new BookingEvent($booking);

            try {
                $this->get('event_dispatcher')->dispatch(BookingEvents::BOOKING_NEW_SUBMITTED, $event);
                $response = $event->getResponse();

                if ($response === null) {//No response means we can create new booking
                    if ($booking) {
                        //New Booking confirmation
                        $this->get('session')->getFlashBag()->add(
                            'success',
                            $this->get('translator')->trans('booking.new.success', array(), 'cocorico_booking')
                        );

                        $response = new RedirectResponse(
                            $this->generateUrl('cocorico_booking_payment_new', ['booking_id' => $booking->getId()])
                        );
                    } else {
                        throw new \Exception('booking.new.form.error');
                    }
                }

                return $response;
            } catch (\Exception $e) {
                //Errors message are created in event subscribers
                $this->get('session')->getFlashBag()->add(
                    'error',
                    /** @Ignore */
                    $this->get('translator')->trans($e->getMessage(), array(), 'cocorico_booking')
                );
            }
        } else {
            $this->addFormMessagesToFlashBag($success);
        }

        //Breadcrumbs
        $breadcrumbs = $this->get('cocorico.breadcrumbs_manager');
        $breadcrumbs->addBookingNewItems($request, $booking);

        return $this->render(
            'CocoricoCoreBundle:Frontend/Booking:new.html.twig',
            [
                'booking' => $booking,
                'form' => $form->createView(),
                //Used to hide errors fields message when a secondary submission (Voucher, Delivery, ...) is done successfully
                'display_errors' => ($success < 2)
            ]
        );
    }

    /**
     * Form message for specific bundles
     *
     * @param int $success
     */
    private function addFormMessagesToFlashBag($success)
    {
        $session = $this->get('session');
        $translator = $this->get('translator');

        if ($success === 2) {//Voucher code is valid
            $session->getFlashBag()->add(
                'success_voucher',
                $translator->trans('booking.new.voucher.success', array(), 'cocorico_booking')
            );
        } elseif ($success === 3) {//Delivery is valid
            $session->getFlashBag()->add(
                'success',
                $translator->trans('booking.new.delivery.success', array(), 'cocorico_booking')
            );
        } elseif ($success === 4) {//Options is valid
            $session->getFlashBag()->add(
                'success',
                $translator->trans('booking.new.options.success', array(), 'cocorico_booking')
            );
        } elseif ($success < 0) {//Errors
            $this->addFlashError($success);
        }
    }

    /**
     * @param $success
     */
    private function addFlashError($success)
    {
        $translator = $this->get('translator');
        $errorMsg = $translator->trans('booking.new.unknown.error', array(), 'cocorico_booking'); //-4
        $flashType = 'error';

        if ($success == -1) {
            $errorMsg = $translator->trans('booking.new.form.error', array(), 'cocorico_booking');
        } elseif ($success == -2) {
            $errorMsg = $translator->trans('booking.new.self_booking.error', array(), 'cocorico_booking');
        } elseif ($success == -3) {
            $errorMsg = $translator->trans('booking.new.voucher_code.error', array(), 'cocorico_booking');
            $flashType = 'error_voucher';
        } elseif ($success == -4) {
            $errorMsg = $translator->trans('booking.new.voucher_amount.error', array(), 'cocorico_booking');
            $flashType = 'error_voucher';
        } elseif ($success == -5) {
            $errorMsg = $translator->trans('booking.new.delivery_max.error', array(), 'cocorico_booking');
            $flashType = 'error';
        } elseif ($success == -6) {
            $errorMsg = $translator->trans('booking.new.delivery.error', array(), 'cocorico_booking');
            $flashType = 'error';
        }

        $this->get('session')->getFlashBag()->add($flashType, $errorMsg);
    }

    /**
     * Creates a form to create a Booking entity.
     *
     * @param Booking $booking The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Booking $booking)
    {
        $form = $this->get('form.factory')->createNamed(
            '',
            BookingNewType::class,
            $booking,
            [
                'method' => 'POST',
                'action' => $this->generateUrl(
                    'cocorico_booking_new',
                    [
                        'listing_id' => $booking->getListing()->getId(),
                        'start' => $booking->getStart()->format('Y-m-d-H:i'),
                        'end' => $booking->getEnd()->format('Y-m-d-H:i'),
                    ]
                ),
            ]
        );

        return $form;
    }
}
