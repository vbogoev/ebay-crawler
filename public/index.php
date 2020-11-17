<?php

use EbayCrawler\EbayCrawler;
use Symfony\Component\DomCrawler\Crawler;

chdir(dirname(__DIR__));

require __DIR__ . '/../vendor/autoload.php';

$countries = [
    EbayCrawler::COUNTRY_UK,
    EbayCrawler::COUNTRY_DE,
];

$keywords = [
    'depeche mode vinyl',
    'muse vinyl',
];

$ebayCrawler = new EbayCrawler();
$ebayCrawler->setParams([
    EbayCrawler::PARAM_SORT => EbayCrawler::SORT_NEWLY_LISTED,
    EbayCrawler::PARAM_DELIVERY_LOCATION => EbayCrawler::DELIVERY_LOCATION_UK,
    EbayCrawler::PARAM_ITEMS_PER_PAGE => EbayCrawler::ITEMS_PER_PAGE_25
]);

foreach ($countries as $country) {
    $ebayCrawler->setCountry($country);
    foreach ($keywords as $keyword) {
        $ebayCrawler->addParam(EbayCrawler::PARAM_KEYWORD, $keyword);

        $items = $ebayCrawler->getItems();

        echo '<pre>';
        print_r($items);
        echo '</pre>';
        exit;
    }
}
