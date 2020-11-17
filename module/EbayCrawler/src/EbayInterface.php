<?php

namespace EbayCrawler;

interface EbayInterface
{
    public const SEARCH_URI = '/sch/i.html';

    public const COUNTRY_UK = 'uk';
    public const COUNTRY_DE = 'de';

    public const AVAILABLE_COUNTRIES = [
        self::COUNTRY_DE,
        self::COUNTRY_UK
    ];

    public const DOMAIN_DE = 'de';
    public const DOMAIN_UK = 'co.uk';

    public const AVAILABLE_DOMAINS = [
        self::DOMAIN_DE,
        self::DOMAIN_UK
    ];

    public const COUNTRY_DOMAIN_MAP = [
        self::COUNTRY_DE => self::DOMAIN_DE,
        self::COUNTRY_UK => self::DOMAIN_UK
    ];

    public const PARAM_KEYWORD = '_nkw';
    public const PARAM_DELIVERY_LOCATION = '_fcid';
    public const PARAM_SORT = '_sop';
    public const PARAM_ITEMS_PER_PAGE = '_ipg';

    public const AVAILABLE_PARAMS = [
        self::PARAM_SORT,
        self::PARAM_DELIVERY_LOCATION,
        self::PARAM_SORT,
        self::PARAM_KEYWORD,
        self::PARAM_ITEMS_PER_PAGE
    ];

    public const SORT_BEST_MATCH = 12;
    public const SORT_LOWEST_PRICE = 2;
    public const SORT_LOWEST_PRICE_PP = 15;
    public const SORT_HIGHEST_PRICE = 3;
    public const SORT_HIGHEST_PRICE_PP = 16;
    public const SORT_NEWLY_LISTED = 10;
    public const SORT_ENDING_SOONEST = 1;
    public const SORT_NEAREST_FIRST = 7;

    public const AVAILABLE_SORT = [
        self::SORT_BEST_MATCH,
        self::SORT_LOWEST_PRICE,
        self::SORT_LOWEST_PRICE_PP,
        self::SORT_HIGHEST_PRICE,
        self::SORT_HIGHEST_PRICE_PP,
        self::SORT_NEWLY_LISTED,
        self::SORT_ENDING_SOONEST,
        self::SORT_NEAREST_FIRST
    ];

    public const ITEMS_PER_PAGE_25 = 25;
    public const ITEMS_PER_PAGE_50 = 50;
    public const ITEMS_PER_PAGE_100 = 100;
    public const ITEMS_PER_PAGE_200 = 200;

    public const AVAILABLE_ITEMS_PER_PAGE = [
        self::ITEMS_PER_PAGE_25,
        self::ITEMS_PER_PAGE_50,
        self::ITEMS_PER_PAGE_100,
        self::ITEMS_PER_PAGE_200,
    ];

    public const DELIVERY_LOCATION_DE = 77;
    public const DELIVERY_LOCATION_UK = 3;

    public const AVAILABLE_DELIVERY_LOCATIONS = [
        self::DELIVERY_LOCATION_DE,
        self::DELIVERY_LOCATION_UK,
    ];
}
