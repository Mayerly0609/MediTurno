<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accesorio extends Model
{
    protected $table = 'accesorio';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = ['id'];
}
