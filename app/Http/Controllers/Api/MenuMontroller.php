<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\DinamikMenu;
use App\Models\FormMenu;
use Illuminate\Support\Facades\App;

class MenuMontroller extends Controller
{

//    public function get_menu(Request $request)
//    {
//        $locale = $request->get('locale', app()->getLocale()); // Lokalizatsiya uchun tilni oling
//
//        // Faqat asosiy menyuni (parent_id = null) chaqirish
//        $menus = Menu::whereNull('parent_id')->with(['children', 'dinamikMens.forms'])->get();
//
//        // Lokalizatsiya qilingan ma'lumotlarni tayyorlash
//        $translatedMenus = $menus->map(function ($menu) use ($locale) {
//            return [
//                'id' => $menu->id,
//                'title' => $menu->title[$locale] ?? null,
//                'parent_id' => $menu->parent_id,
//                'path' => $menu->path,
//                'slug' => $menu->slug,
//                'children' => $menu->children->map(function ($child) use ($locale) {
//                    return [
//                        'id' => $child->id,
//                        'title' => $child->title[$locale] ?? null,
//                        'parent_id' => $child->parent_id,
//                        'path' => $child->path,
//                        'slug' => $child->slug,
//                        'dinamikMenus' => $child->dinamikMens->map(function ($dinamikMenu) use ($locale) {
//                            return [
//                                'id' => $dinamikMenu->id,
//                                'title' => $dinamikMenu->title[$locale] ?? null,
//                                'text' => $dinamikMenu->text[$locale] ?? null,
//                                'background' => $dinamikMenu->lg_img,
//                                'short_title' => $dinamikMenu->short_title[$locale] ?? null,
//                                'forms' => $dinamikMenu->forms->map(function ($form) use ($locale) {
//                                    return [
//                                        'id' => $form->id,
//                                        'title' => $form->title[$locale] ?? null,
//                                        'text' => $form->text[$locale] ?? null,
//                                        'photo' => $form->photo,
//                                    ];
//                                }),
//                            ];
//                        }),
//                    ];
//                }),
//            ];
//        });
//
//        return response()->json($translatedMenus);
//    }


    public function get_menu(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale()); // Lokalizatsiya uchun tilni oling

        // Faqat asosiy menyuni (parent_id = null) chaqirish
        $menus = Menu::whereNull('parent_id')->with(['children', 'dinamikMens.forms'])->get();

