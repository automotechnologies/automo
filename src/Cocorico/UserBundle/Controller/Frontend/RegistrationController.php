<?php

namespace Cocorico\UserBundle\Controller\Frontend;

use Cocorico\UserBundle\Entity\User;
use Cocorico\UserBundle\Form\Type\RegistrationFormType;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Class RegistrationController
 *
 */
class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="cocorico_user_register")
     *
     * @param Request $request
     *
     * @return Response
     * @throws AuthenticationCredentialsNotFoundException
     * @throws \RuntimeException
     */
    public function registerAction(Request $request)
    {
        if ($this->getUser() instanceof UserInterface) {
            return $this->redirectToRoute('cocorico_home');
        }

        $user = $this->get('cocorico_user.user_manager')->createUser();
        $form = $this->createCreateForm($user);
        $isProd = $this->isProd();
        
        $googleReCaptchaIsValid = true;
        if ($request->isMethod('POST')) {
            $googleReCaptchaIsSuccess = true;

            if ($isProd) {
                if ($request->request->has('g-recaptcha-response')) {
                    // Google reCAPTCHA API secret key
                    $secretKey = $this->getParameter('google_recaptcha_secret_key');

                    // Verify the reCAPTCHA response
                    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $request->request->get('g-recaptcha-response'));

                    // Decode json data
                    $responseData = json_decode($verifyResponse);
                    $googleReCaptchaIsSuccess = $responseData->success;
                }
            }

            if ($googleReCaptchaIsSuccess) {
                
                $confirmation = $this->getParameter('cocorico.registration_confirmation');
                $process = $this->get('cocorico_user.form.handler.registration')->process($form, $confirmation);

                if ($process) {
                    /** @var User $user */
                    $user = $form->getData();

                    if ($confirmation) {
                        if ($isProd) {
                            $this->get('session')->getFlashBag()->add(
                                'success',
                                $this->get('translator')->trans('user.register.success', [], 'cocorico_user')
                            );
                        }
                        $this->get('session')->set('cocorico_user_send_confirmation_email/email', $user->getEmail());
                        $url = $this->get('router')->generate('cocorico_user_registration_check_email');
                    } else {
                        $url = $request->get('redirect_to') ? $request->get('redirect_to') :
                            $this->get('router')->generate('cocorico_user_register_confirmed');
                    }

                    return new RedirectResponse($url);
                }
            }
            $form->handleRequest($request);
            $googleReCaptchaIsValid = false;
        }

        return $this->render('CocoricoUserBundle:Frontend/Registration:register.html.twig', [
            'form' => $form->createView(),
            'googleReCaptchaIsValid' => $googleReCaptchaIsValid,
        ]);
    }

    /**
     * Creates a Registration form
     *
     * @param UserInterface $user The entity
     *
     * @return Form The form
     */
    private function createCreateForm(UserInterface $user)
    {
        $form = $this->get('form.factory')->createNamed(
            'user_registration',
            RegistrationFormType::class,
            $user
        );

        return $form;
    }


    /**
     * Tell the user to check their email provider.
     *
     * @Route("/check-email", name="cocorico_user_registration_check_email")
     * @Method("GET")
     *
     * @return RedirectResponse|Response
     * @throws NotFoundHttpException
     */
    public function checkEmailAction()
    {
        $email = $this->get('session')->get('cocorico_user_send_confirmation_email/email');

        if (empty($email)) {
            return new RedirectResponse($this->get('router')->generate('cocorico_user_register'));
        }

        $this->get('session')->remove('cocorico_user_send_confirmation_email/email');
        $user = $this->get('cocorico_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('CocoricoUserBundle:Frontend/Registration:checkEmail.html.twig',
            [
                'user' => $user,
            ]
        );
    }


    /**
     * Receive the confirmation token from user email provider, login the user.
     *
     * @Route("/register-confirmation/{token}", name="cocorico_user_register_confirmation")
     * @Method("GET")
     * @param string $token
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function confirmAction($token)
    {
        /** @var User $user */
        $user = $this->get('cocorico_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEmailVerified(true);
        $user->setLastLogin(new \DateTime());

        $this->get('cocorico_user.form.handler.registration')->handleRegistration($user);

        return new RedirectResponse($this->get('router')->generate('cocorico_user_register_confirmed'));
    }


    /**
     * Tell the user his account is now confirmed.
     *
     * @Route("/register-confirmed", name="cocorico_user_register_confirmed")
     *
     * @return Response
     * @throws AccessDeniedException
     */
    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('CocoricoUserBundle:Frontend/Registration:confirmed.html.twig',
            [
                'user' => $user,
                'targetUrl' => $this->getTargetUrlFromSession(),
            ]
        );
    }

    /**
     * @return mixed|null
     */
    private function getTargetUrlFromSession()
    {
        $key = sprintf('_security.%s.target_path', $this->get('security.token_storage')->getToken()->getProviderKey());

        return $this->get('session')->get($key, null);
    }

    /**
     * @return bool
     */
    private function isProd(): bool
    {
        return $this->container->getParameter('kernel.environment') === 'prod';
    }
}
