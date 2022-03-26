<?php

namespace App\Service\Utils;

use App\Entity\Comment;

class SpamChecker
{
    public function __construct(private HttpClientInterface $httpClient, private string $akismetkey)
    {
    }

    public function getSpamScore(Comment $comment, array $context)
    {

        $endpoint = sprintf('https://%s.rest.akismet.com/1.1/comment-check', $this->akismetkey);
        $response = $this->httpClient->request('POST', $endpoint, [
            'body' => array_merge($context, [
                'blog' => 'https://example.com',
                'comment_type' => 'comment',
                'comment_author' => $comment->getAuthor(),
                'comment_author_email' => $comment->getEmail(),
                'comment_content' => $comment->getText(),
                'comment_dat_gtm' => $comment->getCreatedAt()->format('c'),
                'blog_lang' => 'en',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ]),
        ]);

        $headers = $response->getHeaders();
        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 2;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf('Unabled to checke for spam: %s (%s)', $content, $headers['x-akismet-debug-help'][0]));
        }
        // akismet-guaranteed-spam@example.com
        return 'true' === $content ? 1 : 0;
    }
}
