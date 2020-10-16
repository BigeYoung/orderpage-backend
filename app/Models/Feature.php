<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    /**
     * 获取与这个特征`Feature`相关联的产品`Product`信息。
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_guid', 'guid');
    }
}
