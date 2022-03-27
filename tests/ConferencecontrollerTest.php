<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferencecontrollerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h2', 'Conferences');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(1, $crawler->filter('h4'));

        $client->clickLink('View');

        self::assertPageTitleContains('Amsterdam');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h2', 'Amsterdam 2019');
        self::assertSelectorExists('div:contains("There are 1 comments")');
    }
}
