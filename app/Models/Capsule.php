<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capsule extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'token_hash', 'opens_at'];

    protected $hidden = ['body', 'token_hash'];

    protected function casts(): array
    {
        return ['title' => 'encrypted', 'body' => 'encrypted', 'opens_at' => 'immutable_datetime'];
    }
}
