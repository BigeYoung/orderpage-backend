<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * 获取与订单`Order`相关联的产品`Product`信息。
     */
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'guid', 'product_guid');
    }
}
