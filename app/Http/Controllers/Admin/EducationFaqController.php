<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployType;
use App\Models\ERskill;
use App\Models\Lang;
use Illuminate\Http\Request;
use App\Models\EducationFaq;
use Illuminate\Support\Facades\Validator;

class EducationFaqController extends Controller
{
    public $title = 'Faq';
    public $route_name = 'employ_types';
    public $route_parameter = 'employ_type';
    /**
     * Display a listing of the resource.
     */
//    public function index($id)
//    {
//        // Qidiruv uchun so'rov yaratamiz
//        $employ_typesQuery = EducationFaq::where('educational_program_id', $id);
//
//        // Agar "search" parametri mavjud bo'lsa va bo'sh bo'lmasa
//        if (request()->has('search') && !empty(request('search'))) {
//            $search = trim(request('search')); // Qidiruv parametrini olish
//            $employ_typesQuery->where('name', 'like', '%' . $search . '%'); // Qidiruv sharti qo'shish
//        }
//
//        // Tartib va sahifalash
//        $employ_types = $employ_typesQuery->latest()->paginate(12);
//
//        // Mavjud tillar ro'yxati
//        $languages = Lang::all();
//
//        // View-ga ma'lumotlarni qaytarish
//        return view('admin.faq.index', [
//            'title' => $this->title,
//            'route_name' => $this->route_name,
//            'route_parameter' => $this->route_parameter,
//            'faq' => $employ_types,
//            'languages' => $languages,
//            'search' => request('search', ''), // Qidiruv qiymatini oling yoki bo'sh qiymat bering
//        ]);
//    }


    public function index($id)
    {

        $langs = Lang::all();

        $faqs = EducationFaq::where('educational_program_id', $id)->latest()->paginate(10);

        return view('admin.faq.index', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'langs' => $langs,
            'faqs' => $faqs,
            'id' => $id,
        ]);
    }

    public function create($id)
    {
        $langs = Lang::all();

        $skills = ERskill::query()->get();

        return view('admin.faq.create', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'langs' => $langs,
            'id' => $id,
            'skills' => $skills,
        ]);
    }


    /**
     *
     * Show the form for creating a new resource.
     */
    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        // Kiruvchi ma'lumotlarni tasdiqlash
        $data = $request->validate([
            'educational_program_id' => 'required|integer',
            'question' => 'required|array', // "question" array bo'lishi kerak
            'answer' => 'required|array', // "answer" ham array bo'lishi kerak
            'skill_id' => 'required|integer',
        ]);

        // Ma'lumotni saqlash
        EducationFaq::create([
            'educational_program_id' => $data['educational_program_id'],
            'question' => $data['question'], // JSON formatda saqlash
            'answer' => $data['answer'], // JSON formatda saqlash
            'skill_id' => $data['skill_id'],
        ]);

        // Yaratilgandan so'ng qaytish
        return redirect()->route('education_faqs.index', $data['educational_program_id'])
            ->with('success', 'FAQ muvaffaqiyatli qo\'shildi!');
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
        $faq = EducationFaq::find($id);
        $langs = Lang::all();
        $skills = ERskill::query()->get();

        // View ga ma'lumotlarni yuborish
        return view('admin.faq.edit', [
            'title' => $this->title,
            'route_name' => $this->route_name,
            'route_parameter' => $this->route_parameter,
            'langs' => $langs,
            'faq' => $faq,
            'skills' => $skills,
        ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Kiruvchi ma'lumotlarni tasdiqlash
        $data = $request->validate([
            'educational_program_id' => 'required|integer',
            'question' => 'required|array',
            'answer' => 'required|array',
            'skill_id' => 'required|integer',
        ]);

        // Tahrirlanayotgan obyektni olish
        $faq = EducationFaq::find($id);

        // Ma'lumotni yangilash
        $faq->update([
            'educational_program_id' => $data['educational_program_id'],
            'question' => $data['question'], // Array sifatida saqlash
            'answer' => $data['answer'], // Array sifatida saqlash
            'skill_id' => $data['skill_id'],
        ]);

        // Yangilangandan so'ng qaytish
        return redirect()->route('education_faqs.index', $data['educational_program_id'])
            ->with('success', 'FAQ muvaffaqiyatli yangilandi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Ma'lumotni topish va o'chirish
        EducationFaq::find($id)->delete();

        // Foydalanuvchiga muvaffaqiyatli xabar qaytarish
        return redirect()->back()->with('success', 'FAQ muvaffaqiyatli o‘chirildi!');
    }

}
