<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mock extends Model
{

   protected $table = 'mock_table';

   protected $fillable = [
      'id',
      'email',
      'title',
      'first_name',
      'last_name',
      'tz',
      'date',
      'time',
      'note',
      'ip_address',
      'domain_exists',
      'image_url'
   ];
}
