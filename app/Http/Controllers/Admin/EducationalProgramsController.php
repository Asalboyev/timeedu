<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalProgram;
use App\Models\Employ;
use App\Models\Lang;
use App\Models\Menu;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostsCategory;
use App\Models\StructureType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class EducationalProgramsController extends Controller
{
    public $title = 'Educational Program';
    public $route_name = 'educational-programs';
    public $route_parameter = 'educational-program';
    /**
     * Display a listing of the resource.
     */
//    public function index()
//    {
//        // Query yaratamiz
//        $positionQuery = EducationalProgramseed::query();
//
//        // Agar "search" parametri bo'lsa, qidiruv sharti qo'shamiz
//        if (isset($_GET['search']) && !empty($_GET['search'])) {
//            $search = trim($_GET['search']);
//            $positionQuery->where('name', 'like', '%' . $search . '%'); // Menyu sarlavhasida qidirish
//
//        }
//
//        // Pagination va tartib
//        $educational_programs = $positionQuery->latest()
//            ->paginate(12);
//
//        // Mavjud tillar
//        $languages = Lang::all();
//
//        // View qaytariladi
//        return view('admin.educational-programs.index', [
//            'title' => $this->title,
//            'route_name' => $this->route_name,
//            'route_parameter' => $this->route_parameter,
//            'educational_programs' => $educational_programs,
//            'languages' => $languages,
//            'search' => isset($_GET['search']) ? $_GET['search'] : '', // Qidiruv qiymati
//        ]);
//    }


    public function index(Request $request)
    {
        $languages = Lang::all();
        $search = $request->input('search'); // Qidiruv uchun so'rovdan kelayotgan ma'lumot

        // Faqat asosiy menyularni paginate qilish
        $paginatedMenus = EducationalProgram::whereNull('parent_id')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->paginate(2);

        // Hierarxik daraxtni sahifalangan menyular asosida tuzish
        $menuTree = $this->buildMenuTree($paginatedMenus);

        return view('admin.educational-programs.index', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,

            'educational_programs' => $menuTree, // Hierarxik menyular daraxti
            'languages' => $languages,
            'count' => $paginatedMenus, // Sahifalash obyektini blade-ga yuborish
            'search' => $search, // Qidiruvni blade-ga yuborish
        ]);
    }


    private function buildMenuTree($paginatedMenus)
    {
        // Sahifalangan asosiy menyular ID-lari
        $menuIds = $paginatedMenus->pluck('id')->toArray();

        // Ushbu asosiy menyularga tegishli bolalarni olish
        $childMenus = EducationalProgram::whereIn('parent_id', $menuIds)
            ->get();

        // Hierarxik daraxtni tuzish
        $menuTree = [];
        foreach ($paginatedMenus as $menu) {
            $menuTree[] = [
                'menu' => $menu, // Asosiy menyu
                'children' => $childMenus->where('parent_id', $menu->id), // Faqat tegishli bolalar
            ];
        }

        return $menuTree;
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = Lang::all();
        $educationalProgram = EducationalProgram::whereNull('parent_id')->get();

        $employ = Employ::where('professor', '1')->get();
        $all_categories = PostsCategory::all();



        return view('admin.educational-programs.create', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'educationalProgram' => $educationalProgram,
            'employ' => $employ,
            'all_categories' => $all_categories,
            'langs' => $langs,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $data = $request->all();
        $data['date'] = isset($data['date']) ? date('Y-m-d', strtotime($data['date'])) : date('Y-m-d');

        $validator = Validator::make($data, [
            'first_name.' . $this->main_lang->code => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with([
                'success' => false,
                'message' => 'Ошибка валидации'
            ]);
        }

        $data['slug'] = Str::slug($data['first_name'][$this->main_lang->code], '-');
        if (EducationalProgram::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $data['slug'] . '-' . time();
        }
        if (isset($data['dropzone_images'])) {
            $data['photo'] = $data['dropzone_images'];
        } else {
            $data['photo'] = null;
        }

        DB::beginTransaction();
        try {
            // Handle icon upload if provided
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $iconPath = $icon->store('educational-programs/icons', 'public'); // Save in 'storage/app/public/educational-programs/icons'
                $data['icon'] = $iconPath; // Save the path in the database
            }

            $post = EducationalProgram::create($data);



            if (isset($data['employs'])) {
                $post->employs()->sync($data['employs']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with([
                'success' => false,
                'message' => 'Transaction error'
            ]);
        }

        return redirect()->route('educational-programs.index')->with([
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
    public function edit($id)
    {
        $langs = Lang::all();
        $educationalProgram = EducationalProgram::findOrFail($id);
        $employ = Employ::where('professor', '1')->get();

        $educationalPrograms = EducationalProgram::whereNull('parent_id')->get();

        return view('admin.educational-programs.edit', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'educationalProgram' => $educationalProgram,
            'employ' => $employ,
            'educationalPrograms' => $educationalPrograms,
            'langs' => $langs,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
//        dd($request->all());
        $data = $request->all();
        $data['date'] = isset($data['date']) ? date('Y-m-d', strtotime($data['date'])) : date('Y-m-d');

        $validator = Validator::make($data, [
            'name.' . $this->main_lang->code => 'required',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Validate the icon
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with([
                'success' => false,
                'message' => 'Ошибка валидации'
            ]);
        }

        $data['slug'] = Str::slug($data['name'][$this->main_lang->code], '-');
        if (EducationalProgram::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
            $data['slug'] = $data['slug'] . '-' . time();
        }

        if (isset($data['dropzone_images'])) {
            $data['photo'] = $data['dropzone_images'];
        } else {
            $data['photo'] = null;
        }

        DB::beginTransaction();
        try {
            $post = EducationalProgram::findOrFail($id);

            // Handle icon upload if provided
            if ($request->hasFile('icon')) {
                // Delete the existing icon if it exists
                if ($post->icon) {
                    Storage::disk('public')->delete($post->icon);
                }

                $icon = $request->file('icon');
                $iconPath = $icon->store('educational-programs/icons', 'public'); // Save in 'storage/app/public/educational-programs/icons'
                $data['icon'] = $iconPath; // Save the new path in the data
            }

            $post->update($data);



            if (isset($data['employs'])) {
                $post->employs()->sync($data['employs']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()->with([
                'success' => false,
                'message' => 'Transaction error'
            ]);
        }

        return redirect()->route('educational-programs.index')->with([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $departament = EducationalProgram::findOrFail($id);
        $departament->delete();

        return redirect()->route('educational-programs.index')->with([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
