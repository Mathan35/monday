<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $fillable = [
        'data',
    ];

        /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
    ];
=======
    protected $fillable = ['data'];

    protected $casts = ['data' => 'array'];
>>>>>>> 9fad4a3310e9e7588588e6f06f5fe107919e9353
}
