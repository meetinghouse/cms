<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['name', 'active', 'order', 'banner_name'];
    public static $rules = ['banner_name' => ['required'], 'name' => ['required']];
    public static $rules_update = ['name' => ['required']];

    public static function slideShow()
    {
        $banners =  Banner::where("active", "=", 1)->orderBy('order')->get();
        return $banners;
    }
}
