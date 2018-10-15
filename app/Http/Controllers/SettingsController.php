<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Filesystem\Filesystem;

class SettingsController extends Controller
{

    protected $filesystem;
    protected $settings_path;

    public function __construct(Filesystem $filesystem = null)
    {
        parent::__construct();
        $this->filesystem = ($filesystem == null) ? new Filesystem() : $filesystem;
        $this->beforeFilter("auth", ['only' => ['index', 'create', 'delete', 'edit', 'update', 'store']]);
        $this->settings_path = public_path() . "/img/settings";
    }
    /**
     * Display a listing of the resource.
     * GET /settings
     *
     * @return Response
     */
    public function index()
    {
        parent::show();
        //
    }

    /**
     * Show the form for creating a new resource.
     * GET /settings/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /settings
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /settings/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id = null)
    {
        parent::show();
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /settings/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id = null)
    {
        parent::show();
        $banner = $this->banner;
        $path   = "/img/settings";
        $setting = Setting::find($id);
        return view('settings.edit', compact('setting', 'path', 'banner'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /settings/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $data = Input::all();
        
        $setting = Setting::findOrFail($id);

        if ($setting->theme == false) {
            $validator = Validator::make($data, ['color' => 'required']);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }

        if (Input::get('remove_logo') != null) {
            $data['logo'] = '';
        } elseif (Input::hasFile('logo')) {
            $file = Input::file('logo');
            $filename = $file->getClientOriginalName();
            $destination = $this->settings_path;

            if (!$this->filesystem->exists($destination)) {
                $this->filesystem->mkdir($destination);
            }
            try {
                Input::file('logo')->move($destination, $filename);
            } catch (Exception $e) {
                dd("Error uploading file " . $e->getMessage());
            }
            $data['logo'] = $filename;
        } else {
            $data['logo'] = $setting->logo;
        }
        if ($setting->theme == false) {
            $setting->color             = $data['color'];
        }
        $setting->logo              = $data['logo'];
        $setting->name              = $data['name'];
        $setting->maintenance_mode  = (isset($data['maintenance_mode'])) ? 1 : 0;
        $setting->theme             = (!isset($data['theme']) || $data['theme'] == '0') ? false : true;
        $this->setRobot($setting->maintenance_mode);
        $setting->facebook          = (isset($data['facebook'])) ? $data['facebook'] : '';
        $setting->linkedin          = (isset($data['linkedin'])) ? $data['linkedin'] : '';
        $setting->twitter           = (isset($data['twitter'])) ? $data['twitter'] : '';
        $setting->pinterest         = (isset($data['pinterest'])) ? $data['pinterest'] : '';
        $setting->gplus             = (isset($data['gplus'])) ? $data['gplus'] : '';
        $setting->houzz             = (isset($data['houzz'])) ? $data['houzz'] : '';
        $setting->instagram         = (isset($data['instagram'])) ? $data['instagram'] : '';
        $setting->footer            = (isset($data['footer'])) ? $data['footer'] : '';
        $setting->google_analytics            = (isset($data['google_analytics'])) ? $data['google_analytics'] : '';
        $setting->portfolio_menu_position = $data['portfolio_position'];
        $setting->enable_left_nav = (isset($data['enable_left_nav'])) ? 1 : 0;
        $setting->blog_title = (isset($data['blog_title'])) ? $data['blog_title'] : '';
        $setting->portfolio_title = (isset($data['portfolio_title']) && !empty($data['portfolio_title'])) ? $data['portfolio_title'] : 'Portfolio';
        $setting->enable_blog = (isset($data['enable_blog'])) ? true : false;
        $setting->enable_portfolio = (isset($data['enable_portfolio'])) ? true : false;
        $setting->enable_noindex = (isset($data['enable_noindex'])) ? true : false;
        if (Auth::user() && Auth::user()->admin == 1) {
            $setting->blog_menu_position = $data['blog_menu_position'];
        }
        $setting->save();

        return redirect("/settings/" . $setting->id . "/edit")->withMessage("Settings Updated");
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /settings/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    protected function setRobot($mode)
    {
        $path = public_path();
        if ($mode === 'on' || $mode == '1') {
            $this->filesystem->copy($path . '/robots.txt.block', $path . '/robots.txt', $override = true);
        }
        if ($mode === 0) {
            $this->filesystem->copy($path . '/robots.txt.allow', $path . '/robots.txt', $override = true);
        }
    }
}