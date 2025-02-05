<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kampus;
use App\Models\PostsCategory;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\App;


class NewsController extends Controller
{

    public function get_posts()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $posts = Post::latest()
            ->whereNull('video_link') // Faqat video_link null bo'lgan postlarni olish
            ->with('postImages', 'postsCategories')
            ->paginate(10);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($posts->items())->map(function ($post) use ($locale) {
            return [
                'id' => $post->id,
                'title' => $post->title[$locale] ?? null,
                'desc' => $post->desc[$locale] ?? null,
                'images' => $post->postImages->map(function ($image) {
                    return [
                        'lg' => $image->lg_img, // Katta o'lchamdagi rasm URL
                        'md' => $image->md_img, // O'rta o'lchamdagi rasm URL
                        'sm' => $image->sm_img, // Kichik o'lchamdagi rasm URL
                    ];
                })->toArray(),
                'categories' => $post->postsCategories->map(function ($category) use ($locale) {
                    return [
                        'id' => $category->id,
                        'name' => $category->title[$locale] ?? null, // Kategoriya nomini foydalanuvchi tilida chiqarish
                    ];
                })->toArray(),
                'date' => $post->date,
                'views_count' => $post->views_count,
                'slug' => $post->slug,
                'meta_keywords' => $post->meta_keywords,
            ];
        });

        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts, // Tilga mos postlar
            'total' => $posts->total(), // Umumiy postlar soni
            'per_page' => $posts->perPage(), // Har bir sahifadagi postlar soni
            'current_page' => $posts->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $posts->lastPage(), // Oxirgi sahifa raqami
            'next_page_url' => $posts->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $posts->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }

    public function show_post($slug)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Slug orqali postni olish
        $post = Post::with('postsCategories', 'postImages')->where('slug', $slug)->whereNull('video_link')->first();

        if (is_null($post)) {
            return response()->json(['message' => 'Post not found or URL is not null'], 404);
        }

        // Postni har safar ko'rilganda views_countni oshirish
        $post->increment('views_count');

        // Postni foydalanuvchi tiliga moslashtirish
        $translatedPost = [
            'id' => $post->id,
            'title' => $post->title[$locale] ?? null,
            'desc' => $post->desc[$locale] ?? null,
            'images' => $post->postImages->map(function ($image) {
                return [
                    'lg' => $image->lg_img, // Katta o'lchamdagi rasm URL
                    'md' => $image->md_img, // O'rta o'lchamdagi rasm URL
                    'sm' => $image->sm_img, // Kichik o'lchamdagi rasm URL
                ];
            })->toArray(),
            'categories' => $post->postsCategories->map(function ($category) use ($locale) {
                return [
                    'id' => $category->id,
                    'title' => $category->title[$locale] ?? null, // Kategoriya nomini foydalanuvchi tilida chiqarish
                ];
            })->toArray(),
            'slug' => $post->slug,
            'date' => $post->date,
            'views_count' => $post->views_count, // Yangilangan views_count
            'meta_keywords' => $post->meta_keywords,
        ];

        return response()->json($translatedPost);
    }

    public function get_kampus()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va 10 tadan paginate qilish
        $posts = Kampus::latest()
            ->with('kampusImages')
            ->paginate(10);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($posts->items())->map(function ($post) use ($locale) {
            return [
                'id' => $post->id,
                'name' => $post->name[$locale] ?? null,
                'first_name' => $post->first_name[$locale] ?? null,
                'first_description' => $post->first_description[$locale] ?? null,
                'second_name' => $post->second_name[$locale] ?? null,
                'second_description' => $post->second_description[$locale] ?? null,
                'third_name' => $post->third_name[$locale] ?? null,
                'third_description' => $post->third_description[$locale] ?? null,
                'slug' => $post->slug?? null,
                'audience_size' => $post->audience_size   ?? null,
                'educational_programs' => $post->educational_programs   ?? null,
                'green_zone' => $post->green_zone   ?? null,

                'images' => $post->kampusImages->map(function ($image) {
                    return [
                        'lg' => $image->lg_img, // Katta o'lchamdagi rasm URL
                        'md' => $image->md_img, // O'rta o'lchamdagi rasm URL
                        'sm' => $image->sm_img, // Kichik o'lchamdagi rasm URL
                    ];
                })->toArray(),
                'date' => $post->date,
                'views_count' => $post->views_count,
                'slug' => $post->slug,
                'meta_keywords' => $post->meta_keywords,
            ];
        });

        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts, // Tilga mos postlar
            'total' => $posts->total(), // Umumiy postlar soni
            'per_page' => $posts->perPage(), // Har bir sahifadagi postlar soni
            'current_page' => $posts->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $posts->lastPage(), // Oxirgi sahifa raqami
            'next_page_url' => $posts->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $posts->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }

    public function show_kampus($slug)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Slug orqali postni olish
        $post = Kampus::with( 'kampusImages')->where('slug', $slug)->first();

        if (is_null($post)) {
            return response()->json(['message' => 'Post not found or URL is not null'], 404);
        }
        // Postni foydalanuvchi tiliga moslashtirish
        $translatedPost = [
            'id' => $post->id,
            'name' => $post->name[$locale] ?? null,
            'first_name' => $post->first_name[$locale] ?? null,
            'first_description' => $post->first_description[$locale] ?? null,
            'second_name' => $post->second_name[$locale] ?? null,
            'second_description' => $post->second_description[$locale] ?? null,
            'third_name' => $post->third_name[$locale] ?? null,
            'third_description' => $post->third_description[$locale] ?? null,
            'slug' => $post->slug?? null,
            'audience_size' => $post->audience_size   ?? null,
            'educational_programs' => $post->educational_programs   ?? null,
            'green_zone' => $post->green_zone   ?? null,

            'images' => $post->kampusImages->map(function ($image) {
                return [
                    'lg' => $image->lg_img, // Katta o'lchamdagi rasm URL
                    'md' => $image->md_img, // O'rta o'lchamdagi rasm URL
                    'sm' => $image->sm_img, // Kichik o'lchamdagi rasm URL
                ];
            })->toArray(),
            'date' => $post->date,
            'views_count' => $post->views_count,
            'slug' => $post->slug,
            'meta_keywords' => $post->meta_keywords,
        ];


        return response()->json($translatedPost);
    }


    public function get_video_post()
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Postlarni oxirgi qo'shilganidan boshlab olish va faqat video_link null bo'lmaganlarini 10 tadan paginate qilish
        $posts = Post::latest()
            ->whereNotNull('video_link') // Faqat video_link mavjud bo'lgan postlar
            ->with('postImages', 'postsCategories')
            ->paginate(10);

        // Agar postlar topilmasa, 404 xatolikni qaytaradi
        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = collect($posts->items())->map(function ($post) use ($locale) {
            return [
                'id' => $post->id,
                'title' => $post->title[$locale] ?? null,
                'desc' => $post->desc[$locale] ?? null,
                'video_link' => $post->video_link, // Video link qo'shildi
                'images' => $post->postImages->map(function ($image) {
                    return [
                        'lg' => $image->lg_img, // Katta o'lchamdagi rasm URL
                        'md' => $image->md_img, // O'rta o'lchamdagi rasm URL
                        'sm' => $image->sm_img, // Kichik o'lchamdagi rasm URL
                    ];
                })->toArray(),
                'categories' => $post->postsCategories->map(function ($category) use ($locale) {
                    return [
                        'id' => $category->id,
                        'name' => $category->title[$locale] ?? null, // Kategoriya nomini foydalanuvchi tilida chiqarish
                    ];
                })->toArray(),
                'date' => $post->date,
                'views_count' => $post->views_count,
                'slug' => $post->slug,
                'meta_keywords' => $post->meta_keywords,
            ];
        });

        // Postlar va paginate ma'lumotlarini JSON formatida qaytarish
        return response()->json([
            'data' => $translatedPosts,             // Tilga mos postlar
            'total' => $posts->total(),             // Umumiy postlar soni
            'per_page' => $posts->perPage(),        // Har bir sahifadagi postlar soni
            'current_page' => $posts->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $posts->lastPage(),      // Oxirgi sahifa raqami
            'next_page_url' => $posts->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $posts->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }

    public function show_video_post($slug)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Slug orqali video_link mavjud bo'lgan postni olish
        $post = Post::with('postsCategories', 'postImages')
            ->where('slug', $slug)
            ->whereNotNull('video_link') // Faqat video_link mavjud bo'lgan postlar
            ->first();

        // Agar post topilmasa, 404 xatolikni qaytaradi
        if (is_null($post)) {
            return response()->json(['message' => 'Post not found or does not have a video link'], 404);
        }

        // Postni har safar ko'rilganda views_countni oshirish
        $post->increment('views_count');

        // Postni foydalanuvchi tiliga moslashtirish
        $translatedPost = [
            'id' => $post->id,
            'title' => $post->title[$locale] ?? null,
            'desc' => $post->desc[$locale] ?? null,
            'video_link' => $post->video_link, // Video link qo'shildi
            'images' => $post->postImages->map(function ($image) {
                return [
                    'lg' => $image->lg_img, // Katta o'lchamdagi rasm URL
                    'md' => $image->md_img, // O'rta o'lchamdagi rasm URL
                    'sm' => $image->sm_img, // Kichik o'lchamdagi rasm URL
                ];
            })->toArray(),
            'categories' => $post->postsCategories->map(function ($category) use ($locale) {
                return [
                    'id' => $category->id,
                    'title' => $category->title[$locale] ?? null, // Kategoriya nomini foydalanuvchi tilida chiqarish
                ];
            })->toArray(),
            'slug' => $post->slug,
            'date' => $post->date,
            'views_count' => $post->views_count, // Yangilangan views_count
            'meta_keywords' => $post->meta_keywords,
        ];

        // JSON javobni qaytarish
        return response()->json($translatedPost);
    }


    public function get_categories()
    {
        $locale = App::getLocale();

        // Asosiy kategoriyalarni olish (faqat parent_id = null bo'lganlar)
        $categories = PostsCategory::whereNull('parent_id')->with('children')->latest()->paginate(10);

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Kategoriyalarni map qilish (asosiy va farzand kategoriyalarni tarjimalar bilan)
        $translatedPosts = collect($categories->items())->map(function ($category) use ($locale) {
            return [
                'id' => $category->id,
                'title' => $category->title[$locale] ?? null,
                'desc' => $category->desc[$locale] ?? null,
                'children' => $category->children->map(function ($child) use ($locale) {
                    return [
                        'id' => $child->id,
                        'title' => $child->title[$locale] ?? null,
                        'desc' => $child->desc[$locale] ?? null,
                        'images' => [
                            'lg' => $child->lg_img,
                            'md' => $child->md_img,
                            'sm' => $child->sm_img,
                        ],
                    ];
                }),
                'images' => [
                    'lg' => $category->lg_img, // Katta rasm uchun URL
                    'md' => $category->md_img, // O‘rta rasm uchun URL
                    'sm' => $category->sm_img, // Kichik rasm uchun URL
                ],
                'in_main' => $category->in_main,
                'view' => $category->view,
                'slug' => $category->slug,
            ];
        });

        return response()->json([
            'data' => $translatedPosts,              // Tilga mos kategoriyalar
            'total' => $categories->total(),        // Umumiy kategoriyalar soni
            'per_page' => $categories->perPage(),   // Har bir sahifadagi kategoriyalar soni
            'current_page' => $categories->currentPage(), // Hozirgi sahifa raqami
            'last_page' => $categories->lastPage(), // Oxirgi sahifa raqami
            'next_page_url' => $categories->nextPageUrl(), // Keyingi sahifa URLi
            'prev_page_url' => $categories->previousPageUrl(), // Oldingi sahifa URLi
        ]);
    }

    public function show_categories($slug)
    {
        // Foydalanuvchi tilini olish
        $locale = App::getLocale();

        // Kategoriyani slug orqali topish
        $category = PostsCategory::where('slug', $slug)->first();

        // Agar kategoriya topilmasa, xato xabarini qaytarish
        if (is_null($category)) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Shu kategoriyaga tegishli postlarni olish
        $posts = Post::latest()
            ->whereHas('postsCategories', function ($query) use ($category) {
                $query->where('posts_category_id', $category->id);
            }) // Kategoriyaga tegishli postlarni olish
            ->whereNull('video_link') // Faqat video_link null bo‘lgan postlarni olish
            ->with('postImages') // Postlarga bog‘liq rasmlarni olish
            ->paginate(10);

        // Agar postlar bo‘lmasa, 404 xatolikni qaytaradi
        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No records found'
            ], 404);
        }

        // Postlarni foydalanuvchi tiliga moslashtirish
        $translatedPosts = $posts->map(function ($post) use ($locale) {
            return [
                'id' => $post->id,
                'title' => $post->title[$locale] ?? null,
                'desc' => $post->desc[$locale] ?? null,
                'images' => $post->postImages->map(function ($image) {
                    return [
                        'lg' => $image->lg_img,
                        'md' => $image->md_img,
                        'sm' => $image->sm_img,
                    ];
                })->toArray(),
                'date' => $post->date,
                'views_count' => $post->views_count,
                'slug' => $post->slug,
                'meta_keywords' => $post->meta_keywords,
            ];
        });

        // Kategoriya va postlar ma’lumotlarini qaytarish
        return response()->json([
            'category' => [
                'id' => $category->id,
                'title' => $category->title[$locale] ?? null,
                'desc' => $category->desc[$locale] ?? null,
                'images' => [
                    'lg' => $category->lg_img,
                    'md' => $category->md_img,
                    'sm' => $category->sm_img,
                ],
                'in_main' => $category->in_main,
                'view' => $category->view,
                'slug' => $category->slug,
            ],
            'posts' => [
                'data' => $translatedPosts, // Tilga mos postlar
                'total' => $posts->total(), // Umumiy postlar soni
                'per_page' => $posts->perPage(), // Har bir sahifadagi postlar soni
                'current_page' => $posts->currentPage(), // Hozirgi sahifa raqami
                'last_page' => $posts->lastPage(), // Oxirgi sahifa raqami
                'next_page_url' => $posts->nextPageUrl(), // Keyingi sahifa URL
                'prev_page_url' => $posts->previousPageUrl(), // Oldingi sahifa URL
            ]
        ]);
    }
}
