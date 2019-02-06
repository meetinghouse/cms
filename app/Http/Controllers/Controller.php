<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Setting;
use App\Portfolio;
use App\Page;
use Symfony\Component\Filesystem\Filesystem;
use Request, View, File, JavaScript;
use CMS\Services\TagsService;
use Validator,Input, Response;
abstract class Controller extends BaseController
{

    use DispatchesCommands, ValidatesRequests;

    protected $filesystem;
    public $settings;
    public $portfolio;
    public $top_left_nav;
    public $sub_nav;
    protected $banner = false;

    public function __construct(
        Setting $settings = null,
        Portfolio $portfolio = null,
        Filesystem $filesystem = null,
        Page $top_left_nav = null,
        Page $sub_nav = null
    ) {
        $this->settings   = ($settings == null) ? Setting::first() : $settings;
        $this->portfolio  = ($portfolio == null) ? Portfolio::all() : $portfolio;
        $this->filesystem = ($filesystem == null) ? new Filesystem : $filesystem;
        /* Calculating nav*/
        if (Request::method('get')) {
            $this->top_left_nav = ($top_left_nav == null) ? Page::getAllSubNavParents() : $top_left_nav;

            $slug = "/".Request::path();

            $node = Page::where('slug', '=', $slug)->first();
          // If slug points to a sub_nav's parent
            if (isset($node) && $node->menu_name == 'top,left_side') {
                $this->sub_nav = Page::getSubNavSorted($node->id);
            }

          // If slug points to a sub_nav
            if (isset($node) && $node->menu_name == 'sub_nav') {
                $this->sub_nav = Page::getSubNavSorted($node->menu_parent);
            }
        }
        /* End of Calculating nav*/

        \View::share('settings', $this->settings);
        \View::share('top_left_nav', $this->top_left_nav);
        \View::share('sub_nav', $this->sub_nav);
    }
    public function show($array = null)
    {
        $portfolios      = Portfolio::published()->orderByOrder()->get();
        $portfolio_links = [];
        if ($this->settings->theme == false) {
            if ($portfolios) {
                foreach ($portfolios as $key => $portfolio) {
                    $portfolio_links[$portfolio->title] = $portfolio->slug;
                }
            }
            if ($this->settings->enable_blog == true) {
                $default_menu_items = [];
                foreach ($this->top_left_nav as $nav) {
                    $default_menu_items[$nav['title']] = $nav['slug'];
                }
                $default_menu_items[$this->settings->blog_title] = '/posts';
            } else {
                $default_menu_items = [];
                foreach ($this->top_left_nav as $nav) {
                    $default_menu_items[$nav['title']] = $nav['slug'];
                }
            }
        } else {
            if ($this->settings->enable_blog == true) {
                $default_menu_items = [];
                foreach ($this->top_left_nav as $nav) {
                    $default_menu_items[$nav['title']] = $nav['slug'];
                }
                $default_menu_items[$this->settings->blog_title] = '/posts';
            } else {
                $default_menu_items = [];
                foreach ($this->top_left_nav as $nav) {
                    $default_menu_items[$nav['title']] = $nav['slug'];
                }
            }
        }
        $shared_links = array_merge($portfolio_links, $default_menu_items);

        View::share('shared_links', $shared_links);
        View::share('portfolio_links', $portfolio_links);

        //links for the top nav
        $top_menu_items = [
          'Home' => '/',
          'Portfolios' => '/about#',
          'About Page' => '/about',
          'Contact Page' => '/contact',
        ];
        View::share('top_links', $top_menu_items);
        /* Share post tags for light theme */
        if ($this->settings->theme == false) {
            $tags = new TagsService;
            $tags = $tags->get_tags_for_type('Post');
            \View::share('post_tags', $tags);
        }
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = view($this->layout);
        }
    }

    public function json_response($status, $message, $data, $code)
    {
        return Response::json(['status' => $status, 'message' => $message, 'data' => $data], $code);
    }

    public function respond($results, $view, $view_options, $message = null)
    {
        if (Request::format() == 'html') {
            if (!$results) {
                return view('404');
            }

            return view($view, $view_options);
        } else {
            if (!$results) {
                return Response::json(null, 404);
            }

            return Response::json(['data' => $results->toArray(), 'status' => 'success', 'message' => "Success"], 200);
        }
    }

    public function bannerSet($page)
    {
        if (isset($page) && $page->slug === '/home') {
            $banner = true;
        } else {
            $banner = false;
        }

        return $banner;
    }

    public function getPortfolioBlock()
    {
        return Portfolio::allActiveSorted();
    }

    public function uploadFile($data, $field_name)
    {
        //Only run when an image
        if ($data[$field_name]) {
            $image       = $data[$field_name];
            $filename    = $image->getClientOriginalName();
            $destination = $this->save_to;
            if (!$this->filesystem->exists($destination)) {
                $this->filesystem->mkdir($destination);
            }
            try {
                $image->move($destination, $filename);
                $data[$field_name] = $filename;
            } catch (\Exception $e) {
                throw new \Exception("Error uploading file $field_name" . $e->getMessage());
            }
        }

        return $data;
    }


    /**
     * @param $all
     * @param $model
     * @param $rules Portfolios::$rules
     * @return \Illuminate\Validation\Validator
     */
    public function validateSlugEdit($all, $model, $rules)
    {
        $messages = [];
        if (isset($all['slug']) && $all['slug'] != $model->slug) {
            $messages = [
                'slug.unique' => 'The url is not unique.',
                'slug.regex'  => 'The url must start with a slash and contain only letters and numbers, no spaces.'
            ];
        } else {
            unset($rules['slug']);
        }
        $validator = Validator::make($data = Input::all(), $rules, $messages);

        return $validator;
    }

    public function validateSlugOnCreate($all, $rules)
    {
        $messages  = [
            'slug.unique' => 'The url is not unique .',
            'slug.regex'  => 'The url must start with a slash and contain only letters and numbers, no spaces.'
        ];
        $validator = Validator::make($data = Input::all(), $rules, $messages);

        return $validator;
    }

    public function checkPublished($data)
    {
        if (!isset($data['published'])) {
            $data['published'] = 0;
        }

        return $data;
    }

    // put in sort to have images come in order assigned by file name br -2015/11/16
    public function getSlides()
    {

        $slides    = [];
        $directory = public_path() . "/slideshow/";
        $files     = File::allFiles($directory);
        foreach ($files as $key => $file) {
            $slides[$key] = "/slideshow/" . $file->getRelativePathname();
        }
        sort($slides) ;  // br 2016/01/21
        JavaScript::put(compact('slides'));
    }

    // Put in sort to enable images appear in order by file name br 2016-01/21
    public function checkForSlideshow($id)
    {
        $slideshow = false;
        $slide_ids = [2, 5];
        $id == in_array($id, $slide_ids) ? $slideshow = true : $tags = false;
        if ($this->settings->theme == false) {
            $slideshow = false;
        }
        \View::share('slideshow', $slideshow);
    }

    protected function updateImagesCaption($image_captions)
    {
        foreach ($image_captions as $key => $image_caption) {
            //@TODO add catch here
            $image_id = intval($key);
            $caption  = null;
            if (isset($image_caption)) {
                $caption = $image_caption[0];
            }
            if ($caption != null) {
                $new_data = ["image_caption" => $caption];
                Image::where("id", "=", $image_id)->update($new_data);
            }
        }
    }

    protected function updateImagesOrder($image_order_values)
    {
        foreach ($image_order_values as $key => $image_order) {
            //@TODO add catch here
            $image_id = intval($key);
            $order    = $image_order[0];
            if ($order != null) {
                $new_data = ["order" => $order];
                Image::where("id", "=", $image_id)->update($new_data);
            }
        }
    }
}