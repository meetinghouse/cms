<?php

namespace App;

class Portfolio extends BaseModel
{

    public static $rules = [
	'category_id' => 'required',
    'title' => 'required',
    'body'  => 'required',
    'slug'  => 'required|unique:portfolios|unique:posts|unique:pages|unique:projects|unique:portfolio_category|regex:/^\/[A-Za-z0-9_]+$/'
  ];


    protected $fillable = ['title', 'published', 'body', 'slug', 'order', 'seo', 'header'];

    public function projects()
    {
        return $this->hasMany('App\Project')->Published()->OrderByOrder();
    }

    public static function allPortfoliosSelectOptions()
    {
        $ports = self::all();
        $options = [];
        foreach ($ports as $port) {
            $options[$port->id] = $port->title;
        }
        return $options;
    }
}
