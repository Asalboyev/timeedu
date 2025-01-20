<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lang;
use App\Models\Menu;
use App\Models\Post;
use App\Models\ProductsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MenusController extends Controller
{
    public $title = 'Menus';
    public $route_name = 'menus';
    public $route_parameter = 'menu';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menusQuery = Menu::query();

        // Search qidiruvi
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
            $menusQuery->where('title', 'like', '%' . $search . '%') // Menyu sarlavhasida qidirish
            ; // Menyu ta'rifida qidirish (agar mavjud bo'lsa)
        }

        // Pagination va tartib
        $menus = $menusQuery
            ->orderBy('parent_id') // Glavni menyularni avval olib keladi
            ->orderBy('order')    // Tartibni hisobga oladi
            ->paginate(12);

        // Hierarxik menyu daraxtini yaratish
        $menuTree = $this->buildMenuTree($menus);

        $languages = Lang::all();

        return view('admin.menus.index', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'menus' => $menuTree,
            'count' => $menus, // Pagination linklari uchun
            'languages' => $languages,
            'search' => isset($_GET['search']) ? $_GET['search'] : '', // Qidiruv qiymati
        ]);
    }


// Hierarxik menyular daraxti uchun yordamchi funksiya
    private function buildMenuTree($menus, $parentId = null)
    {
        $tree = [];
        foreach ($menus as $menu) {
            if ($menu->parent_id == $parentId) {
                $children = $this->buildMenuTree($menus, $menu->id);
                $tree[] = [
                    'menu' => $menu,
                    'children' => $children,
                ];
            }
        }
        return $tree;
    }






    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = Lang::all();
        $menus = Menu::all();

        return view('admin.menus.create', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'langs' => $langs,
            'all_categories' => $menus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title.'.$this->main_lang->code => 'required'
        ]);
        if ($validator->fails()) {
            return back()->withInput()->with([
                'success' => false,
                'message' => 'Validation error'
            ]);
        }
        $data['slug'] = Str::slug($data['title'][$this->main_lang->code], '-');
        if(Menu::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $data['slug'].'-'.time();
        }



        Menu::create($data);

        return redirect()->route('menus.index')->with([
            'success' => true,
            'message' => 'Saved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function updateOrder(Request $request)
    {
        $orderData = $request->order;

        foreach ($orderData as $menu) {
            Menu::where('id', $menu['id'])->update(['order' => $menu['order']]);
        }

        return response()->json(['message' => 'Order updated successfully!']);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $langs = Lang::all();
        $menus = Menu::all();

        return view('admin.menus.edit', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'langs' => $langs,
            'menus' => $menus,
            'menu' => $menu
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title.'.$this->main_lang->code => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with([
                'success' => false,
                'message' => 'Validation error'
            ]);
        }

        $menu = Menu::findOrFail($id);

        // Faqat agar `title` o'zgargan bo'lsa, yangi `slug` yaratiladi
        if ($menu->title[$this->main_lang->code] !== $data['title'][$this->main_lang->code]) {
            $data['slug'] = Str::slug($data['title'][$this->main_lang->code], '-');
            if (Menu::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                $data['slug'] = $data['slug'].'-'.time();
            }
        }

        $menu->update($data);

        return redirect()->route('menus.index')->with([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('menus.index')->with([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
    public function detele_file()
    {

        $menus = Menu::onlyTrashed()->latest()
            ->paginate(12);
        $languages = Lang::all();

        return view('admin.menus.delete', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'menus' => $menus,
            'languages' => $languages
        ]);
    }
    public function restore($id)
    {
        $menu = Menu::withTrashed()->findOrFail($id);
        $menu->restore();

        return redirect()->route('menus.index')->with([
            'success' => true,
            'message' => 'Restored successfully'
        ]);
    }
    public function forceDestroy($id)
    {
        $menu = Menu::withTrashed()->findOrFail($id);
        $menu->forceDelete();

        return redirect()->route('menus.index')->with([
            'success' => true,
            'message' => 'Permanently deleted successfully'
        ]);
    }
}
