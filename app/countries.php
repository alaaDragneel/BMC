<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class countries extends Model
{
  protected $fillable = ['name'];
  protected $table = 'countries';
}
