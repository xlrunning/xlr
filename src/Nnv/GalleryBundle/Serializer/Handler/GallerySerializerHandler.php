<?php

namespace Nnv\GalleryBundle\Serializer\Handler;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * GallerySerializerHandler
 *
 * @author kail
 */
class GallerySerializerHandler
{
    private $assetPackages;
    
    /**
     *
     * @var RequestStack
     */
    private $requestStack;
    
    public function __construct($assetPackages, RequestStack $requestStack)
    {
        $this->assetPackages = $assetPackages;
        $this->requestStack = $requestStack;
    }
    
    /**
     * Check absolute_url twig helper for more
     * 
     * @param type $visitor
     * @param string $obj
     * @param array $type
     * @return string
     */
    public function onPreSerialize($visitor, $obj, array $type)
    {
        $uriPrefix = '/uploads/gallery';
        $req = $this->requestStack->getMasterRequest();
        return $req->getSchemeAndHttpHost() . $this->assetPackages->getUrl($uriPrefix . "/$obj");
    }
}