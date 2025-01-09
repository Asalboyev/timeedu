<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DinamikMenu;
use App\Models\Lang;
use App\Models\Menu;
use App\Models\PostsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\FormMenu;

class DynamicMenuController extends Controller
{
    public $title = 'Dynamic-Pages';
    public $route_name = 'dynamic-menus';
    public $route_parameter = 'dynamic-menu';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Query yaratamiz
        $menusQuery = DinamikMenu::query();

        // Agar "search" parametri bo'lsa, qidiruv sharti qo'shamiz
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = trim($_GET['search']);
            $menusQuery->where('title', 'like', '%' . $search . '%'); // Menyu sarlavhasida qidirish

        }

        // Pagination va tartib
        $menus = $menusQuery->latest()
            ->paginate(12);

        // Mavjud tillar
        $languages = Lang::all();

        // View qaytariladi
        return view('admin.dynamic-menus.index', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'menus' => $menus,
            'languages' => $languages,
            'search' => isset($_GET['search']) ? $_GET['search'] : '', // Qidiruv qiymati
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = Lang::all();
        $menus = Menu::all();
        $all_categories = PostsCategory::all();

        return view('admin.dynamic-menus.create', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'all_categories' => $all_categories,
            'langs' => $langs,
            'menus' => $menus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(Request $request)
//    {
//        dd($request->all());
//        $data = $request->all();
//
//        $validator = Validator::make($data, [
//            'title.'.$this->main_lang->code => 'required'
//        ]);
//        if (isset($data['dropzone_images'])) {
//            $data['background'] = $data['dropzone_images'];
//        }
//        if ($validator->fails()) {
//            return back()->withInput()->with([
//                'success' => false,
//                'message' => 'Validation error'
//            ]);
//        }
//
//
//        DinamikMenu::create($data);
//
//        return redirect()->route('dynamic-menus.index')->with([
//            'success' => true,
//            'message' => 'Saved successfully'
//        ]);
//    }


    public function store(Request $request)
    {
        // Create a new dinamik_menu record

        $dinamikMenu = DinamikMenu::create([
            'menu_id' => $request->menu_id,
            'title' => $request->title, // Store the title directly as it should already be JSON
            'short_title' => $request->short_title ?? null, // Handle nullable short_title
            'background' => $request->background,
        ]);

        // Define all form menu keys in a single array
        $formMenus1 = ['formmenu' ];
        $formMenus2 = ['formmenu2'];
        $formMenus3 = ['formmenu3'];

        // Iterate through the form menus and handle them
        foreach ($formMenus1 as $form1MenuKey) {
            if (!empty($request->$form1MenuKey)) {
                foreach ($request->$form1MenuKey as $formMenuData) {
                    FormMenu::create([
                        'title' => $formMenuData['title'] ?? [], // Default to empty array if not provided
                        'text' => $formMenuData['text'] ?? null,
                        'order' => $formMenuData['order'] ?? 0, // Default order to 0
                        'photo' => $formMenuData['dropzone_images'] ?? null,
                        'dinamik_menu_id' => $dinamikMenu->id,
                    ]);
                }
            }
        }
        foreach ($formMenus2 as $form2MenuKey) {
            if (!empty($request->$form2MenuKey)) {
                foreach ($request->$form2MenuKey as $form2MenuData) {
                    DB::beginTransaction(); // Start a transaction for each FormMenu
                    try {
                        // Create the FormMenu record
                        $formMenu = FormMenu::create([
                            'title' => $form2MenuData['title'] ?? [], // Default to empty array if not provided
                            'text' => $form2MenuData['text'] ?? null,
                            'order' => $form2MenuData['order'] ?? 0, // Default order to 0
                            'position' => $form2MenuData['position'] ?? 1, // Default position to 1 (for formmenu3)
                            'photo' => $form2MenuData['dropzone_images'] ?? null,
                            'dinamik_menu_id' => $dinamikMenu->id,
                        ]);

                        // Sync categories if they exist
                        if (isset($form2MenuData['categories']) && is_array($form2MenuData['categories'])) {
                            $formMenu->postsmenuCategories()->sync($form2MenuData['categories']);
                        }

                        DB::commit(); // Commit the transaction
                    } catch (Exception $e) {
                        DB::rollBack(); // Rollback the transaction in case of an error

                        // Log or handle the exception
                        return response()->json([
                            'success' => false,
                            'message' => 'Ошибка при сохранении категории: ' . $e->getMessage(),
                        ], 500);
                    }
                }
            }
        }
        foreach ($formMenus3 as $formMenuKey) {
            if (!empty($request->$formMenuKey)) {
                foreach ($request->$formMenuKey as $form3MenuData) {
                    FormMenu::create([
                        'title' => $form3MenuData['title'] ?? [], // Default to empty array if not provided
                        'text' => $form3MenuData['text'] ?? null,
                        'order' => $form3MenuData['order'] ?? 0, // Default order to 0
                        'position' => $form3MenuData['position'] ?? 1, // Default position to 1
                        'photo' => $form3MenuData['dropzone_images'] ?? null,
                        'dinamik_menu_id' => $dinamikMenu->id,
                    ]);
                }
            }
        }

        // Return success response
        return response()->json([
            'message' => 'Data successfully saved!',
        ]);
    }

//return redirect()->route('dynamic-menus.index')->with([
//'success' => true,
//'message' => 'Saved successfully'
//]);

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
        if (isset($data['dropzone_images'])) {
            $data['img'] = $data['dropzone_images'];
        } else {
            $data['img'] = null;
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
