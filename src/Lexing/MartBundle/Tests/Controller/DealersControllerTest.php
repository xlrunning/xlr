<?php

namespace Lexing\MartBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DealersControllerTest extends WebTestCase
{
    public function testDealerlist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/dealerList');
    }

}
