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
use App\Models\FormImage;

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


    public function store(Request $request)
    {
        $filePaths = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $lang => $file) {
                if ($file->isValid()) {
                    // Faylni saqlash yo'li
                    $destinationPath = 'uploads/dynamic_menus/' . $lang;
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePaths[$lang] = $file->storeAs($destinationPath, $fileName, 'public');
                }
            }
        }

        // DinamikMenu yozuvini yaratish
        $dinamikMenu = DinamikMenu::create([
            'menu_id' => $request->menu_id,
            'title' => $request->title, // JSON formatdagi title
            'short_title' => $request->short_title ?? null,
            'background' => $request->dropzone_images, // Dropzone'dan olingan rasmlar
            'file' => $filePaths, // JSON formatda fayllarni saqlash
        ]);


        if (isset($dinamikMenu['dropzone_images'])) {
            $dinamikMenu['background'] = $dinamikMenu['dropzone_images'];
        } else {
            $dinamikMenu['background'] = null;
        }

        // Define all form menu keys in a single array
        $formMenus1 = ['formmenu' ];
        $formMenus2 = ['formmenu2'];
        $formMenus3 = ['formmenu3'];

        // Iterate through the form menus and handle them
        foreach ($formMenus1 as $form1MenuKey) {
            if (!empty($request->$form1MenuKey)) {
                foreach ($request->$form1MenuKey as $formMenuData) {
                    // FormMenu uchun asosiy ma'lumotlarni saqlash
                    $formMenu = FormMenu::create([
                        'title' => $formMenuData['title'] ?? null,
                        'text' => $formMenuData['text'] ?? null,
                        'type' => $formMenuData['type'] ?? null,
                        'order' => $formMenuData['order'] ?? null,
                        'dinamik_menu_id' => $dinamikMenu->id ?? null,
                    ]);

                    // Tasvirlarni FormImage modeliga saqlash
                    if (isset($formMenuData['dropzone_images'])) {
                        // dropzone_images qaysi formatda kelayotganini tekshiramiz
                        $dropzoneImages = [];

                        if (is_string($formMenuData['dropzone_images'])) {
                            // JSON formatda kelayotgan satrni massivga aylantiramiz
                            $dropzoneImages = json_decode($formMenuData['dropzone_images'], true);
                        } elseif (is_array($formMenuData['dropzone_images'])) {
                            // Agar massiv bo'lsa, to'g'ridan-to'g'ri ishlatamiz
                            $dropzoneImages = $formMenuData['dropzone_images'];
                        }

                        // Har bir tasvirni FormImage ga saqlash
                        foreach ($dropzoneImages as $image) {
                            FormImage::create([
                                'form_id' => $formMenu->id,
                                'img' => trim($image), // Tasvir nomini tozalash
                            ]);
                        }
                    }
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
                            'title' => $form2MenuData['title'] ?? null, // Default to empty array if not provided
                            'text' => $form2MenuData['text'] ?? null,
                            'type' => $form2MenuData['type'] ?? null,

                            'order' => $form2MenuData['order'] ?? null, // Default order to 0
                            'photo' => $form2MenuData['dropzone_images'] ?? null,
                            'dinamik_menu_id' => $dinamikMenu->id,
                        ]);

                        if (isset($form2MenuData['dropzone_images'])) {
                            $form2MenuData['photo'] = $form2MenuData['dropzone_images'];
                        } else {
                            $form2MenuData['photo'] = null;
                        }
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
                            'message' => 'Error saving category: ' . $e->getMessage(),
                        ], 500);
                    }
                }
            }
        }
        foreach ($formMenus3 as $formMenuKey) {
            if (!empty($request->$formMenuKey)) {
                foreach ($request->$formMenuKey as $form3MenuData) {
                    // FormMenu3 uchun asosiy ma'lumotlarni saqlash
                    $formMenu3 = FormMenu::create([
                        'title' => $form3MenuData['title'] ?? null,
                        'text' => $form3MenuData['text'] ?? null,
                        'type' => $form3MenuData['type'] ?? null,
                        'order' => $form3MenuData['order'] ?? null,
                        'position' => $form3MenuData['position'] ?? 1, // Default position to 1
                        'photo' => $form3MenuData['dropzone_images'] ?? null,
                        'dinamik_menu_id' => $dinamikMenu->id ?? null,
                    ]);

                    // Tasvirlarni FormImage modeliga saqlash
                    if (isset($form3MenuData['dropzone_images'])) {
                        // dropzone_images qaysi formatda kelayotganini tekshiramiz
                        $dropzoneImages = [];

                        if (is_string($form3MenuData['dropzone_images'])) {
                            // JSON formatda kelayotgan satrni massivga aylantiramiz
                            $dropzoneImages = json_decode($form3MenuData['dropzone_images'], true);
                        } elseif (is_array($form3MenuData['dropzone_images'])) {
                            // Agar massiv bo'lsa, to'g'ridan-to'g'ri ishlatamiz
                            $dropzoneImages = $form3MenuData['dropzone_images'];
                        }

                        // Har bir tasvirni FormImage ga saqlash
                        foreach ($dropzoneImages as $image) {
                            FormImage::create([
                                'form_id' => $formMenu3->id,
                                'img' => trim($image), // Tasvir nomini tozalash
                            ]);
                        }
                    }
                }
            }
        }

        // Return success response
        return redirect()->route('dynamic-menus.index')->with([
            'success' => true,
            'message' => 'Saved successfully'
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
        $formmenu = FormMenu::where('dinamik_menu_id', $dynamic_menu->id)
            ->where('type', 'formmenu') // Filtering by type
            ->with('formImages', 'postsmenuCategories') // Eager loading relationships
            ->get();
        $formmenu1 = FormMenu::where('dinamik_menu_id', $dynamic_menu->id)
            ->where('type', 'formmenu1') // Filtering by type
            ->with('formImages', 'postsmenuCategories') // Eager loading relationships
            ->get();
        $formmenu3 = FormMenu::where('dinamik_menu_id', $dynamic_menu->id)
            ->where('type', 'formmenu3') // Filtering by type
            ->with('formImages', 'postsmenuCategories') // Eager loading relationships
            ->get();


        $all_categories = PostsCategory::all(); // Barcha kategoriyalar

        return view('admin.dynamic-menus.edit', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'all_categories' => $all_categories,
            'formmenu1' => $formmenu1,
            'formmenu3' => $formmenu3,
            'formmenu' => $formmenu,
            'langs' => $langs,
            'menus' => $menus,
            'dynamic_menu' => $dynamic_menu,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dinamikMenu = DinamikMenu::findOrFail($id);

        // Fayllarni saqlash uchun massiv
        $filePaths = $dinamikMenu->file ?? []; // Oldingi fayllarni olib kelamiz

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $lang => $file) {
                if ($file->isValid()) {
                    // Faylni saqlash yo'li
                    $destinationPath = 'uploads/dynamic_menus/' . $lang;
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    // Eski faylni o'chirish
                    if (isset($filePaths[$lang]) && \Storage::disk('public')->exists($filePaths[$lang])) {
                        \Storage::disk('public')->delete($filePaths[$lang]);
                    }

                    // Yangi faylni saqlash
                    $filePaths[$lang] = $file->storeAs($destinationPath, $fileName, 'public');
                }
            }
        }

        // Dinamik menyu ma'lumotlarini yangilash
        $dinamikMenu->update([
            'menu_id' => $request->menu_id,
            'title' => $request->title, // JSON formatdagi title
            'short_title' => $request->short_title ?? null,
            'background' => $request->dropzone_images, // Dropzone'dan olingan rasmlar
            'file' => $filePaths, // JSON formatda fayllarni saqlash
        ]);

        // Form menusini yangilash uchun funktsiya
        $this->updateFormMenus($request, $dinamikMenu);

        // Muvaffaqiyatli javob qaytarish
        return redirect()->route('dynamic-menus.index')->with([
            'success' => true,
            'message' => 'Updated successfully',
        ]);
    }

    /**
     * Form menusini yangilash
     */
    private function updateFormMenus(Request $request, $dinamikMenu)
    {
        $formMenusKeys = [
            'formmenu' => 1,
            'formmenu2' => 2,
            'formmenu3' => 3,
        ];

        foreach ($formMenusKeys as $key => $position) {
            if (!empty($request->$key)) {
                $existingIds = [];
                foreach ($request->$key as $formMenuData) {
                    $formMenu = FormMenu::updateOrCreate(
                        ['id' => $formMenuData['id'] ?? null],
                        [
                            'title' => $formMenuData['title'] ?? null,
                            'text' => $formMenuData['text'] ?? null,
                            'type' => $formMenuData['type'] ?? $key,
                            'order' => $formMenuData['order'] ?? null,
                            'position' => $position,
                            'photo' => $formMenuData['dropzone_images'] ?? null,
                            'dinamik_menu_id' => $dinamikMenu->id,
                        ]
                    );

                    $existingIds[] = $formMenu->id;

                    // Rasmni yangilash yoki qo'shish
                    if (isset($formMenuData['dropzone_images'])) {
                        $dropzoneImages = is_string($formMenuData['dropzone_images'])
                            ? json_decode($formMenuData['dropzone_images'], true)
                            : $formMenuData['dropzone_images'];

                        $formMenu->formImages()->delete(); // Eski rasmlarni o'chirish
                        foreach ($dropzoneImages as $image) {
                            FormImage::create([
                                'form_id' => $formMenu->id,
                                'img' => trim($image),
                            ]);
                        }
                    }
                }

                // Eski form menu yozuvlarini o'chirish
                FormMenu::where('dinamik_menu_id', $dinamikMenu->id)
                    ->where('position', $position)
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            }
        }
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
