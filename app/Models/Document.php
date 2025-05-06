<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
        ];
    }

    public function modelable(): MorphTo
    {
        return $this->morphTo();
    }
}
