<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartBackup extends Model
{
    use HasFactory;

    protected $table = 'cart_backups';

    protected $fillable = [
        'user_id',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
