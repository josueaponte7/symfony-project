<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h2', 'Conferences');
    }

    public function testCommentSubmission()
    {
        $client = static::createClient();
        $client->request('GET', '/conference/amsterdam-2019');
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Fabien',
            'comment_form[text]' => 'Some feedback from an automated functional test',
            'comment_form[email]' => 'me@automat.ed',
            'comment_form[photoFilename]' => dirname(__DIR__, 2).'/public/images/under-construction.gif',
        ]);
        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertSelectorExists('div:contains("There are 2 comments")');
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
