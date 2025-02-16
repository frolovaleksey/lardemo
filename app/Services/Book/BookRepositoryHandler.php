<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Services\File\BookFile;
use App\Services\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BookRepositoryHandler extends Repository implements BookRepository
{
    protected array $whereLike = [
        'title'
    ];

    protected array $whereStrict = [
        'id'
    ];

    protected function initModel(): void
    {
        $this->model = Book::class;
    }

    protected function setWith(Builder $query): Builder
    {
        $query->with('authors');
        return $query;
    }

    public function create(array $data): Model
    {
        if (isset($data['image'])) {
            $file = app(BookFile::class);
            $data['image_url'] = $file::storeUploadedFile($data['image']);
        }

        $model = parent::create($data);

        $model->authors()->sync($data['authors']);

        return $model;
    }

    public function update(int|Model $id, array $data): ?Model
    {
        if (isset($data['image'])) {
            $file = app(BookFile::class);
            $data['image_url'] = $file::refreshUploadedFile($this->getModelInstance($id), $data['image']);
        }

        $model = parent::update($id, $data);

        $model->authors()->sync($data['authors']);

        return $model;
    }

    public function findById(int $id): ?Model
    {
        return $this->model::where('id', $id)->with('authors')->first();
    }
}
