<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public function user(){return $this->belongsTo(User::class);}
    public function article(){return $this->belongsTo(Article::class);}
}
