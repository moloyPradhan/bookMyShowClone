<?php

namespace App\Models;

use App\Entities\Movie;
use App\Traits\HasUUID;
use CodeIgniter\Model;

class MovieModel extends Model
{
    use HasUUID;

    protected $table = 'movies';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = Movie::class;

    protected $allowedFields = [

        'id',

        'title',
        'slug',
        'description',

        'duration_minutes',

        'language',
        'genre',

        'release_date',

        'poster_url',
        'banner_url',
        'trailer_url',

        'censor_rating',

        'status',
    ];

    protected $useTimestamps = true;

    protected $beforeInsert = [
        'generateUUID',
    ];
}
