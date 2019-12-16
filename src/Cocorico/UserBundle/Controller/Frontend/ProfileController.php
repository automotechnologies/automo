<?php

namespace Cocorico\UserBundle\Controller\Frontend;

use Cocorico\CoreBundle\Entity\Listing;
use Cocorico\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller managing the user profile
 *
 * @Route("/user")
 */
class ProfileController extends Controller
{
    /**
     * Show user profile
     *
     * @Route("/{id}/show", name="cocorico_user_profile_show", requirements={
     *      "id" = "\d+"
     * })
     * @Method("GET")
     * @ParamConverter("user", class="CocoricoUserBundle:User")
     *
     * @param  Request $request
     * @param  User    $user
     *
     * @return Response
     * @throws AccessDeniedException
     */
    public function showAction(Request $request, User $user)
    {
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $userListings = $this->getDoctrine()->getRepository(Listing::class)->findByOwner(
            $user->getId(),
            $request->getLocale(),
            array(Listing::STATUS_PUBLISHED)
        );

        //Breadcrumbs
        $breadcrumbs = $this->get('cocorico.breadcrumbs_manager');
        $breadcrumbs->addProfileShowItems($request, $user);

        return $this->render(
            'CocoricoUserBundle:Frontend/Profile:show.html.twig',
            [
                'user' => $user,
                'user_listings' => $userListings
            ]
        );
    }
}
