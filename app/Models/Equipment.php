<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $primaryKey = 'guid';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /**
     * 获取与设备`Equipment`相关联的操作`Operation`信息。
     */
    public function operations()
    {
        return $this->hasMany('App\Models\Operation', 'equipment_guid', 'guid');
    }
}
