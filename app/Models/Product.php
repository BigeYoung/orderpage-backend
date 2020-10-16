<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'guid';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /**
     * 获取与产品`Product`相关联的订单`Order`信息。
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
    
    /**
     * 获取与产品`Product`相关联的特征`Fearure`信息。
     */
    public function features()
    {
        return $this->hasMany('App\Models\Feature', 'product_guid', 'guid');
    }

    /**
     * 获取与产品`Product`相关联的操作`Operation`信息。
     */
    public function operations()
    {
        return $this->hasMany('App\Models\Operation', 'product_guid', 'guid');
    }
}
