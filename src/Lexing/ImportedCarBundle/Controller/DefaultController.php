<?php

namespace Lexing\ImportedCarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LexingImportedCarBundle:Default:index.html.twig');
    }
}
