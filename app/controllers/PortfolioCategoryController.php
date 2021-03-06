<?php

use CMS\Services\TagsService;

class PortfolioCategoryController extends \BaseController {

	/**
     * Display a listing of Portfolio categories 
     *
     * @return Response
     */
	 
	public function __construct(Portfolio $portfolio = null, TagsService $tagsService = null)
    {
        parent::__construct();
        $this->beforeFilter("auth", array('only' => ['adminIndex', 'create', 'delete', 'edit', 'update', 'store']));
        $this->portfolio = ($portfolio == null) ? new Portfolio : $portfolio;
        $this->tags = $tagsService;
    }

	public function adminIndex()
    {
		
		$categories = Portfolio_Category::orderBy('sort_order', 'asc')->get();
		return View::make('portfolio_category.admin_index', compact('categories'));
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		parent::show();
		return View::make('portfolio_category.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$all = Input::all();
        $rules = Portfolio_Category::$rules;
        $validator = $this->validateSlugOnCreatePortfolioCategory($all, $rules);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Portfolio_Category::create($all);

        return Redirect::route('portfolio_categories');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($portfolio_category = NULL)
	{
		if(is_numeric($portfolio_category)) {
			$portfolio_category = Portfolio_Category::find($portfolio_category);
        }
        if($portfolio_category == NULL){
            return View::make('404', compact('settings'));
        }
		//$portfolio_category = Portfolio_Category::where("slug", 'LIKE', '/' . $portfolio_category)->first();
		$projects = Project::where('project_category',$portfolio_category->id)->orderBy('order','asc')->where('published', '=', 1)->get();
		$cat_slug = str_replace('/','',$portfolio_category->slug);
		foreach($this->top_left_nav as $item){
			if( isset($item['menu_parent']) && $item['menu_parent'] == 0 ){
				$portfolio_category_id_list = explode(",", $item['portfolio_category_id']);
				if(in_array($portfolio_category->id, $portfolio_category_id_list)){
					$page_slug = str_replace('/','',$item['slug']);
					break;
				}
			}			
			
		}
		return View::make('portfolio_category.categoriesIndex_dark', compact('projects','page_slug', 'cat_slug', 'portfolio_category'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
	public function edit($id)
	{
		parent::show();
        $categories = Portfolio_Category::find($id);

        return View::make('portfolio_category.edit', compact('categories'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$Portfolio_Category = Portfolio_Category::findOrFail($id);
        $messages = [];
        //1. see if the slug is the same as the original
        //2. if it is then we will not validate against right
        $all = Input::all();
        $rules = Portfolio_Category::$rules;

        $validator = $this->validateSlugEditPortfolioCategory($all, $Portfolio_Category, $rules);
        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }
		$Portfolio_Category->is_active = (isset($all['isactive'])) ? 1 : 0;
        $Portfolio_Category->update($all);

        return Redirect::route('portfolio_categories');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Project::where('project_category', $id)->update(['project_category' => 0]);
		Page::where('portfolio_category_id', $id)->update(['portfolio_category_id' => 0]);
		Portfolio_Category::destroy($id);		
        return Redirect::route('portfolio_categories');
	}
	
	private function validateSlugOnCreatePortfolioCategory($all, $rules) {
		$messages  = array(
            'slug.unique' => 'The url is not unique .',
            'slug.regex'  => 'The url must start with a slash and contain only letters and numbers, no spaces.'
        );
        $validator = Validator::make($data = Input::all(), $rules, $messages);

        return $validator;
    }
	
	private function validateSlugEditPortfolioCategory($all, $model, $rules) {
        $messages = [];
		
		if (isset($all['slug']) && $all['slug'] != $model->slug) {
			$messages = array(
                'slug.unique' => 'The url is not unique.',
                'slug.regex'  => 'The url must start with a slash and contain only letters and numbers, no spaces.'
            );
        }
        else {
            unset($rules['slug']);
        }
        $validator = Validator::make($data = Input::all(), $rules, $messages);

        return $validator;
    }

}
