<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_type',
        'result',
    ];

    protected int $userId;
    protected string $fileType;
    protected string $result;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
