<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Service\Cache\CacheAware;
use App\Service\Cache\CacheServiceInterface;
use App\Service\Search\QueryExecutor\LocationSearchQueryExecutor;
use App\Service\Search\SearchResultLocationExtractor;
use App\Tree\LocationTreeBuilder;
use App\Tree\Values\MenuItem;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

final class MenuGenerator implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait, CacheAware;

    const CACHE_KEY_MENU = 'app_professionals_listing_menu';

    /**
     * @return MenuItem[]
     */
    public function generate(string $pathString, int $rootLocationId): array
    {
        $cache = $this->container->get(CacheServiceInterface::class);

        $item = $cache->getItem(self::CACHE_KEY_MENU);

        if ($item->isHit()) {
            return $item->get();
        }

        $locationSearchResults = $this->container->get(LocationSearchQueryExecutor::class)->getResults($pathString);
        $menuItems = SearchResultLocationExtractor::extract($locationSearchResults);
        $menu = LocationTreeBuilder::build($menuItems, $rootLocationId);

        $item->expiresAfter((int) $cache->getCacheExpirationTime());
        $item->set($menu);
        $item->tag('location-'.$rootLocationId);

        $cache->save($item);

        return $menu;
    }

    public static function getSubscribedServices()
    {
        return [
            LocationSearchQueryExecutor::class,
            CacheServiceInterface::class
        ];
    }
}
