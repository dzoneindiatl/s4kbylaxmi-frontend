<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
  use HasFactory;
  public $table = 'refund_requests';
  protected $fillable = [
    'user_id',
    'order_id',
    'refund_reason',
    'refund_details',
    'account_number',
    'ifsc_code',
    'account_type',
    'bank_name',
    'order_item_id',
    'refund_mode'
  ];
}