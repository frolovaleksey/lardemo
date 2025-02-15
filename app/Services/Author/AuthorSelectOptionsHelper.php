<?php

namespace App\Services\Author;

use App\Models\Author;

class AuthorSelectOptionsHelper
{
    public static function all(): array
    {
        $result=[];
        foreach (Author::all() as $item){
            $result[] = [
                'value' => $item->id,
                'label' => $item->first_name,
            ];
        }
        return $result;
    }
}
