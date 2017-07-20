<?php

namespace Nnv\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * ApiController
 *
 * @Route("/form")
 */
class ApiController extends Controller
{
    /**
     *
     * @Route("/populate-choices/{from}/{to}", name="nnv_form_populate_choices")
     */
    public function populateChoices($from, $to, Request $req)
    {
        $data = $this->get('nnv.form.choice_populator')->populate($from, $to, $req->query->get('from'));
        return $this->json([
            'ok' => 1,
            'result' => $data
        ]);
    }
}