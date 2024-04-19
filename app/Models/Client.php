<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'photo',
        'linkedin',
        'active',
        'title',
        'company',
        'role',
        'company_website',
        'business_details',
        'business_type',
        'company_size',
        'temperature',
        'notes',
        'referrals',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name} - {$this->company}";
    }
}
