<?php

namespace App\Services;

class PaginatedService
{
    public function __construct(private array $data, private int $total, private int $limit, private int $page)
    {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
