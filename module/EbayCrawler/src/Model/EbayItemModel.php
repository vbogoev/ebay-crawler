<?php

namespace EbayCrawler\Model;

class EbayItemModel
{
    private int $id;
    private string $link;
    private string $title;
    private string $image;
    private string $price;
    private string $shipping;

    private array $fillable = [
        'id',
        'link',
        'title',
        'image',
        'price',
        'shipping'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): EbayItemModel
    {
        $this->id = $id;
        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): EbayItemModel
    {
        $this->link = $link;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): EbayItemModel
    {
        $this->title = $title;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): EbayItemModel
    {
        $this->image = $image;
        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): EbayItemModel
    {
        $this->price = $price;
        return $this;
    }

    public function getShipping(): string
    {
        return $this->shipping;
    }

    public function setShipping(string $shipping): EbayItemModel
    {
        $this->shipping = $shipping;
        return $this;
    }

    public function hydrate(array $data): void
    {
        foreach ($data as $itemName => $itemValue) {
            if (in_array($itemName, $this->fillable, true)) {
                $this->{'set' . $itemName}($itemValue);
            }
        }
    }
}
