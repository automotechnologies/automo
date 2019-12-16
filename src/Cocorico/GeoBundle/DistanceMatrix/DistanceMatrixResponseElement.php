<?php

namespace Cocorico\GeoBundle\DistanceMatrix;

class DistanceMatrixResponseElement
{

    /** @var string */
    protected $status;

    /** @var null|Distance */
    protected $distance;

    /** @var null|Duration */
    protected $duration;

    /**
     * Create a distance matrix response element.
     *
     * @param Distance $distance The element distance.
     * @param Duration $duration The element duration.
     * @param string   $status   The element status.
     */
    public function __construct($status, Distance $distance = null, Duration $duration = null)
    {
        $this->setStatus($status);

        if ($distance !== null) {
            $this->setDistance($distance);
        }

        if ($duration !== null) {
            $this->setDuration($duration);
        }
    }

    /**
     * Gets the distance matrix response status.
     *
     * @return string The distance matrix response status.
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the distance matrix response status.
     *
     * @param string $status The distance matrix status.
     *
     * @throws \Exception If the status is not valid.
     */
    public function setStatus($status)
    {
        if (!in_array($status, DistanceMatrix::$statusElements)) {
            throw new \Exception('Invalid distance matrix response element status');
        }

        $this->status = $status;
    }

    /**
     * Gets the step distance.
     *
     * @return Distance The step distance.
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Sets the step distance.
     *
     * @param Distance $distance The step distance.
     */
    public function setDistance(Distance $distance)
    {
        $this->distance = $distance;
    }

    /**
     * Gets the step duration.
     *
     * @return Duration The step duration.
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Sets the step duration
     *
     * @param Duration $duration The step duration.
     */
    public function setDuration(Duration $duration)
    {
        $this->duration = $duration;
    }
}