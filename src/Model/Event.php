<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\Model;

final class Event
{
    private string $name;
    private array $data;

    public function __construct(string $name, array $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'event' => $this->name,
            'ecommerce' => $this->data
        ];
    }
}