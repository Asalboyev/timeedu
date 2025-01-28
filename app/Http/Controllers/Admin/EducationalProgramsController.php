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

    public function index()
    {
        $programsQuery = EducationalProgram::query();

        // Qidiruv (search)
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
            $programsQuery->where('name', 'like', '%' . $search . '%');
        }

        // Pagination bilan ma'lumot olish
        $paginatedPrograms = $programsQuery
            ->orderBy('parent_id') // Glavni (asosiy) elementlar avval keladi
            ->paginate(12);

        // Daraxtli tuzilma yaratish
        $educational_programs = $this->buildTree($paginatedPrograms->items());

        $languages = Lang::all();

        return view('admin.educational-programs.index', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'educational_programs' => $educational_programs,
            'count' => $paginatedPrograms, // Pagination linklari uchun
            'languages' => $languages,
            'search' => $_GET['search'] ?? '',
        ]);
    }

    private function buildTree($programs)
    {
        $programMap = [];
        $tree = [];

        // ID bo'yicha xaritalash
        foreach ($programs as $program) {
            $programMap[$program->id] = [
                'program' => $program,
                'children' => [],
            ];
        }

        // Daraxtga joylashtirish
        foreach ($programs as $program) {
            if ($program->parent_id && isset($programMap[$program->parent_id])) {
                $programMap[$program->parent_id]['children'][] = &$programMap[$program->id];
            } else {
                $tree[] = &$programMap[$program->id];
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

        DB::beginTransaction();
        try {
            // Handle icon upload if provided
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $iconPath = $icon->store('educational-programs/icons', 'public'); // Save in 'storage/app/public/educational-programs/icons'
                $data['icon'] = $iconPath; // Save the path in the database
            }

            $post = EducationalProgram::create($data);

            if (isset($data['dropzone_images'])) {
                $data['photo'] = $data['dropzone_images'];
            } else {
                $data['photo'] = null;
            }

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

            if (isset($data['dropzone_images'])) {
                $data['photo'] = $data['dropzone_images'];
            } else {
                $data['photo'] = null;
            }

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
