<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DinamikMenu;
use App\Models\Lang;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DynamicMenuController extends Controller
{
    public $title = 'Dynamic-Menus';
    public $route_name = 'dynamic-menus';
    public $route_parameter = 'dynamic-menu';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = DinamikMenu::latest()
            ->paginate(12);
        $languages = Lang::all();

        return view('admin.dynamic-menus.index', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'menus' => $menus,
            'languages' => $languages
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = Lang::all();
        $menus = Menu::all();

        return view('admin.dynamic-menus.create', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'langs' => $langs,
            'menus' => $menus
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


        DinamikMenu::create($data);

        return redirect()->route('dynamic-menus.index')->with([
            'success' => true,
            'message' => 'Saved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DinamikMenu $dynamic_menu)
    {
        $langs = Lang::all();
        $menus = Menu::all();

        return view('admin.dynamic-menus.edit', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'langs' => $langs,
            'menus' => $menus,
            'dynamic_menu' => $dynamic_menu
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

        $menu = DinamikMenu::findOrFail($id);

        $menu->update($data);

        return redirect()->route('dynamic-menus.index')->with([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu = DinamikMenu::findOrFail($id);
        $menu->delete();

        return redirect()->route('dynamic-menus.index')->with([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
    public function detele_file()
    {

        $menus = DinamikMenu::onlyTrashed()->latest()
            ->paginate(12);
        $languages = Lang::all();

        return view('admin.dynamic-menus.delete', [
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

        return redirect()->route('dynamic-menus.index')->with([
            'success' => true,
            'message' => 'Restored successfully'
        ]);
    }
    public function forceDestroy($id)
    {
        $menu = Menu::withTrashed()->findOrFail($id);
        $menu->forceDelete();

        return redirect()->route('dynamic-menus.index')->with([
            'success' => true,
            'message' => 'Permanently deleted successfully'
        ]);
    }
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            // Fayl nomini tozalash va noyob qilish
            $originalName = $request->file('upload')->getClientOriginalName();
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);

            // Faylni kerakli joyga saqlash
            $request->file('upload')->move(public_path('images/upload/'), $fileName);

            // CKEditor funksiyasi uchun raqamni olish
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');

            // Fayl URL manzilini olish
            $url = asset('images/upload/' . $fileName);
            $msg = 'Image successfully uploaded';

            // To'g'ri formatda javob berish
            return response()->json([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => $url,
            ]);
        } else {
            // Agar fayl yuklanmasa, CKEditor'ga xato haqida javob
            return response()->json([
                'uploaded' => 0,
                'error' => [
                    'message' => 'No file uploaded'
                ]
            ]);
        }
    }
}
