<?php

namespace App\Http\Controllers;

use App\Page;
use App\Project;
use CMS\Services\ImagesService;
use CMS\Services\TagsService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use JavaScript;

class PagesController extends Controller
{

    public $pages;

    public function __construct(Page $pages = null, TagsService $tagsService = null, ImagesService $imagesService = null)
    {
        parent::__construct();
        $this->beforeFilter("auth", ['only' => ['index', 'create', 'delete', 'edit', 'update', 'store']]);
        $this->pages = ($pages == null) ? new Page : $pages;
        $this->tags = $tagsService;
        $this->imagesService = $imagesService;
    }
        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
    public function index()
    {
        parent::show();
        $pages = $this->pages->all();
        $banner = $this->banner;
        if ($this->settings->theme == true) {
            return $this->respond($pages, 'pages.index_dark', compact('pages', 'banner'));
        } else {
            return $this->respond($pages, 'pages.index', compact('pages', 'banner'));
        }
    }

    public function adminIndex()
    {
        parent::show();
        $pages = $this->pages->all();
        // print_r($pages);
        // dd($pages);
        $banner = $this->banner;
        return view('pages.admin_index', compact('pages', 'settings'));
    }

        /**
         * Show the form for creating a new resource.
         *
         * @return Response
         */
    public function create()
    {
        // Added from Andy's code example
        parent::show();
            
        // this is a way of creating a view
        // the 'pages.create' parameter references the pages folder (app/views/pages)
        // and the create.blade.php file (create) in the pages folder
        $subnavparents = Page::getAllSubNavParents();
        return view('pages.create', compact('subnavparents'));
    }

        /**
         * Display the specified resource.
         *
         * @return Response
         */
    public function show($page = null)
    {
        parent::show();
        parent::getSlides();
        if (is_numeric($page)) {
            $page = Page::find($page);
        }
        if ($page == null) {
            return view('404', compact('settings'));
        }
        $projects = Project::orderBy('id', 'asc')->paginate(20);
        $seo = $page->seo;
        $banner = true;
        $page->id == 4 ? $tags = $this->getTags() : $tags = null;
        $this->settings->pageId = $page->id;
        $this->settings->menu_name = $page->menu_name;
        $page->id == 1 ? JavaScript::put(['home'=>'home']) : JavaScript::put(['home'=>'notHome']);
        if ($this->settings->theme == true) {
            return view('pages.show_dark', compact('page', 'banner', 'settings', 'seo', 'projects', 'tags'));
        } else {
            return view('pages.show', compact('page', 'banner', 'settings', 'seo', 'projects', 'tags'));
        }
    }

    public function getTags()
    {
        if ($this->tags == null) {
            $this->tags =  $this->setTags();
        }
        $tags = $this->tags->get_tags_for_type('Project');
        return  $tags;
    }

    private function setTags()
    {
        return new TagsService;
    }


        /**
         * Store a newly created resource in storage.
         *
         * @return Response
         */
    public function store()
    {
        
        // Added from Andy's code example
        $input = Input::all();
      //  die($input);
        $rules = Page::$rules;
        // print_r($rules);
                   
        $validator = Validator::make($input, ['slug' => 'regex:/^\/[A-Za-z0-9_\-]+$/']);
                                                 
        if ($validator->passes()) {
         // print_r(array_values($input));
         // die("in validator test") ;
            if (!Input::get('published')) {
                         $input['published'] = 0;
            }
            
            if (!Input::get('enable_menu')) {
                $input['menu_sort_order'] = 0;
                $input['menu_name'] = '';
                $input['menu_parent'] = 0;
            } else {
                if ($input['menu_name'] == 'top,left_side') {
                    $input['menu_parent'] = 0;
                }
            }
            
            $page = Page::create($input);
            $banner = $this->bannerSet($page);
         //  return redirect('pages.admin_index'->withMessage("Created Page");
            return redirect('pages/' . $page->id)->withMessage("Page Created ");
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }
    
        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return Response
         */
    public function edit($id = null)
    {
        parent::show();
        $page = Page::findOrFail($id);
        parent::checkForSlideshow($page->id);
        $subnavparents = Page::getAllSubNavParents();
        return view('pages.edit', compact('page', 'settings', 'slideshow', 'subnavparents'));
    }


        /**
         * Update the specified resource in storage.
         *
         * @param  int  $id
         * @return Response
         */
    public function update($id)
    {
        $page_update = Input::all();
//        dd($page_update);
        if ($this->settings->theme != true) {
            $validator = Validator::make($page_update, ['title' => 'required', 'slug' => 'regex:/^\/[A-Za-z0-9_\-]+$/']);
            $page = Page::find($id);
            if ($validator->passes()) {
                $page->title = $page_update['title'];
                $page->body = $page_update['body'];
                $page->seo = $page_update['seo'];
                $page->slug = (isset($page_update['slug'])) ?  $page_update['slug'] : $page->slug;
                if (isset($page_update['images'])) {
                    $this->imagesService->addImages($page->id, $page_update['images'], 'Page');
                }
                if (!Input::get('enable_menu')) {
                    $page->menu_sort_order = 0;
                    $page->menu_name = '';
                    $page->menu_parent = 0;
                } else {
                    $page->menu_sort_order = $page_update['menu_sort_order'];
                    $page->menu_name = $page_update['menu_name'];
                    $page->menu_parent = $page_update['menu_parent'];
                  
                  
                    if ($page_update['menu_name'] == 'top,left_side') {
                        $page->menu_parent = 0;
                    }
                }
                $page->hide_title = (isset($page_update['hide_title'])) ? true : false;
                $page->published = (isset($page_update['published'])) ? true : false;
                $page->save();
                $banner = $this->bannerSet($page);
                return redirect("/pages/")->withMessage("Page Updated");
            } else {
                return redirect('pages/' . $page->id . '/edit')->withErrors($validator)
                    ->withMessage("Error ");
            }
        } else {
            $validator = Validator::make($page_update, ['slug' => 'regex:/^\/[A-Za-z0-9_\-]+$/']);
            $page = Page::find($id);
            if ($validator->passes()) {
                $page->seo = $page_update['seo'];
                $page->title = $page_update['title'];
                $page->body = $page_update['body'];
                $page->slug = (isset($page_update['slug'])) ?  $page_update['slug'] : $page->slug;
                
                if (!Input::get('enable_menu')) {
                    $page->menu_sort_order = 0;
                    $page->menu_name = '';
                    $page->menu_parent = 0;
                } else {
                    $page->menu_sort_order = $page_update['menu_sort_order'];
                    $page->menu_name = $page_update['menu_name'];
                    $page->menu_parent = $page_update['menu_parent'];
                  
                  
                    if ($page_update['menu_name'] == 'top,left_side') {
                        $page->menu_parent = 0;
                    }
                }
                $page->hide_title = (isset($page_update['hide_title'])) ? true : false;
                $page->published = (isset($page_update['published'])) ? true : false;
                $page->save();
                $banner = $this->bannerSet($page);
                return redirect("/pages/")->withMessage("Page Updated");
            } else {
                return redirect('pages/' . $page->id . '/edit')->withErrors($validator)
                    ->withMessage("Error ");
            }
        }
    }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return Response
         */
    public function destroy($id)
    {
        Page::destroy($id);
        return Redirect::route('pages.index');
    }
}