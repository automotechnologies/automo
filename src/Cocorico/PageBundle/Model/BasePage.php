<?php

namespace Cocorico\PageBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * BasePage
 *
 * @todo: move to CMS bundle
 *
 * @ORM\MappedSuperclass
 */
abstract class BasePage
{
    /**
     *
     * @ORM\Column(name="published", type="boolean", nullable=true)
     *
     * @var boolean
     */
    protected $published = false;

    /**
     * Translation proxy
     *
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

}
