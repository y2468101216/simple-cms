<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function orderProducts()
    {
        return $this->hasMany('App\Model\OrderProduct');
    }

    public function getNextId()
    {
        $schema = \DB::table('INFORMATION_SCHEMA.TABLES')
        ->select('AUTO_INCREMENT as next_id')
        ->where('TABLE_SCHEMA', env('DB_DATABASE'))
        ->where('TABLE_NAME', 'orders')
        ->first();

        return $schema->next_id;
    }
}
