<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EntranceRequirement;
use App\Models\EducationalProgram;
use App\Models\EmployMeta;

class EducationalProgramsController extends Controller
{


    public function get_educational_programs(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale()); // Get the locale for translations

        // Retrieve all parent educational programs with their relationships
        $programs = EducationalProgram::whereNull('parent_id')
            ->with(['children', 'employs', 'activity', 'faq', 'EntranceRequirement'])
            ->get();

        // Localize and structure the response
        $translatedPrograms = $programs->map(function ($program) use ($locale) {
            return [
                'id' => $program->id,
                'name' => $program->name[$locale] ?? null,
                'slug' => $program->slug,
                'active' => $program->active,
                'children' => $program->children->map(function ($child) use ($locale) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name[$locale] ?? null,
                        'first_name' => $child->first_name[$locale] ?? null,
                        'second_name'=> $child->second_name[$locale] ?? null,
                        'third_name'=> $child->third_name[$locale] ?? null,
                        'parent_id'=> $child->parent_id ?? null,
                        'slug'=> $child->slug ?? null,
                        'map'=> $child->map[$locale] ?? null,
                        'active'=> $child->active ?? null,
                        'form_education'=> $child->form_education[$locale] ?? null,
                        'daytime'=> $child->daytime ?? null,
                        'part_time' => $child->part_time ?? null,
                        'first_descriptionv' => $child->first_descriptionv[$locale] ?? null,
                        'second_description'=> $child->second_description[$locale] ?? null,
                        'third_description' => $child->third_description[$locale] ?? null,
                        'icon' => $child->icon,
                        'code' => $child->code,
                        'lang' => $child->lang[$locale] ?? null,
                        'date' => $child->date,

                        'employs' => $child->employs->map(function ($employ) use ($locale) {
                            return [
                                'id' => $employ->id,
                                'name' => $employ->first_name[$locale] ?? $employ->first_name, // Add localization if applicable
                                'dec' => $employ->dec[$locale] ?? $employ->first_name, // Add localization if applicable
                            ];
                        }),
                        'activity' => $child->activity ? [
                            'id' => $child->activity->id,
                            'title' => $child->activity->title[$locale],
                            'dec' => $child->activity->dec[$locale],
                            'dec' => $child->activity->dec[$locale],
                            'photo' => [
                                'lg' => $child->activity->photo ? url('/upload/images/' . $child->activity->photo) : null, // Katta o'lchamdagi rasm
                                'md' => $child->activity->photo ? url('/upload/images/600/' . $child->activity->photo) : null, // O'rtacha o'lchamdagi rasm
                                'sm' => $child->activity->photo ? url('/upload/images/200/' . $child->activity->photo) : null, // Kichik o'lchamdagi rasm
                            ],
                        ] : null,
                        'entrance_requirement' => $child->EntranceRequirement ? [
                            'id' => $child->EntranceRequirement->id,
                            'name' => $child->EntranceRequirement->name[$locale] ?? $child->EntranceRequirement->name,
                            'dec' => $child->EntranceRequirement->dec[$locale] ?? $child->EntranceRequirement->dec,
                            'photo' => [
                                'lg' => $child->EntranceRequirement->photo ? url('/upload/images/' . $child->EntranceRequirement->photo) : null, // Katta o'lchamdagi rasm
                                'md' => $child->EntranceRequirement->photo ? url('/upload/images/600/' . $child->EntranceRequirement->photo) : null, // O'rtacha o'lchamdagi rasm
                                'sm' => $child->EntranceRequirement->photo ? url('/upload/images/200/' . $child->EntranceRequirement->photo) : null, // Kichik o'lchamdagi rasm
                            ],
                            'skills' => $child->EntranceRequirement->skills ? $child->EntranceRequirement->skills->filter(function ($skill) {
                                // Faqat yangi qo‘shilganlarini chiqarish (misol uchun, oxirgi 7 kun ichida qo‘shilganlar)
                                return $skill->created_at >= now()->subDays(7);
                            })->map(function ($skill) use ($locale) {
                                return [
                                    'id' => $skill->id,
                                    'name' => $skill->name[$locale] ?? $skill->name,
                                ];
                            }) : [],
                        ] : null,


                        'faq' => $child->faq ? $child->faq->map(function ($faq) use ($locale) {
                            return [
                                'id' => $faq->id,
                                'skills' => $faq->skill ? [
                                    'id' => $faq->skill->id,
                                    'name' => $faq->skill->name[$locale] ?? $faq->skill->name,
                                ] : null,
                                'question' => $faq->question[$locale] ?? null,
                                'answer' => $faq->answer[$locale] ?? null,
                            ];
                        }) : null,
                    ];
                }),
            ];
        });

        return response()->json($translatedPrograms);
    }

    public function show_educational_program(Request $request, $id)
    {
        $locale = $request->get('locale', app()->getLocale()); // Get the locale for translations

        // Retrieve the specific educational program with its relationships
        $program = EducationalProgram::where('id', $id)
            ->orWhere('slug', $id)
            ->with(['children', 'employs', 'activity', 'faq', 'EntranceRequirement'])
            ->first();

        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }

        // Localize and structure the response
        $translatedProgram = [
            'id' => $program->id,
            'name' => $program->name[$locale] ?? null,
            'first_name' => $program->first_name[$locale] ?? null,
            'second_name'=> $program->second_name[$locale] ?? null,
            'third_name'=> $program->third_name[$locale] ?? null,
            'parent_id'=> $program->parent_id ?? null,
            'slug'=> $program->slug ?? null,
            'map'=> $program->map[$locale] ?? null,
            'active'=> $program->active ?? null,
            'form_education'=> $program->form_education[$locale] ?? null,
            'daytime'=> $program->daytime ?? null,
            'part_time' => $program->part_time ?? null,
            'first_descriptionv' => $program->first_descriptionv[$locale] ?? null,
            'second_description'=> $program->second_description[$locale] ?? null,
            'third_description' => $program->third_description[$locale] ?? null,
            'icon' => $program->icon,
            'code' => $program->code,
            'lang' => $program->lang[$locale] ?? null,
            'date' => $program->date,
            'employs' => $program->employs->map(function ($employ) use ($locale) {
                return [
                    'id' => $employ->id,
                    'name' => $employ->first_name[$locale] ?? $employ->first_name,
                    'dec' => $employ->dec[$locale] ?? $employ->first_name,
                ];
            }),
            'activity' => $program->activity ? [
                'id' => $program->activity->id,
                'title' => $program->activity->title[$locale],
                'dec' => $program->activity->dec[$locale],
                'photo' => [
                    'lg' => $program->activity->photo ? url('/upload/images/' . $program->activity->photo) : null,
                    'md' => $program->activity->photo ? url('/upload/images/600/' . $program->activity->photo) : null,
                    'sm' => $program->activity->photo ? url('/upload/images/200/' . $program->activity->photo) : null,
                ],
            ] : null,
            'entrance_requirement' => $program->EntranceRequirement ? [
                'id' => $program->EntranceRequirement->id,
                'name' => $program->EntranceRequirement->name[$locale] ?? $program->EntranceRequirement->name,
                'dec' => $program->EntranceRequirement->dec[$locale] ?? $program->EntranceRequirement->dec,
                'photo' => [
                    'lg' => $program->EntranceRequirement->photo ? url('/upload/images/' . $program->EntranceRequirement->photo) : null,
                    'md' => $program->EntranceRequirement->photo ? url('/upload/images/600/' . $program->EntranceRequirement->photo) : null,
                    'sm' => $program->EntranceRequirement->photo ? url('/upload/images/200/' . $program->EntranceRequirement->photo) : null,
                ],
                'skills' => $program->EntranceRequirement->skills ? $program->EntranceRequirement->skills->filter(function ($skill) {
                    return $skill->created_at >= now()->subDays(7);
                })->map(function ($skill) use ($locale) {
                    return [
                        'id' => $skill->id,
                        'name' => $skill->name[$locale] ?? $skill->name,
                    ];
                }) : [],
            ] : null,
            'faq' => $program->faq ? $program->faq->map(function ($faq) use ($locale) {
                return [
                    'id' => $faq->id,
                    'skills' => $faq->skill ? [
                        'id' => $faq->skill->id,
                        'name' => $faq->skill->name[$locale] ?? $faq->skill->name,
                    ] : null,
                    'question' => $faq->question[$locale] ?? null,
                    'answer' => $faq->answer[$locale] ?? null,
                ];
            }) : null,
            'children' => $program->children->map(function ($child) use ($locale) {
                return [
                    'id' => $child->id,
                    'name' => $child->name[$locale] ?? null,
                    'slug' => $child->slug,
                    'code' => $child->code,
                    'lang' => $child->lang[$locale] ?? null,
                    'active' => $child->active,
                    'date' => $child->date,
                    'lg_img' => $child->lg_img,
                    'md_img' => $child->md_img,
                    'sm_img' => $child->sm_img,
                    'employs' => $child->employs->map(function ($employ) use ($locale) {
                        return [
                            'id' => $employ->id,
                            'name' => $employ->first_name[$locale] ?? $employ->first_name,
                            'dec' => $employ->dec[$locale] ?? $employ->first_name,
                        ];
                    }),
                    'activity' => $child->activity ? [
                        'id' => $child->activity->id,
                        'title' => $child->activity->title[$locale],
                        'dec' => $child->activity->dec[$locale],
                        'photo' => [
                            'lg' => $child->activity->photo ? url('/upload/images/' . $child->activity->photo) : null,
                            'md' => $child->activity->photo ? url('/upload/images/600/' . $child->activity->photo) : null,
                            'sm' => $child->activity->photo ? url('/upload/images/200/' . $child->activity->photo) : null,
                        ],
                    ] : null,
                    'entrance_requirement' => $child->EntranceRequirement ? [
                        'id' => $child->EntranceRequirement->id,
                        'name' => $child->EntranceRequirement->name[$locale] ?? $child->EntranceRequirement->name,
                        'dec' => $child->EntranceRequirement->dec[$locale] ?? $child->EntranceRequirement->dec,
                        'photo' => [
                            'lg' => $child->EntranceRequirement->photo ? url('/upload/images/' . $child->EntranceRequirement->photo) : null,
                            'md' => $child->EntranceRequirement->photo ? url('/upload/images/600/' . $child->EntranceRequirement->photo) : null,
                            'sm' => $child->EntranceRequirement->photo ? url('/upload/images/200/' . $child->EntranceRequirement->photo) : null,
                        ],
                        'skills' => $child->EntranceRequirement->skills ? $child->EntranceRequirement->skills->filter(function ($skill) {
                            return $skill->created_at >= now()->subDays(7);
                        })->map(function ($skill) use ($locale) {
                            return [
                                'id' => $skill->id,
                                'name' => $skill->name[$locale] ?? $skill->name,
                            ];
                        }) : [],
                    ] : null,
                    'faq' => $child->faq ? $child->faq->map(function ($faq) use ($locale) {
                        return [
                            'id' => $faq->id,
                            'skills' => $faq->skill ? [
                                'id' => $faq->skill->id,
                                'name' => $faq->skill->name[$locale] ?? $faq->skill->name,
                            ] : null,
                            'question' => $faq->question[$locale] ?? null,
                            'answer' => $faq->answer[$locale] ?? null,
                        ];
                    }) : null,
                ];
            }),
        ];

        return response()->json($translatedProgram);
    }






}
