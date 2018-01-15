<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
 	protected $fillable = [
        'bill_no', 'customer_name', 'contact', 'total', 'advance', 'balance'
    ];
}
