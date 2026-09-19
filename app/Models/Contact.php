<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    
  protected $table = 'contactus';
    protected $fillable = [
        'name', 'email', 'phone_number', 'subject','message','title','type'
    ];
}
?>