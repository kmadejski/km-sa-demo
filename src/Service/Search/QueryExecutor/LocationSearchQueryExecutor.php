<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Service\Search\QueryExecutor;

use App\QueryType\MenuQueryType;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class LocationSearchQueryExecutor implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    const MENU_ITEM_LIMIT = 400;
    const MENU_CONTENT_TYPES = ['folder'];
    const MENU_CONTENT_DEPTH = 5;

    public function getResults($pathString): SearchResult
    {
        $query = $this->container->get(MenuQueryType::class)->getQuery([
            'path_string' => $pathString,
            'included_content_type_identifier' => self::MENU_CONTENT_TYPES,
            'depth' => self::MENU_CONTENT_DEPTH
        ]);

        $query->limit = self::MENU_ITEM_LIMIT;

        return $this->container->get(SearchService::class)->findLocations($query);
    }

    public static function getSubscribedServices()
    {
        return [
            SearchService::class,
            MenuQueryType::class,
        ];
    }
}
