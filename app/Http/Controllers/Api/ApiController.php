<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Document;
use App\Models\Member;
use App\Models\Question;
use App\Models\Service;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\SiteInfo;
use App\Models\Translation;
use App\Models\Partner;
use App\Models\Brand;
use App\Models\Application;



class ApiController extends Controller
{
    public function store(Request $request)
    {
        // Requestdagi ma'lumotlarni tekshirish (validatsiya)
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Ma'lumotni saqlash
        $contact = Application::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'message' => $request->message,
        ]);

        // Yangi Contact ma'lumotlarini qaytarish
        return response()->json([
            'message' => 'Contact information saved successfully.',
            'data' => $contact,
        ], 201);
    }

    public function show_students_filter($year)
    {
        // Filter shartlari
        $femaleCount = Member::where('yers', $year)
            ->where('gender', 'female')
            ->count();

        $maleCount = Member::where('yers', $year)
            ->where('gender', 'male')
            ->count();

        // Natijani qaytarish
        return response()->json([
            'year' => $year,
            'female_students' => $femaleCount,
            'male_students' => $maleCount,
        ]);
    }

    public function get_banner()
    {
        $locale = App::getLocale();

        $banners = Brand::latest()->paginate(10);

        if ($banners->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($banners->items())->map(function ($banner) use ($locale) {
            return [
                'id' => $banner->id,
                'title' => $banner->title[$locale] ?? null,
                'desc' => $banner->desc[$locale] ?? null,
                'url' => $banner->url ?? null,
                'images' => [
                    'lg' => $banner->lg_img, // Katta rasm uchun URL
                    'md' => $banner->md_img, // O‘rta rasm uchun URL
                    'sm' => $banner->sm_img, // Kichik rasm uchun URL
                ],
            ];
        });

        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $banners->total(),             // Umumiy postlar soni
            'per_page' => $banners->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $banners->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $banners->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $banners->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $banners->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }
    public function get_faq()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $question = Question::latest()->paginate(15);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($question->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($question->items())->map(function ($question) use ($locale) {
            return [
                'id' => $question->id,
                'answer' => $question->answer[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'question' => $question->question[$locale] ?? null, //

            ];
        });


        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $question->total(),             // Umumiy postlar soni
            'per_page' => $question->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $question->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $question->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $question->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $question->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }
    public function translations()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Tarjimalarni olish, har bir tarjimani uning guruhidan olish
        $banners = Translation::with('translationGroup')->latest()->get();

        // Agar tarjimalar topilmasa, 404 xatolikni qaytaradi
        if ($banners->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Tarjimalarni formatlash: key: value shaklida, group.sub_text.key formatida
        $translations = $banners->mapWithKeys(function ($banner) use ($locale) {
            return [
                $banner->translationGroup->sub_text . '.' . $banner->key => $banner->val[$locale] ?? null
            ];
        });

        // JSON formatida qaytarish
        return response()->json($translations);
    }

    public function show_faq($id){ // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Slug orqali postni olish
        $question = Question::where('id', $id)->first();

        if (is_null($question)) {
            return response()->json(['message' => 'FAQ not found or URL is not null'], 404);
        }

        // Postni foydalanuvchi tiliga moslashtirish
        $translatedPost = [
            'id' => $question->id,
            'question' => $question->question[$locale] ?? null,
            'answer' => $question->answer[$locale] ?? null,
            ];


        return response()->json($translatedPost);
    }
    public function get_students()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $member = Member::latest()->paginate(15);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($member->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($member->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null, //
                'name' => $member->name[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'dec' => $member->dec[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'position' => $member->position[$locale] ?? null, //
                'phone_number' => $member->phone_number ?? null, //
                'instagram_link' => $member->instagram_link ?? null, //
                'telegram_link	' => $member->telegram_link	 ?? null, //
                'linkedin_link' => $member->linkedin_link ?? null, //
                'facebook_link' => $member->facebook_link ?? null, //
                'facebook_link' => $member->facebook_link ?? null, //
                'work_time' => $member->work_time[$locale]?? null, //
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null, // Katta o'lchamdagi rasm
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null, // Kichik o'lchamdagi rasm
                ],

            ];
        });


        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $member->total(),             // Umumiy postlar soni
            'per_page' => $member->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $member->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $member->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $member->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $member->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }
    public function show_students(Request $request, $id = null)
{
    // Foydalanuvchi tilini olish
    $locale = App::getLocale();

    // Agar id bo'lsa, ma'lum bir studentni qaytarish
    if ($id) {
        $member = Member::find($id);

        // Agar student topilmasa, 404 xatolikni qaytaradi
        if (!$member) {
            return response()->json([
                'message' => 'Student not found'
            ], 404);
        }

        // Student ma'lumotlarini tilga mos formatda qaytarish
        $translatedStudent = [
            'id' => $member->id ?? null,
            'name' => $member->name[$locale] ?? null,
            'dec' => $member->dec[$locale] ?? null,
            'position' => $member->position[$locale] ?? null,
            'phone_number' => $member->phone_number ?? null,
            'instagram_link' => $member->instagram_link ?? null,
            'telegram_link' => $member->telegram_link ?? null,
            'linkedin_link' => $member->linkedin_link ?? null,
            'facebook_link' => $member->facebook_link ?? null,
            'work_time' => $member->work_time[$locale] ?? null,
            'photo' => [
                'lg' => $member->img ? url('/upload/images/' . $member->img) : null,
                'md' => $member->img ? url('/upload/images/600/' . $member->img) : null,
                'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null,
            ],
        ];

        return response()->json($translatedStudent);
    }

    // Agar id berilmagan bo'lsa, barcha studentlarni paginate qilish
    $members = Member::latest()->paginate(15);

    // Agar studentlar topilmasa, 404 xatolikni qaytaradi
    if ($members->isEmpty()) {
        return response()->json([
            'message' => 'No records found'
        ], 404);
    }

    // Studentlarni foydalanuvchi tiliga moslashtirish
    $translatedPosts = collect($members->items())->map(function ($member) use ($locale) {
        return [
            'id' => $member->id ?? null,
            'name' => $member->name[$locale] ?? null,
            'position' => $member->position[$locale] ?? null,
            'phone_number' => $member->phone_number ?? null,
            'instagram_link' => $member->instagram_link ?? null,
            'telegram_link' => $member->telegram_link ?? null,
            'linkedin_link' => $member->linkedin_link ?? null,
            'facebook_link' => $member->facebook_link ?? null,
            'work_time' => $member->work_time ?? null,
            'photo' => [
                'lg' => $member->img ? url('/upload/images/' . $member->img) : null,
                'md' => $member->img ? url('/upload/images/600/' . $member->img) : null,
                'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null,
            ],
        ];
    });

    // Studentlar va paginate ma'lumotlarini JSON formatida qaytarish
    return response()->json([
        'data' => $translatedPosts,             // Tilga mos studentlar
        'total' => $members->total(),             // Umumiy studentlar soni
        'per_page' => $members->perPage(),        // Har bir sahifadagi studentlar soni
        'current_page' => $members->currentPage(), // Hozirgi sahifa raqami
        'last_page' => $members->lastPage(),      // Oxirgi sahifa raqami
        'next_page_url' => $members->nextPageUrl(), // Keyingi sahifa URLi
        'prev_page_url' => $members->previousPageUrl(), // Oldingi sahifa URLi
    ]);
}

    public function get_certificates()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $member = Certificate::where('action', 0)->latest()->paginate(15);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($member->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($member->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null, //
                'title' => $member->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'desc' => $member->desc[$locale] ?? null, //
                'date' => $member->created_at?? null, //
                'file' => url('upload/certificates/' . $member->file),
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null, // Katta o'lchamdagi rasm
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null, // Kichik o'lchamdagi rasm
                ],

            ];
        });


        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $member->total(),             // Umumiy postlar soni
            'per_page' => $member->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $member->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $member->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $member->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $member->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }
    public function show_certificates(Request $request, $id = null)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Agar id bo'lsa, ma'lum bir studentni qaytarish
        if ($id) {
            $member = Certificate::find($id);

            // Agar student topilmasa, 404 xatolikni qaytaradi
            if (!$member) {
                return response()->json([
                    'message' => 'Student not found'
                ], 404);
            }

            // Student ma'lumotlarini tilga mos formatda qaytarish
            $translatedStudent = [
                'id' => $member->id ?? null, //
                'title' => $member->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'desc' => $member->desc[$locale] ?? null, //
                'date' => $member->created_at?? null, //
                'file' => url('upload/certificates/' . $member->file),
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null, // Katta o'lchamdagi rasm
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null, // Kichik o'lchamdagi rasm
                ],
            ];

            return response()->json($translatedStudent);
        }

        // Agar id berilmagan bo'lsa, barcha studentlarni paginate qilish
        $members = Member::latest()->paginate(15);

        // Agar studentlar topilmasa, 404 xatolikni qaytaradi
        if ($members->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Studentlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($members->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null,
                'name' => $member->name[$locale] ?? null,
                'position' => $member->position[$locale] ?? null,
                'phone_number' => $member->phone_number ?? null,
                'instagram_link' => $member->instagram_link ?? null,
                'telegram_link' => $member->telegram_link ?? null,
                'linkedin_link' => $member->linkedin_link ?? null,
                'facebook_link' => $member->facebook_link ?? null,
                'work_time' => $member->work_time ?? null,
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null,
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null,
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null,
                ],
            ];
        });

        // Studentlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos studentlar
            'total' => $members->total(),             // Umumiy studentlar soni
            'per_page' => $members->perPage(),        // Har bir sahifadagi studentlar soni
            'current_page' => $members->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $members->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $members->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $members->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }


    public function get_certificates_other()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $member = Certificate::where('action', 1)->latest()->paginate(15);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($member->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($member->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null, //
                'title' => $member->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'desc' => $member->desc[$locale] ?? null, //
                'date' => $member->created_at?? null, //
                'file' => url('upload/certificates/' . $member->file),
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null, // Katta o'lchamdagi rasm
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null, // Kichik o'lchamdagi rasm
                ],

            ];
        });


        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $member->total(),             // Umumiy postlar soni
            'per_page' => $member->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $member->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $member->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $member->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $member->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }
    public function show_certificates_other(Request $request, $id = null)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Agar id bo'lsa, ma'lum bir studentni qaytarish
        if ($id) {
            $member = Certificate::find($id);

            // Agar student topilmasa, 404 xatolikni qaytaradi
            if (!$member) {
                return response()->json([
                    'message' => 'Student not found'
                ], 404);
            }

            // Student ma'lumotlarini tilga mos formatda qaytarish
            $translatedStudent = [
                'id' => $member->id ?? null, //
                'title' => $member->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'desc' => $member->desc[$locale] ?? null, //
                'date' => $member->created_at?? null, //
                'file' => url('upload/certificates/' . $member->file),
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null, // Katta o'lchamdagi rasm
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null, // Kichik o'lchamdagi rasm
                ],
            ];

            return response()->json($translatedStudent);
        }

        // Agar id berilmagan bo'lsa, barcha studentlarni paginate qilish
        $members = Member::latest()->paginate(15);

        // Agar studentlar topilmasa, 404 xatolikni qaytaradi
        if ($members->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Studentlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($members->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null,
                'name' => $member->name[$locale] ?? null,
                'position' => $member->position[$locale] ?? null,
                'phone_number' => $member->phone_number ?? null,
                'instagram_link' => $member->instagram_link ?? null,
                'telegram_link' => $member->telegram_link ?? null,
                'linkedin_link' => $member->linkedin_link ?? null,
                'facebook_link' => $member->facebook_link ?? null,
                'work_time' => $member->work_time ?? null,
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null,
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null,
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null,
                ],
            ];
        });

        // Studentlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos studentlar
            'total' => $members->total(),             // Umumiy studentlar soni
            'per_page' => $members->perPage(),        // Har bir sahifadagi studentlar soni
            'current_page' => $members->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $members->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $members->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $members->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }

    public function get_documents()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $member = Document::latest()->paginate(15);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($member->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($member->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null, //
                'title' => $member->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'link' => $member->link ?? null, //
                'date' => $member->date ?? null, //
                'file' => url('/upload/documents/' . $member->file),
            ];
        });


        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $member->total(),             // Umumiy postlar soni
            'per_page' => $member->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $member->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $member->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $member->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $member->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }
    public function show_documents(Request $request, $id = null)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Agar id bo'lsa, ma'lum bir studentni qaytarish
        if ($id) {
            $member = Document::find($id);

            // Agar student topilmasa, 404 xatolikni qaytaradi
            if (!$member) {
                return response()->json([
                    'message' => 'Student not found'
                ], 404);
            }

            // Student ma'lumotlarini tilga mos formatda qaytarish
            $translatedStudent = [
                'id' => $member->id ?? null, //
                'title' => $member->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'link' => $member->link ?? null, //
                'date' => $member->date ?? null, //
                'file' => url('/upload/documents/' . $member->file),
            ];

            return response()->json($translatedStudent);
        }

        // Agar id berilmagan bo'lsa, barcha studentlarni paginate qilish
        $members = Member::latest()->paginate(15);

        // Agar studentlar topilmasa, 404 xatolikni qaytaradi
        if ($members->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Studentlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($members->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null,
                'name' => $member->name[$locale] ?? null,
                'position' => $member->position[$locale] ?? null,
                'phone_number' => $member->phone_number ?? null,
                'instagram_link' => $member->instagram_link ?? null,
                'telegram_link' => $member->telegram_link ?? null,
                'linkedin_link' => $member->linkedin_link ?? null,
                'facebook_link' => $member->facebook_link ?? null,
                'work_time' => $member->work_time ?? null,
                'date' => $member->created_at ?? null,
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null,
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null,
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null,
                ],
            ];
        });

        // Studentlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos studentlar
            'total' => $members->total(),             // Umumiy studentlar soni
            'per_page' => $members->perPage(),        // Har bir sahifadagi studentlar soni
            'current_page' => $members->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $members->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $members->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $members->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }

    public function get_journals()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $member = Service::latest()->paginate(15);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($member->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($member->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null, //
                'title' => $member->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'desc' => $member->desc[$locale]  ?? null, //
                'views_count' => $member->views_count ?? null, //
                'slug' => $member->slug ?? null, //
                'date' => $member->created_at ?? null,
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null, // Katta o'lchamdagi rasm
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null, // Kichik o'lchamdagi rasm
                ],
            ];
        });


        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $member->total(),             // Umumiy postlar soni
            'per_page' => $member->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $member->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $member->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $member->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $member->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }
    public function show_journals(Request $request, $slug = null)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Agar slug berilgan bo'lsa, ma'lum bir studentni qaytarish
        if ($slug) {
            $member = Service::where('slug', $slug)->first();

            // Agar student topilmasa, 404 xatolikni qaytaradi
            if (!$member) {
                return response()->json([
                    'message' => 'Student not found'
                ], 404);
            }

            // Student ma'lumotlarini tilga mos formatda qaytarish
            $translatedStudent = [
                'id' => $member->id ?? null,
                'title' => $member->title[$locale] ?? null,
                'desc' => $member->desc[$locale] ?? null,
                'views_count' => $member->views_count ?? null,
                'slug' => $member->slug ?? null,
                'date' => $member->created_at ?? null,
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null,
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null,
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null,
                ],
            ];

            return response()->json($translatedStudent);
        }

        // Agar slug berilmagan bo'lsa, barcha studentlarni paginate qilish
        $members = Member::latest()->paginate(15);

        // Agar studentlar topilmasa, 404 xatolikni qaytaradi
        if ($members->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Studentlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($members->items())->map(function ($member) use ($locale) {
            return [
                'id' => $member->id ?? null,
                'name' => $member->name[$locale] ?? null,
                'position' => $member->position[$locale] ?? null,
                'phone_number' => $member->phone_number ?? null,
                'instagram_link' => $member->instagram_link ?? null,
                'telegram_link' => $member->telegram_link ?? null,
                'linkedin_link' => $member->linkedin_link ?? null,
                'facebook_link' => $member->facebook_link ?? null,
                'work_time' => $member->work_time ?? null,
                'photo' => [
                    'lg' => $member->img ? url('/upload/images/' . $member->img) : null,
                    'md' => $member->img ? url('/upload/images/600/' . $member->img) : null,
                    'sm' => $member->img ? url('/upload/images/200/' . $member->img) : null,
                ],
            ];
        });

        // Studentlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,
            'total' => $members->total(),
            'per_page' => $members->perPage(),
            'current_page' => $members->currentPage(),
            'last_page' => $members->lastPage(),
            'next_page_url' => $members->nextPageUrl(),
            'prev_page_url' => $members->previousPageUrl(),
        ]);
    }
    public function get_partners()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Partner modeli 1 bo'lganlarini olish va 10 tadan paginate qilish
        $partners = Partner::where('partner', 1)->latest()->paginate(10);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($partners->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($partners->items())->map(function ($partners) use ($locale) {
            return [
                'id' => $partners->id,
                'title' => $partners->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'photo' => [
                    'lg' => $partners->img ? url('/upload/images/' . $partners->img) : null, // Katta o'lchamdagi rasm
                    'md' => $partners->img ? url('/upload/images/600/' . $partners->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $partners->img ? url('/upload/images/200/' . $partners->img) : null, // Kichik o'lchamdagi rasm
                ],
                'link' => $partners->link,
            ];
        });

        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,
            'total' => $partners->total(),
            'per_page' => $partners->perPage(),
            'current_page' => $partners->currentPage(),
            'last_page' => $partners->lastPage(),
            'next_page_url' => $partners->nextPageUrl(),
            'prev_page_url' => $partners->previousPageUrl(),
        ]);
    }

    public function show_partners($id)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // ID va partner modeli 1 bo'lgan postni olish
        $partners = Partner::where('partner', 1)->find($id);

        if (is_null($partners)) {
            return response()->json(['message' => 'Post not found or URL is not null'], 404);
        }

        // Postni foydalanuvchi tiliga moslashtirish
        $translatedPost = [
            'id' => $partners->id,
            'title' => $partners->title[$locale] ?? null,
            'photo' => [
                'lg' => $partners->img ? url('/upload/images/' . $partners->img) : null, // Katta o'lchamdagi rasm
                'md' => $partners->img ? url('/upload/images/600/' . $partners->img) : null, // O'rtacha o'lchamdagi rasm
                'sm' => $partners->img ? url('/upload/images/200/' . $partners->img) : null, // Kichik o'lchamdagi rasm
            ],
            'link' => $partners->link,
        ];

        return response()->json($translatedPost);
    }
    public function get_partners_link()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Partner modeli 1 bo'lganlarini olish va 10 tadan paginate qilish
        $partners = Partner::where('partner', 0)->latest()->paginate(10);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($partners->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($partners->items())->map(function ($partners) use ($locale) {
            return [
                'id' => $partners->id,
                'title' => $partners->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'photo' => [
                    'lg' => $partners->img ? url('/upload/images/' . $partners->img) : null, // Katta o'lchamdagi rasm
                    'md' => $partners->img ? url('/upload/images/600/' . $partners->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $partners->img ? url('/upload/images/200/' . $partners->img) : null, // Kichik o'lchamdagi rasm
                ],
                'link' => $partners->link,
            ];
        });

        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,
            'total' => $partners->total(),
            'per_page' => $partners->perPage(),
            'current_page' => $partners->currentPage(),
            'last_page' => $partners->lastPage(),
            'next_page_url' => $partners->nextPageUrl(),
            'prev_page_url' => $partners->previousPageUrl(),
        ]);
    }

    public function show_partners_link($id)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // ID va partner modeli 1 bo'lgan postni olish
        $partners = Partner::where('partner', 0)->find($id);

        if (is_null($partners)) {
            return response()->json(['message' => 'Post not found or URL is not null'], 404);
        }

        // Postni foydalanuvchi tiliga moslashtirish
        $translatedPost = [
            'id' => $partners->id,
            'title' => $partners->title[$locale] ?? null,
            'photo' => [
                'lg' => $partners->img ? url('/upload/images/' . $partners->img) : null, // Katta o'lchamdagi rasm
                'md' => $partners->img ? url('/upload/images/600/' . $partners->img) : null, // O'rtacha o'lchamdagi rasm
                'sm' => $partners->img ? url('/upload/images/200/' . $partners->img) : null, // Kichik o'lchamdagi rasm
            ],
            'link' => $partners->link,
        ];

        return response()->json($translatedPost);
    }


    public function getCompany()
    {
        // Hozirgi foydalanuvchi tilini olish
        $locale = App::getLocale();

        // SiteInfo ma'lumotlarini olish (oxirgi kiritilgan)
        $site_info = SiteInfo::latest()->first();

        if (!$site_info) {
            return response()->json(['message' => 'Site information not found'], 404);
        }

        // Foydalanuvchi tiliga moslashtirilgan ma'lumotlar
        $translatedSiteInfo = [
            'id' => $site_info->id,
            'title' => $site_info->title[$locale] ?? $site_info->title,  // Foydalanuvchi tiliga mos nom
            'logo_dark' => $site_info->logo_dark,  // Qorong'u logo
            'desc' => $site_info->desc[$locale] ?? $site_info->desc,  // Tavsif
            'address' => $site_info->address[$locale] ?? $site_info->address,  // Manzil
            'phone_number' => $site_info->phone_number,  // Telefon raqami
            'email' => $site_info->email,  // Elektron pochta
            'work_time' => $site_info->work_time[$locale] ?? $site_info->work_time ?? null,  // Ish vaqti
            'map' => $site_info->map,  // Xarita
            'exchange' => $site_info->exchange,  // Kurs o'zgarishlari
            'favicon' => $site_info->favicon ? url('/upload/images/' . $site_info->favicon) : null,
            'logo' => $site_info->logo ? url('/upload/images/' . $site_info->logo) : null,
            'telegram' => $site_info->telegram,  // Telegram
            'instagram' => $site_info->instagram,  // Instagram
            'facebook' => $site_info->facebook,  // Facebook
            'youtube' => $site_info->youtube,  // YouTube
            'audience_size' => $site_info->audience_size,  // Auditoriya hajmi
            'educational_programs' => $site_info->educational_programs,  // Ta'lim dasturlari
            'green_zone' => $site_info->green_zone,  // Yashil hudud
            'library_collection' => $site_info->library_collection,  // Kutubxona fondi
            'number_of_students' => $site_info->number_of_students,  // Talabalar soni
            'male_students' => $site_info->male_students,  // Erkak talabalar
            'female_students' => $site_info->female_students,  // Ayol talabalar
        ];


        // JSON formatida natijalarni qaytarish
        return response()->json([
            'data' => $translatedSiteInfo,  // Foydalanuvchi tiliga mos kompaniya ma'lumotlari
        ]);
    }

    public function get_vacancies()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $vacancies = Vacancy::latest()->paginate(10);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($vacancies->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($vacancies->items())->map(function ($vacancies) use ($locale) {
            return [
                'id' => $vacancies->id,
                'title' => $vacancies->title[$locale] ?? null, // Mahsulotning nomi (locale bo'yicha)
                'desc' => $vacancies->desc[$locale] ?? null, // Mahsulotning ta'rifi (locale bo'yicha)
                'week' => $vacancies->week[$locale] ?? null, // Mahsulotning ta'rifi (locale bo'yicha)
                'price' => $vacancies->price[$locale] ?? null, // Mahsulotning ta'rifi (locale bo'yicha)

                // Rasmning to'liq URL manzili, turli o'lchamlar uchun
                'photo' => [
                    'lg' => $vacancies->img ? url('/upload/images/' . $vacancies->img) : null, // Katta o'lchamdagi rasm
                    'md' => $vacancies->img ? url('/upload/images/600/' . $vacancies->img) : null, // O'rtacha o'lchamdagi rasm
                    'sm' => $vacancies->img ? url('/upload/images/200/' . $vacancies->img) : null, // Kichik o'lchamdagi rasm
                ],
                'date' => $vacancies->date,
                'views_count' => $vacancies->views_count,
                'location' => $vacancies->location,
            ];
        });


        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $vacancies->total(),             // Umumiy postlar soni
            'per_page' => $vacancies->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $vacancies->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $vacancies->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $vacancies->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $vacancies->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }

    public function show_vacancies($id)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Slug orqali postni olish
        $vacancies = Vacancy::find($id);

        // Agar vacancy topilmasa, 404 xatolikni qaytaradi
        if (is_null($vacancies)) {
            return response()->json(['message' => 'Vacancy not found or URL is invalid'], 404);
        }

        // `views_count` maydonini 1 ga oshirish
        $vacancies->increment('views_count');

        // Postni foydalanuvchi tiliga moslashtirish
        $translatedPost = [
            'id' => $vacancies->id,
            'title' => $vacancies->title[$locale] ?? null, // Vakansiyaning nomi (locale bo'yicha)
            'desc' => $vacancies->desc[$locale] ?? null, // Vakansiyaning ta'rifi (locale bo'yicha)
            'week' => $vacancies->week[$locale] ?? null, // Haftalik malumoti
            'price' => $vacancies->price[$locale] ?? null, // Narx ma'lumoti
            'photo' => [
                'lg' => $vacancies->img ? url('/upload/images/' . $vacancies->img) : null, // Katta o'lchamdagi rasm
                'md' => $vacancies->img ? url('/upload/images/600/' . $vacancies->img) : null, // O'rtacha o'lchamdagi rasm
                'sm' => $vacancies->img ? url('/upload/images/200/' . $vacancies->img) : null, // Kichik o'lchamdagi rasm
            ],
            'date' => $vacancies->date,
            'views_count' => $vacancies->views_count, // Yangilangan views_count
            'location' => $vacancies->location,
        ];

        return response()->json($translatedPost);
    }
}
