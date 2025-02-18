<?php

namespace App\Services\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Repository
{
    protected array $whereLike=[];
    protected array $whereStrict=[];
    protected int $pagination=10;

    protected string $model;

    public function __construct()
    {
        $this->initModel();
    }

    abstract protected function initModel(): void;

    protected function baseQuery(): Builder
    {
        return $this->setWith( $this->model::query() );
    }

    protected function setWith(Builder $query): Builder
    {
        return $query;
    }

    protected function getModelInstance(int|Model $idOrModel): ?Model
    {
        return is_int($idOrModel) ? $this->findById($idOrModel) : $idOrModel;
    }

    protected function saveModel(Model $model, array $data): Model
    {
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function getAll()
    {
        return $this->model::all();
    }

    public function findById(int $id): ?Model
    {
        return $this->model::find($id);
    }

    public function create(array $data): Model
    {
        $item = new $this->model();
        return $this->saveModel($item, $data);
    }

    public function update(int|Model $id, array $data): ?Model
    {
        $item = $this->getModelInstance($id);
        return $item ? $this->saveModel($item, $data) : null;
    }

    public function delete(int|Model $id)
    {
        return $this->getModelInstance($id)?->delete();
    }

    protected function getPagination(): int
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

    protected function addFilters(Builder $query, array $filters): Builder
    {
        $query = $this->addWhereLikeFilter($query, $filters);
        $query = $this->addWhereStrictFilter($query, $filters);
        return $query;
    }

    protected function addWhereLikeFilter(Builder $query, array $filters): Builder
    {
        foreach ($this->getWhereLike() as $option){
            if (!empty($filters[$option])) {
                $query->where($option, 'like', '%' . $filters[$option] . '%');
            }
        }
        return $query;
    }

    protected function addWhereStrictFilter(Builder $query, array $filters): Builder
    {
        foreach ($this->getWhereStrict() as $option){
            if (!empty($filters[$option])) {
                $query->where($option,  $filters[$option]);
            }
        }
        return $query;
    }

    public function getFilteredItems(array $filters): Builder
    {
        return $this->addFilters($this->baseQuery(), $filters);
    }

    public function getFilteredPaginateItems(array $filters): LengthAwarePaginator
    {
        return $this->getFilteredItems($filters)
            ->paginate($this->getPagination())
            ->withQueryString()
            ;
    }
}
