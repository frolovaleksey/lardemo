<?php

namespace App\Services\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Repository
{
    protected array $whereLike=[];
    protected array $whereStrict=[];
    protected int $pagination=10;

    abstract protected function baseQuery(): Builder;

    public function getPagination(): int
    {
        return $this->pagination;
    }

    public function setPagination(int $pagination): self
    {
        $this->pagination = $pagination;
        return $this;
    }

    protected function getWhereLike(): array
    {
        return $this->whereLike;
    }

    protected function getWhereStrict(): array
    {
        return $this->whereStrict;
    }

    public function getFilteredItems(array $filters): LengthAwarePaginator
    {
        $query = $this->baseQuery();

        foreach ($this->getWhereLike() as $option){
            if (!empty($filters[$option])) {
                $query->where($option, 'like', '%' . $filters[$option] . '%');
            }
        }

        foreach ($this->getWhereStrict() as $option){
            if (!empty($filters[$option])) {
                $query->where($option,  $filters[$option]);
            }
        }

        return $query->paginate( $this->getPagination() )->withQueryString();
    }
}
