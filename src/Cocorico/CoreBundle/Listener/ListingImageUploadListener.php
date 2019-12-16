<?php

namespace Cocorico\CoreBundle\Listener;

use Cocorico\CoreBundle\Entity\Listing;
use Cocorico\CoreBundle\Model\Manager\ListingManager;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ListingImageUploadListener
{
    protected $lem;

    public function __construct(ListingManager $lem)
    {
        $this->lem = $lem;
    }

    public function onUpload(PostUploadEvent $event)
    {
        /** @var UploadedFile $file */
        $file = $event->getFile();
        $request = $event->getRequest();
        $response = $event->getResponse();
        //print_r($request->request->all());
        $idListing = $request->query->get("listing_id");

        if ($idListing) {

            /** @var Listing $listing */
//            $listing = $this->lem->getRepository()->find($idListing);
//            $this->lem->addImages(
//                $listing,
//                array($file->getFilename()),
//                true
//            );
        } else {//New Listing

        }

        $response['files'] = array(
            array(
                'name' => $file->getFilename(),
//                'size' => $file->getSize(),
//                'url' => '',
//                'deleteUrl' => '',
//                'deleteType' => 'DELETE'
            )
        );
    }

}
