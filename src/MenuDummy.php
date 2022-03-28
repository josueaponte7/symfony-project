<?php

namespace App;

use Symfony\Contracts\Cache\CacheInterface;

class MenuDummy
{
    public function __construct(private CacheInterface $cache)
    {
    }

    public function getMenuFromService()
    {
        return $this->cache->get('app.menu', function () {
            $data = [
                'item1',
                'item2',
                'item3',
            ];

            sleep(5);

            return $data;
        });
    }
}
