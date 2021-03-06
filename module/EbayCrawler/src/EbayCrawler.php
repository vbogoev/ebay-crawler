<?php

namespace EbayCrawler;

use EbayCrawler\Exception\UnsupportedCountryException;
use EbayCrawler\Exception\UnsupportedParamException;
use EbayCrawler\Exception\UnsupportedParamValueException;
use EbayCrawler\Model\EbayItemModel;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class EbayCrawler implements EbayInterface
{
    private ClientInterface $client;

    private string $country;

    private string $schema = 'https';

    private array $params = [];

    public const OBJECT_FILTERS = [
        'items' => '.srp-results .s-item__wrapper',
        'item' => [
            'link' => '.s-item__link',
            'title' => '.s-item__title',
            'image' => '.s-item__image-img',
            'price' => '.s-item__price',
            'shipping' => '.s-item__shipping',
        ]
    ];

    public function __construct(string $country = '', array $params = [])
    {
        if (!empty($params)) {
            $this->setParams($params);
        }

        if (!empty($country)) {
            $this->setCountry($country);
        }
    }

    private function getBaseUri(): string
    {
        return $this->schema . '://' .'ebay.' . self::COUNTRY_DOMAIN_MAP[$this->country] . self::SEARCH_URI;
    }

    private function getRequestUri(): string
    {
        return '?' . $this->getParamsForRequest();
    }

    private function getParamsForRequest(): string
    {
        return http_build_query($this->params);
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): self
    {
        $this->validateParams($params);

        $this->params = $params;

        return $this;
    }

    public function addParam(string $paramName, $paramValue): void
    {
        $paramArray = [$paramName => $paramValue];

        $this->validateParams($paramArray);

        $this->params = array_merge($this->params, $paramArray);
    }

    public function setCountry(string $country): void
    {
        $this->validateCountry($country);

        $this->country = $country;

        $this->client = new Client([
            'base_uri' => $this->getBaseUri()
        ]);
    }

    private function validateCountry(string $country): void
    {
        if (!in_array($country, self::AVAILABLE_COUNTRIES)) {
            throw new UnsupportedCountryException(sprintf('%s country is not supported.', $country));
        }
    }

    private function validateParams(array $params): void
    {
        foreach ($params as $paramName => $paramValue) {
            switch ($paramName)
            {
                case self::PARAM_KEYWORD:
                    if (empty($paramValue)) {
                        throw new UnsupportedParamValueException(sprintf('Keyword parameter can\'t be empty.'));
                    }
                    break;
                case self::PARAM_ITEMS_PER_PAGE:
                    if (!in_array($paramValue, self::AVAILABLE_ITEMS_PER_PAGE, false)) {
                        throw new UnsupportedParamValueException(sprintf('Items Per Page parameter is not supported.'));
                    }
                    break;
                case self::PARAM_SORT:
                    if (!in_array($paramValue, self::AVAILABLE_SORT, false)) {
                        throw new UnsupportedParamValueException(sprintf('Sort parameter is not supported.'));
                    }
                    break;
                case self::PARAM_DELIVERY_LOCATION:
                    if (!in_array($paramValue, self::AVAILABLE_DELIVERY_LOCATIONS, false)) {
                        throw new UnsupportedParamValueException(sprintf('Delivery Location parameter is not supported.'));
                    }
                    break;
                default:
                    throw new UnsupportedParamException(sprintf('%s parameter is not supported.', $paramName));
            }
        }
    }

    public function getItems(): array
    {
        $responseData = $this->client->request('GET', $this->getRequestUri());
        $domObject = new Crawler($responseData->getBody()->getContents());

        $itemsData = $domObject
            ->filter(self::OBJECT_FILTERS['items'])
            ->each(function (Crawler $node) {
                return $this->extractItemData($node);
            });

        $result = [];
        foreach ($itemsData as $itemData) {
            $model = new EbayItemModel();
            $model->hydrate($itemData);
            $result[] = $model;
        }

        return $result;
    }
    
    private function extractItemData(Crawler $item): array
    {
        $link = $item->filter(self::OBJECT_FILTERS['item']['link'])->attr('href');

        return [
            'id' => $this->extractItemIdFromLink($link),
            'link' => $link,
            'title' => $this->extractItemTitle($item->filter(self::OBJECT_FILTERS['item']['title'])),
            'image' => $item->filter(self::OBJECT_FILTERS['item']['image'])->attr('src'),
            'price' => $item->filter(self::OBJECT_FILTERS['item']['price'])->text('No Price Available'),
            'shipping' => $item->filter(self::OBJECT_FILTERS['item']['shipping'])->text('No Shipping Available'),
        ];
    }

    private function extractItemIdFromLink(string $link): int
    {
        preg_match('/\/(\d+)\?/', $link, $matches);
        return $matches[1];
    }

    private function extractItemTitle(Crawler $title): string
    {
        // Remove "New Listing" label inside title tag
        $title->filter('span')->each(function ($crawler) {
            foreach ($crawler as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        return $title->text();

    }
}
