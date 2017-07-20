<?php

namespace Lexing\MartBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehicleControllerTest extends WebTestCase
{
    public function testVehiclelist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/vehicleList');
    }

}
