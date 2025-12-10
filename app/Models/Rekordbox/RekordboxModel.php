<?php

namespace App\Models\Rekordbox;

use Illuminate\Database\Eloquent\Model;

abstract class RekordboxModel extends Model
{
    protected $connection = 'rekordbox';
}
