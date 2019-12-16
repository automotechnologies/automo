<?php

namespace Cocorico\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ListingSearchActionEvent extends Event
{
    protected $request;
    protected $extraViewParams = array();

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getExtraViewParams()
    {
        return $this->extraViewParams;
    }

    /**
     * @param array $extraViewParams
     */
    public function setExtraViewParams($extraViewParams)
    {
        $this->extraViewParams = $extraViewParams;
    }
}
