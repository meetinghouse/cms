<?php

namespace App\Http\Controllers;

use App\Portfolio;
use App\Project;
use CMS\Services\TagsService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;



class PortfoliosController extends Controller
{

    public $portfolio;
    private $tags;

    public function __construct(Portfolio $portfolio = null, TagsService $tagsService = null)
    {
        parent::__construct();
        $this->beforeFilter("auth", ['only' => ['adminIndex', 'create', 'delete', 'edit', 'update', 'store']]);
        $this->portfolio = ($portfolio == null) ? new Portfolio : $portfolio;
        $this->tags = $tagsService;
    }


    /**
     * Display a listing of portfolios
     *
     * @return Response
     */
    public function index()
    {
        parent::show();
        $portfolios = Portfolio::Published()->OrderByOrder()->get();

        return view('portfolios.index', compact('portfolios'));
    }

    /**
     * Display a listing of projects optionally grouped by tag
     *
     * @return Response
     */
    public function projectsIndex()
    {
        parent::show();
        $projects = Project::where('published', '=', 1)->orderBy('order')->get();
        $tags = $this->tags->get_tags_for_type('Project');
        if ($this->settings->theme == true) {
            return view('portfolios.projectsIndex_dark', compact('projects', 'tags'));
        } else {
            return view('portfolios.projectsIndex', compact('projects', 'tags'));
        }
    }

    /**
     * Display a listing of portfolios
     *
     * @return Response
     */
    public function adminIndex($portfolio = null)
    {
        parent::show();
        $portfolios = Portfolio::OrderByOrder()->get();

        return view('portfolios.admin_index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new portfolio
     *
     * @return Response
     */
    public function create()
    {
        parent::show();
        return view('portfolios.create');
    }

    /**
     * Store a newly created portfolio in storage.
     *
     * @return Response
     */
    public function store()
    {
        $all = Input::all();
        $rules = Portfolio::$rules;
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
     * @param  int  $id
     * @return Response
     */
    public function show($portfolio = null)
    {
        parent::show();
        if (is_numeric($portfolio)) {
            $portfolio = Portfolio::find($portfolio);
        }

        if ($portfolio == null) {
            return view('404', compact('settings'));
        }


        $seo = $portfolio->seo;
        $banner = true;
        return view('portfolios.show', compact('portfolio', 'banner', 'settings', 'seo'));
    }

    /**
     * Show the form for editing the specified portfolio.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id = null)
    {
        parent::show();
        $portfolio = Portfolio::find($id);

        return view('portfolios.edit', compact('portfolio'));
    }

    /**
     * Update the specified portfolio in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $messages = [];
        //1. see if the slug is the same as the original
        //2. if it is then we will not validate against right
        $all = Input::all();
        $rules = Portfolio::$rules;

        $validator = $this->validateSlugEdit($all, $portfolio, $rules);
        $data = $this->checkPublished($all);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $portfolio->update($data);

        return Redirect::route('admin_portfolio');
    }

    /**
     * Remove the specified portfolio from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Portfolio::destroy($id);

        return Redirect::route('portfolios.index');
    }
}
