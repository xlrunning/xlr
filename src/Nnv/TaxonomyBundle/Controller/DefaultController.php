<?php

namespace Nnv\TaxonomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/taxon")
     */
    public function indexAction()
    {
        return $this->render('NnvTaxonomyBundle:Default:index.html.twig');
    }
}
