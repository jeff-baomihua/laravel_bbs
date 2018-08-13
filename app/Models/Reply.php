<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];//只允许用户修改content

    //一条回复属于一个话题
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    //一条回复属于一个作者所有
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
