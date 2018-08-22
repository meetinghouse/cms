<?php

namespace App\Http\Controllers;

use App\Page;
use CMS\Services\MenuService;
use Illuminate\Support\Facades\Input;

class MenusController extends Controller
{

    public $pages;
    public $menuService;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        parent::show();
        $menus = Page::tree();
        $banner = $this->banner;
        return $this->respond($menus, 'menus.index', compact('menus', 'banner'));
    }

    public function store()
    {
        $input = Input::all();
        $menus = new MenuService();
        $menus->updateMenus($input['data']);
        $menus->saveMenus();
        return $this->json_response("success", "Menu Updates", null, 200);
    }
}
