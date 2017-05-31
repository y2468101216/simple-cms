<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const COMPELETE = 1;
    
    const CREDITCARD = 1;

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
