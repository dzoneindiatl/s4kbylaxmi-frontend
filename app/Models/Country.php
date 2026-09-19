<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    public $table = 'countries';
    public function user_address(){
        return $this->belongsTo(UserAddress::class,'id','country_id');
    }
}
