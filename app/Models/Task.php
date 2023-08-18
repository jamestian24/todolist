<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_descr',
        'member_id'
    ];

    protected $casts = [
       'created_at' => 'datetime:Y-m-d H:i:s', // 轉換為 datetime 類型 ,
       'updated_at' => 'datetime:Y-m-d H:i:s', // 轉換為 datetime 類型
    ];

    public function member()
    {
        //belongsTo(類別名稱, 參照欄位, 主鍵)
        return $this->belongsTo('App\Models\member');
    }

}
