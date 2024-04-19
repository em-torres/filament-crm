<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'title',
        'client_id',
        'summary',
        'start',
        'end',
    ];

    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
