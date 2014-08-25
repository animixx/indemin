<?php

namespace Eye3\ControlBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class estadisticasControllerTest extends WebTestCase
{
    public function testTiempo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tiempo');
    }

    public function testCamion()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/camion');
    }

    public function testGrua()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/grua');
    }

}
