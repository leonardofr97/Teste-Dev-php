<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'document',
        'email',
        'phone',
        'zip_code',
        'street',
        'district',
        'city',
        'state'
    ];
}
