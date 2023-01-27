<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    //Use to resolve the model and get the uuid
    public function getRouteKeyName()
    {
       
        return 'uuid';
    }

    public function user(){
      return  $this->belongsTo(User::class);
    }
}
