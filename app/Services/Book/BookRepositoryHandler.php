<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Services\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
            $path = $data['image']->store('book_images', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $model = parent::create($data);

        $model->authors()->sync($data['authors']);

        return $model;
    }
}
