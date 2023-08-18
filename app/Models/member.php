<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group',
    ];

    public function Tasks()
    {
        //hasMany(類別名稱, 參照欄位, 主鍵)
        return $this->hasMany('App\Models\Task', 'member_id', 'id');
    }
}
