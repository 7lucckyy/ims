<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeansOfMeasure extends Model
{
    use HasFactory;

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }
}
