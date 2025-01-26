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
            'slug' => $program->slug,
            'code' => $program->code,
            'lang' => $program->lang[$locale] ?? null,
            'active' => $program->active,
            'date' => $program->date,
            'lg_img' => $program->lg_img,
            'md_img' => $program->md_img,
            'sm_img' => $program->sm_img,
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

    public function get_employ_meta(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale()); // Get the locale for translations

        // Retrieve all EmployMeta data with their relationships
        $employMetas = EmployMeta::with([
            'employ',
            'department',
            'position',
            'employ_form',
            'employ_staff',
            'employ_type'
        ])->get();

        // Map through the data and localize the response
        $translatedEmployMetas = $employMetas->map(function ($employMeta) use ($locale) {
            return [
                'id' => $employMeta->id,
                'employ_id' => $employMeta->employ_id,
                'department_id' => $employMeta->department_id,
                'position_id' => $employMeta->position_id,
                'employ_staff_id' => $employMeta->employ_staff_id,
                'employ_form_id' => $employMeta->employ_form_id,
                'contrakt_date' => $employMeta->contrakt_date,
                'contrakt_number' => $employMeta->contrakt_number,
                'employ_type_id' => $employMeta->employ_type_id,
                'active' => $employMeta->active,
                'employ' => $employMeta->employ ? [
                    'id' => $employMeta->employ->id,
                    'first_name' => $employMeta->employ->first_name[$locale],
                    'last_name' => $employMeta->employ->last_name[$locale],
                    'surname' => $employMeta->employ->surname[$locale],
                    'email' => $employMeta->employ->email,
                    'address' => $employMeta->employ->address[$locale],
                    'status' => $employMeta->employ->status,
                    'birthday' => $employMeta->employ->birthday,
                    'gender' => $employMeta->employ->gender,
                    'special' => $employMeta->employ->special,
                    'photo' => $employMeta->employ->photo ? url('/upload/images/' . $employMeta->employ->photo) : null,
                    'phone' => $employMeta->employ->phone,
                    'dec' => $employMeta->employ->dec[$locale] ?? $employMeta->employ->dec,
                    'started_work' => $employMeta->employ->started_work,
                    'leader' => $employMeta->employ->leader,
                    'professor' => $employMeta->employ->professor,
                ] : null,
                'department' => $employMeta->department ? [
                    'id' => $employMeta->department->id,
                    'name' => $employMeta->department->name[$locale] ?? $employMeta->department->name,
                    'structure_type' => $employMeta->department->structureType ? [
                        'id' => $employMeta->department->structureType->id,
                        'name' => $employMeta->department->structureType->name[$locale] ?? $employMeta->department->structureType->name,
                    ] : null,
                    'parent' => $employMeta->department->parent ? [
                        'id' => $employMeta->department->parent->id,
                        'name' => $employMeta->department->parent->name[$locale] ?? $employMeta->department->parent->name,
                    ] : null,
                    'children' => $employMeta->department->children->map(function ($child) use ($locale) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name[$locale] ?? $child->name,
                            'active' => $child->active,
                            'code' => $child->code,
                        ];
                    }),
                    'active' => $employMeta->department->active,
                    'code' => $employMeta->department->code,
                ] : null,
                'position' => $employMeta->position ? [
                    'id' => $employMeta->position->id,
                    'name' => $employMeta->position->name[$locale] ?? $employMeta->position->name,
                ] : null,
                'employ_form' => $employMeta->employ_form ? [
                    'id' => $employMeta->employ_form->id,
                    'name' => $employMeta->employ_form->name[$locale] ?? $employMeta->employ_form->name,
                ] : null,
                'employ_staff' => $employMeta->employ_staff ? [
                    'id' => $employMeta->employ_staff->id,
                    'name' => $employMeta->employ_staff->name[$locale] ?? $employMeta->employ_staff->name,
                ] : null,
                'employ_type' => $employMeta->employ_type ? [
                    'id' => $employMeta->employ_type->id,
                    'name' => $employMeta->employ_type->name[$locale] ?? $employMeta->employ_type->name,
                ] : null,
            ];
        });

        return response()->json($translatedEmployMetas);
    }

    /**
     * Get a single EmployMeta with relationships.
     */
    public function show_employ_meta(Request $request, $id)
    {
        $locale = $request->get('locale', app()->getLocale()); // Get the locale for translations

        // Retrieve a single EmployMeta record with relationships
        $employMeta = EmployMeta::with([
            'employ',
            'department.structureType',
            'department.parent',
            'department.children',
            'position',
            'employ_form',
            'employ_staff',
            'employ_type'
        ])->findOrFail($id);

        // Localize and format the response
        $translatedEmployMeta = [
            'id' => $employMeta->id,
            'employ_id' => $employMeta->employ_id,
            'department_id' => $employMeta->department_id,
            'position_id' => $employMeta->position_id,
            'employ_staff_id' => $employMeta->employ_staff_id,
            'employ_form_id' => $employMeta->employ_form_id,
            'contrakt_date' => $employMeta->contrakt_date,
            'contrakt_number' => $employMeta->contrakt_number,
            'employ_type_id' => $employMeta->employ_type_id,
            'active' => $employMeta->active,
            'employ' => $employMeta->employ ? [
                'id' => $employMeta->employ->id,
                'first_name' => $employMeta->employ->first_name[$locale],
                'last_name' => $employMeta->employ->last_name[$locale],
                'surname' => $employMeta->employ->surname[$locale],
                'email' => $employMeta->employ->email,
                'address' => $employMeta->employ->address[$locale],
                'status' => $employMeta->employ->status,
                'birthday' => $employMeta->employ->birthday,
                'gender' => $employMeta->employ->gender,
                'special' => $employMeta->employ->special,
                'photo' => $employMeta->employ->photo ? url('/upload/images/' . $employMeta->employ->photo) : null,
                'phone' => $employMeta->employ->phone,
                'dec' => $employMeta->employ->dec[$locale] ?? $employMeta->employ->dec,
                'started_work' => $employMeta->employ->started_work,
                'leader' => $employMeta->employ->leader,
                'professor' => $employMeta->employ->professor,
            ] : null,
            'department' => $employMeta->department ? [
                'id' => $employMeta->department->id,
                'name' => $employMeta->department->name[$locale] ?? $employMeta->department->name,
                'structure_type' => $employMeta->department->structureType ? [
                    'id' => $employMeta->department->structureType->id,
                    'name' => $employMeta->department->structureType->name[$locale] ?? $employMeta->department->structureType->name,
                ] : null,
                'parent' => $employMeta->department->parent ? [
                    'id' => $employMeta->department->parent->id,
                    'name' => $employMeta->department->parent->name[$locale] ?? $employMeta->department->parent->name,
                ] : null,
                'children' => $employMeta->department->children->map(function ($child) use ($locale) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name[$locale] ?? $child->name,
                        'active' => $child->active,
                        'code' => $child->code,
                    ];
                }),
                'active' => $employMeta->department->active,
                'code' => $employMeta->department->code,
            ] : null,
            'position' => $employMeta->position ? [
                'id' => $employMeta->position->id,
                'name' => $employMeta->position->name[$locale] ?? $employMeta->position->name,
            ] : null,
            'employ_form' => $employMeta->employ_form ? [
                'id' => $employMeta->employ_form->id,
                'name' => $employMeta->employ_form->name[$locale] ?? $employMeta->employ_form->name,
            ] : null,
            'employ_staff' => $employMeta->employ_staff ? [
                'id' => $employMeta->employ_staff->id,
                'name' => $employMeta->employ_staff->name[$locale] ?? $employMeta->employ_staff->name,
            ] : null,
            'employ_type' => $employMeta->employ_type ? [
                'id' => $employMeta->employ_type->id,
                'name' => $employMeta->employ_type->name[$locale] ?? $employMeta->employ_type->name,
            ] : null,
        ];

        return response()->json($translatedEmployMeta);
    }


}