        // Lokalizatsiya qilingan ma'lumotlarni tayyorlash
        $translatedMenus = $menus->map(function ($menu) use ($locale) {
            return [
                'id' => $menu->id,
                'title' => $menu->title[$locale] ?? null,
                'parent_id' => $menu->parent_id,
                'path' => $menu->path,
                'slug' => $menu->slug,
                'children' => $menu->children->map(function ($child) use ($locale) {
                    return [
                        'id' => $child->id,
                        'title' => $child->title[$locale] ?? null,
                        'parent_id' => $child->parent_id,
                        'path' => $child->path,
                        'slug' => $child->slug,
                        'dinamikMenus' => $child->dinamikMens->map(function ($dinamikMenu) use ($locale) {
                            // Formlarni type bo'yicha ajratish
                            $formsByType = $dinamikMenu->forms->groupBy('type')->map(function ($forms, $type) use ($locale) {
                                return $forms->map(function ($form) use ($locale) {
                                    return [
                                        'id' => $form->id,
                                        'title' => $form->title[$locale] ?? null,
                                        'text' => $form->text[$locale] ?? null,
                                        'order' => $form->order,
                                        'position' => $form->position,
                                        'type' => $form->type,
                                        'photo' => $form->formImages->map(function ($image) {
                                            return [
                                                'lg' => $image->img ? url('/upload/images/' . $image->img) : null, // Katta o'lchamdagi rasm
                                                'md' => $image->img ? url('/upload/images/600/' . $image->img) : null, // O'rtacha o'lchamdagi rasm
                                                'sm' => $image->img ? url('/upload/images/200/' . $image->img) : null, // Kichik o'lchamdagi rasm
                                            ];
                                        })->toArray(),

                                        'categories' => $form->postsmenuCategories->map(function ($category) use ($locale) {
                                            return [
                                                'id' => $category->id,
                                                'title' => $category->title[$locale] ?? null, // Lokalizatsiya qilingan nom
                                                'posts' => $category->posts->map(function ($post) use ($locale) {
                                                    return [
                                                        'id' => $post->id,
                                                        'title' => $post->title[$locale] ?? null,
                                                        'subtitle' => $post->subtitle[$locale] ?? null,
                                                        'desc' => $post->desc[$locale] ?? null,
                                                        'date' => $post->date ?? null,
                                                        'meta_keywords' => $post->meta_keywords[$locale] ?? null,
                                                        'views_count' => $post->views_count ?? null,
                                                        'slug' => $post->slug,
                                                        'images' => $post->postImages->map(function ($image) {
                                                            return [
                                                                'lg' => $image->lg_img, // Katta o'lchamdagi rasm URL
                                                                'md' => $image->md_img, // O'rta o'lchamdagi rasm URL
                                                                'sm' => $image->sm_img, // Kichik o'lchamdagi rasm URL
                                                            ];
                                                        })->toArray(),
                                                    ];
                                                }),
                                            ];
                                        }),
//                                        'images' => $form->formImages->map(function ($image) {
//                                            return [
//                                                'id' => $image->id,
//                                                'url' => $image->url,
//                                                'alt_text' => $image->alt_text,
//                                            ];
//                                        }),
                                    ];
                                });
                            });


                            return [
                                'id' => $dinamikMenu->id,
                                'title' => $dinamikMenu->title[$locale] ?? null,
                                'text' => $dinamikMenu->text[$locale] ?? null,
                                'background' => $dinamikMenu->lg_img,
                                'short_title' => $dinamikMenu->short_title[$locale] ?? null,
                                'forms' => $formsByType, // Formlarni type bo'yicha ajratilgan holda qaytarish
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json($translatedMenus);
    }
    public function show_menu($id, Request $request)
    {
        $locale = $request->get('locale', app()->getLocale()); // Get localization language

        // Fetch the specific menu item with its children and dynamic menus
        $menu = Menu::where('id', $id)
            ->with(['children', 'dinamikMens.forms.formImages', 'dinamikMens.forms.postsmenuCategories.posts.postImages'])
            ->first();

        if (!$menu) {
            return response()->json(['error' => 'Menu not found'], 404);
        }

        // Recursive function to transform menu data
        $transformMenu = function ($menu) use ($locale, &$transformMenu) {
            return [
                'id' => $menu->id,
                'title' => $menu->title[$locale] ?? null,
                'parent_id' => $menu->parent_id,
                'path' => $menu->path,
                'slug' => $menu->slug,
                'children' => $menu->children->map($transformMenu), // Recursively process children
                'dinamikMenus' => $menu->dinamikMens->map(function ($dinamikMenu) use ($locale) {
                    // Group forms by type
                    $formsByType = $dinamikMenu->forms->groupBy('type')->map(function ($forms, $type) use ($locale) {
                        return $forms->map(function ($form) use ($locale) {
                            return [
                                'id' => $form->id,
                                'title' => $form->title[$locale] ?? null,
                                'text' => $form->text[$locale] ?? null,
                                'order' => $form->order,
                                'position' => $form->position,
                                'type' => $form->type,
                                'photo' => $form->formImages->map(function ($image) {
                                    return [
                                        'lg' => $image->img ? url('/upload/images/' . $image->img) : null,
                                        'md' => $image->img ? url('/upload/images/600/' . $image->img) : null,
                                        'sm' => $image->img ? url('/upload/images/200/' . $image->img) : null,
                                    ];
                                }),
                                'categories' => $form->postsmenuCategories->map(function ($category) use ($locale) {
                                    return [
                                        'id' => $category->id,
                                        'title' => $category->title[$locale] ?? null,
                                        'posts' => $category->posts->map(function ($post) use ($locale) {
                                            return [
                                                'id' => $post->id,
                                                'title' => $post->title[$locale] ?? null,
                                                'subtitle' => $post->subtitle[$locale] ?? null,
                                                'desc' => $post->desc[$locale] ?? null,
                                                'date' => $post->date,
                                                'meta_keywords' => $post->meta_keywords[$locale] ?? null,
                                                'views_count' => $post->views_count,
                                                'slug' => $post->slug,
                                                'images' => $post->postImages->map(function ($image) {
                                                    return [
                                                        'lg' => $image->lg_img,
                                                        'md' => $image->md_img,
                                                        'sm' => $image->sm_img,
                                                    ];
                                                }),
                                            ];
                                        }),
                                    ];
                                }),
                            ];
                        });
                    });

                    return [
                        'id' => $dinamikMenu->id,
                        'title' => $dinamikMenu->title[$locale] ?? null,
                        'text' => $dinamikMenu->text[$locale] ?? null,
                        'background' => $dinamikMenu->lg_img,
                        'short_title' => $dinamikMenu->short_title[$locale] ?? null,
                        'forms' => $formsByType,
                    ];
                }),
            ];
        };

        // Transform the menu
        $translatedMenu = $transformMenu($menu);

        return response()->json($translatedMenu);
    }


}
