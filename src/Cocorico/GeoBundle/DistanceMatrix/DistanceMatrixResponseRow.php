<?php

namespace Cocorico\GeoBundle\DistanceMatrix;

class DistanceMatrixResponseRow
{
    /** @var DistanceMatrixResponseElement[] */
    protected $elements;

    /**
     * Create a distance matrix response row.
     *
     * @param array $elements The row elements.
     */
    public function __construct(array $elements)
    {
        $this->setElements($elements);
    }

    /**
     * Gets the distance matrix row elements.
     *
     * @return DistanceMatrixResponseElement[] The row elements.
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Sets the distance matrix row elements.
     *
     * @param array $elements The row elements.
     */
    public function setElements(array $elements)
    {
        $this->elements = array();

        foreach ($elements as $element) {
            $this->addElement($element);
        }
    }

    /**
     * Add a distance matrix element.
     *
     * @param DistanceMatrixResponseElement $element The element to add.
     */
    public function addElement(DistanceMatrixResponseElement $element)
    {
        $this->elements[] = $element;
    }
}