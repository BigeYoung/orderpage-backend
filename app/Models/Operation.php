<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    /**
     * 获取与这个操作`Operation`相关联的订单`Equipment`信息。
     */
    public function equipment()
    {
        return $this->belongsTo('App\Models\Equipment', 'equipment_guid', 'guid');
    }
    
    /**
     * 获取与这个操作`Operation`相关联的产品`Product`信息。
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_guid', 'guid');
    }
    
}
