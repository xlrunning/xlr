<?php

namespace Lexing\VehicleBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testBrand()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/brand/view');
    }

}
