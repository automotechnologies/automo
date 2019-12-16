<?php

namespace Cocorico\ReviewBundle\Extension;

/**
 * StarRatingTwigExtension will render the star ratings in the twig,
 * using single line, depending upon the values for rating
 */
class StarRatingTwigExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @inheritdoc
     */
    public function getGlobals()
    {
        return array();
    }

    /**
     * Returns the filter method for the twig
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'cocorico_star_rating', array($this, 'starRatingFilter'),
                array('needs_environment' => true, 'is_safe' => array('html'))
            ),
        );
    }


    /**
     * startRatingFilter outputs the readonly starts
     *
     * @param \Twig_Environment $env
     * @param                   $rating
     *
     * @return string
     * @inheritdoc
     */
    public function starRatingFilter(\Twig_Environment $env, $rating)
    {
        return $env->render('CocoricoReviewBundle:Frontend/Twig:star_rating.html.twig', array('rating' => $rating));
    }

    /** @inheritdoc */
    public function getName()
    {
        return 'cocorico_star_rating';
    }

}