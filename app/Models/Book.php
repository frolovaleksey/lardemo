<?php

namespace App\Models;

use App\Services\Shop\OrderableItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model implements OrderableItem
{
    use HasFactory;
    protected $fillable=[
        'title',
        'price',
        'image_url',
    ];
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function getAmount(): int
    {
        return $this->price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this::class;
    }
}
