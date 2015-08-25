<?php

use CMS\Services\TagsService;

class PortfoliosController extends \BaseController {

    public $portfolio;
    private $tags;

    public function __construct(Portfolio $portfolio = NULL, TagsService $tagsService = NULL) {
        parent::__construct();
        $this->beforeFilter("auth", array('only' => ['adminIndex', 'create', 'delete', 'edit', 'update', 'store']));
        $this->portfolio = ($portfolio == NULL) ? new Portfolio : $portfolio;
        $this->tags      = $tagsService;
    }


    /**
     * Display a listing of portfolios
     *
     * @return Response
     */
    public function index() {
        parent::show();
        $portfolios = Portfolio::Published()->OrderByOrder()->get();

        return View::make('portfolios.index', compact('portfolios'));
    }

    /**
     * Display a listing of projects optionally grouped by tag
     *
     * @return Response
     */
    public function projectsIndex() {
        parent::show();
        $projects = Project::where('published', '=', 1)->orderBy('order')->get();
        $tags     = $this->tags->get_tags_for_type('Project');

        return View::make('portfolios.projectsIndex', compact('projects', 'tags'));
    }

    /**
     * Display a listing of portfolios
     *
     * @return Response
     */
    public function adminIndex($portfolio = NULL) {
        parent::show();
        $portfolios = Portfolio::OrderByOrder()->get();

        return View::make('portfolios.admin_index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new portfolio
     *
     * @return Response
     */
    public function create() {
        parent::show();

        return View::make('portfolios.create');
    }

    /**
     * Store a newly created portfolio in storage.
     *
     * @return Response
     */
    public function store() {
        $all       = Input::all();
        $rules     = Portfolio::$rules;
        $validator = $this->validateSlugOnCreate($all, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Portfolio::create($all);

        return Redirect::route('admin_portfolio');
    }

    /**
     * Display the specified portfolio.
     *
     * @param  int $id
     * @return Response
     */
    public function show($portfolio = NULL) {
        parent::show();
        if (is_numeric($portfolio)) {
            $portfolio = Portfolio::find($portfolio);
        }

        if ($portfolio == NULL) {
            return View::make('404', compact('settings'));
        }


        $seo    = $portfolio->seo;
        $banner = TRUE;

        return View::make('portfolios.show', compact('portfolio', 'banner', 'settings', 'seo'));
    }

    /**
     * Show the form for editing the specified portfolio.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id = NULL) {
        parent::show();
        $portfolio = Portfolio::find($id);

        return View::make('portfolios.edit', compact('portfolio'));
    }

    /**
     * Update the specified portfolio in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id) {
        $portfolio = Portfolio::findOrFail($id);
        $messages  = [];
        //1. see if the slug is the same as the original
        //2. if it is then we will not validate against right
        $all   = Input::all();
        $rules = Portfolio::$rules;

        $validator = $this->validateSlugEdit($all, $portfolio, $rules);
        $data      = $this->checkPublished($all);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }


        $newOrder = (int) $data['order'];
        $oldOrder = (int) $portfolio->order;
        $id       = $portfolio->id;

        $portfolio->update($data);


        if ($newOrder != $oldOrder) {
            $this->updateSortOrders($newOrder, $oldOrder, $id);
        }

        return Redirect::route('admin_portfolio');
    }

    protected function updateSortOrders($newOrder, $oldOrder, $id) {
        // see http://stackoverflow.com/questions/8607998/using-a-sort-order-column-in-a-database-table
//--Moving down chain
        if ($newOrder < $oldOrder) {
            $portfolios = Portfolio::whereBetween('order', [$newOrder, $oldOrder - 1])->get();
            foreach ($portfolios as $portfolio) {
                if ($portfolio->id != $id) {
                    $portfolio->order = $portfolio->order + 1;
                    $portfolio->save();
                }
            }
        }

//--Moving up chain
        if ($newOrder > $oldOrder) {
            $portfolios = Portfolio::whereBetween('order', [$oldOrder + 1, $newOrder])->get();
            foreach ($portfolios as $portfolio) {
                if ($portfolio->id != $id) {
                    $portfolio->order = $portfolio->order - 1;
                    $portfolio->save();
                }
            }
        }


    }

    /**
     * Remove the specified portfolio from storage.
     *
     * @param  int $id
     * @return Response
     */
    public
    function destroy($id) {
        Portfolio::destroy($id);

        return Redirect::route('portfolios.index');
    }

}
