<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmployMeta;
use Illuminate\Http\Request;

class LeadershipController extends Controller
{
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




    public function getRahbariyatEmployees()
    {
        $locale = app()->getLocale(); // Get the current locale

        // Fetch all employees in the "Rahbariyat" department
        $employees = EmployMeta::with([
            'department.structureType',
            'department.parent',
            'position',
            'employ',
        ])
            ->whereHas('department', function ($query) {
                $query->where('id', 2); // Filter by department ID
            })
            ->get();

        // Group employees by department and map the response
        $response = $employees->groupBy('department.id')->map(function ($group) use ($locale) {
            $department = $group->first()->department;

            return [
                'id' => $department->id,
                'name' => $department->name[$locale] ?? $department->name,
                'structure_type' => $department->structureType ? [
                    'id' => $department->structureType->id,
                    'name' => $department->structureType->name[$locale] ?? $department->structureType->name,
                ] : null,
                'parent' => $department->parent ? [
                    'id' => $department->parent->id,
                    'name' => $department->parent->name[$locale] ?? $department->parent->name,
                ] : null,
                'active' => $department->active,
                'code' => $department->code,

                // Professor employees
                'professor_employ' => $group->filter(function ($employee) {
                    return $employee->position->id === 4; // Filter by position ID
                })->map(function ($employee) use ($locale) {
                    return [
                        'id' => $employee->id,
                        'id_employ' => $employee->employ->id,
                        'first_name' => $employee->employ->first_name[$locale],
                        'last_name' => $employee->employ->last_name[$locale],
                        'surname' => $employee->employ->surname[$locale],
                        'email' => $employee->employ->email,
                        'address' => $employee->employ->address[$locale],
                        'status' => $employee->employ->status,
                        'birthday' => $employee->employ->birthday,
                        'gender' => $employee->employ->gender,
                        'special' => $employee->employ->special,
                        'photo' => $employee->employ->photo ? url('/upload/images/' . $employee->employ->photo) : null,
                        'phone' => $employee->employ->phone,
                        'dec' => $employee->employ->dec[$locale] ?? $employee->employ->dec,
                        'started_work' => $employee->employ->started_work,
                        'leader' => $employee->employ->leader,
                        'professor' => $employee->employ->professor,
                        'department_id' => $employee->department_id,
                        'position_id' => $employee->position_id,
                        'employ_staff_id' => $employee->employ_staff_id,
                        'employ_form_id' => $employee->employ_form_id,
                        'employ_type_id' => $employee->employ_type_id,
                        'active' => $employee->active,
                        'contrakt_date' => $employee->contrakt_date,
                        'contrakt_number' => $employee->contrakt_number,

                        'employ_form' => $employee->employ_form->name[$locale],
                        'employ_staff' => $employee->employ_staff->name[$locale],
                        'employ_type' => $employee->employ_type->name[$locale],
                    ];
                })->values(),

                // Management employees
                'manage_employ' => $group->filter(function ($employee) {
                    return $employee->position->id === 5; // Filter by position ID
                })->map(function ($employee) use ($locale) {
                    return [
                        'id' => $employee->id,
                        'id_employ' => $employee->employ->id,
                        'first_name' => $employee->employ->first_name[$locale],
                        'last_name' => $employee->employ->last_name[$locale],
                        'surname' => $employee->employ->surname[$locale],
                        'email' => $employee->employ->email,
                        'address' => $employee->employ->address[$locale],
                        'status' => $employee->employ->status,
                        'birthday' => $employee->employ->birthday,
                        'gender' => $employee->employ->gender,
                        'special' => $employee->employ->special,
                        'photo' => $employee->employ->photo ? url('/upload/images/' . $employee->employ->photo) : null,
                        'phone' => $employee->employ->phone,
                        'dec' => $employee->employ->dec[$locale] ?? $employee->employ->dec,
                        'started_work' => $employee->employ->started_work,
                        'leader' => $employee->employ->leader,
                        'professor' => $employee->employ->professor,
                        'department_id' => $employee->department_id,
                        'position_id' => $employee->position_id,
                        'employ_staff_id' => $employee->employ_staff_id,
                        'employ_form_id' => $employee->employ_form_id,
                        'employ_type_id' => $employee->employ_type_id,
                        'active' => $employee->active,
                        'contrakt_date' => $employee->contrakt_date,
                        'contrakt_number' => $employee->contrakt_number,

                        'employ_form' => $employee->employ_form->name[$locale],
                        'employ_staff' => $employee->employ_staff->name[$locale],
                        'employ_type' => $employee->employ_type->name[$locale],
                    ];
                })->values(),
            ];
        })->values();

        return response()->json($response);
    }
    public function getEmployeeDetails($id)
    {
        $locale = app()->getLocale(); // Get the current locale

        // Fetch the employee by ID, along with the related models
        $employee = EmployMeta::with([
            'department.structureType',
            'department.parent',
            'position',
            'employ',
            'employ_form',
            'employ_staff',
            'employ_type',
        ])
            ->findOrFail($id); // This will return 404 if not found

        // Map the employee details to the response structure
        $response = [
            'id' => $employee->id,
            'first_name' => $employee->employ->first_name[$locale],
            'last_name' => $employee->employ->last_name[$locale],
            'surname' => $employee->employ->surname[$locale],
            'email' => $employee->employ->email,
            'address' => $employee->employ->address[$locale],
            'status' => $employee->employ->status,
            'birthday' => $employee->employ->birthday,
            'gender' => $employee->employ->gender,
            'special' => $employee->employ->special,
            'photo' => $employee->employ->photo ? url('/upload/images/' . $employee->employ->photo) : null,
            'phone' => $employee->employ->phone,
            'dec' => $employee->employ->dec[$locale] ?? $employee->employ->dec,
            'started_work' => $employee->employ->started_work,
            'leader' => $employee->employ->leader,
            'professor' => $employee->employ->professor,
            'department' => [
                'id' => $employee->department->id,
                'name' => $employee->department->name[$locale] ?? $employee->department->name,
                'structure_type' => $employee->department->structureType ? [
                    'id' => $employee->department->structureType->id,
                    'name' => $employee->department->structureType->name[$locale] ?? $employee->department->structureType->name,
                ] : null,
                'parent' => $employee->department->parent ? [
                    'id' => $employee->department->parent->id,
                    'name' => $employee->department->parent->name[$locale] ?? $employee->department->parent->name,
                ] : null,
                'active' => $employee->department->active,
                'code' => $employee->department->code,
            ],
            'position' => [
                'id' => $employee->position->id,
                'name' => $employee->position->name[$locale] ?? $employee->position->name,
            ],
            'contrakt_date' => $employee->contrakt_date,
            'contrakt_number' => $employee->contrakt_number,
            'employ_form' => $employee->employ_form->name[$locale],
            'employ_staff' => $employee->employ_staff->name[$locale],
            'employ_type' => $employee->employ_type->name[$locale],
        ];

        return response()->json($response);
    }


    public function getDepartmentEmployees()
    {
        $locale = app()->getLocale(); // Get the current locale

        // Fetch all employees in the "Rahbariyat" department
        $employees = EmployMeta::with([
            'department.structureType',
            'department.parent',
            'position',
            'employ',
        ])
            ->whereHas('department', function ($query) {
                $query->where('id', 1); // Filter by department ID
            })
            ->get();

        // Group employees by department and map the response
        $response = $employees->groupBy('department.id')->map(function ($group) use ($locale) {
            $department = $group->first()->department;

            return [
                'id' => $department->id,
                'name' => $department->name[$locale] ?? $department->name,
                'structure_type' => $department->structureType ? [
                    'id' => $department->structureType->id,
                    'name' => $department->structureType->name[$locale] ?? $department->structureType->name,
                ] : null,
                'parent' => $department->parent ? [
                    'id' => $department->parent->id,
                    'name' => $department->parent->name[$locale] ?? $department->parent->name,
                ] : null,
                'active' => $department->active,
                'code' => $department->code,

                // Professor employees
                'professor_employ' => $group->filter(function ($employee) {
                    return $employee->position->id === 4; // Filter by position ID for professors
                })->map(function ($employee) use ($locale, $group) {
                    return [
                        'id' => $employee->id,
                        'id_employ' => $employee->employ->id,
                        'first_name' => $employee->employ->first_name[$locale],
                        'last_name' => $employee->employ->last_name[$locale],
                        'surname' => $employee->employ->surname[$locale],
                        'email' => $employee->employ->email,
                        'address' => $employee->employ->address[$locale],
                        'status' => $employee->employ->status,
                        'birthday' => $employee->employ->birthday,
                        'gender' => $employee->employ->gender,
                        'special' => $employee->employ->special,
                        'photo' => $employee->employ->photo ? url('/upload/images/' . $employee->employ->photo) : null,
                        'phone' => $employee->employ->phone,
                        'dec' => $employee->employ->dec[$locale] ?? $employee->employ->dec,
                        'started_work' => $employee->employ->started_work,
                        'leader' => $employee->employ->leader,
                        'professor' => $employee->employ->professor,
                        'department_id' => $employee->department_id,
                        'position_id' => $employee->position_id,
                        'employ_staff_id' => $employee->employ_staff_id,
                        'employ_form_id' => $employee->employ_form_id,
                        'employ_type_id' => $employee->employ_type_id,
                        'active' => $employee->active,
                        'contrakt_date' => $employee->contrakt_date,
                        'contrakt_number' => $employee->contrakt_number,

                        'employ_form' => $employee->employ_form->name[$locale],
                        'employ_staff' => $employee->employ_staff->name[$locale],
                        'employ_type' => $employee->employ_type->name[$locale],
                        'position' => $employee->position->id,

                        // Add manage_employ list inside the professor_employ array
                        'manage_employ' => $group->filter(function ($employee) {
                            return $employee->position->id === 7; // Filter by position ID for managers
                        })->map(function ($employee) use ($locale) {
                            return [
                                'id' => $employee->id,
                                'id_employ' => $employee->employ->id,
                                'first_name' => $employee->employ->first_name[$locale],
                                'last_name' => $employee->employ->last_name[$locale],
                                'surname' => $employee->employ->surname[$locale],
                                'email' => $employee->employ->email,
                                'address' => $employee->employ->address[$locale],
                                'status' => $employee->employ->status,
                                'birthday' => $employee->employ->birthday,
                                'gender' => $employee->employ->gender,
                                'special' => $employee->employ->special,
                                'photo' => $employee->employ->photo ? url('/upload/images/' . $employee->employ->photo) : null,
                                'phone' => $employee->employ->phone,
                                'dec' => $employee->employ->dec[$locale] ?? $employee->employ->dec,
                                'started_work' => $employee->employ->started_work,
                                'leader' => $employee->employ->leader,
                                'professor' => $employee->employ->professor,
                                'department_id' => $employee->department_id,
                                'position_id' => $employee->position_id,
                                'employ_staff_id' => $employee->employ_staff_id,
                                'employ_form_id' => $employee->employ_form_id,
                                'employ_type_id' => $employee->employ_type_id,
                                'active' => $employee->active,
                                'contrakt_date' => $employee->contrakt_date,
                                'contrakt_number' => $employee->contrakt_number,

                                'employ_form' => $employee->employ_form->name[$locale],
                                'employ_staff' => $employee->employ_staff->name[$locale],
                                'employ_type' => $employee->employ_type->name[$locale],
                            ];
                        })->values(),
                    ];
                })->values(),


                // Management employees

            ];
        })->values();

        return response()->json($response);
    }
    public function showDepartmentEmployees($id)
    {
        $locale = app()->getLocale(); // Get the current locale

        // Fetch the department and its employees based on the provided ID
        $employees = EmployMeta::with([
            'department.structureType',
            'department.parent',
            'position',
            'employ',
        ])
            ->whereHas('department', function ($query) use ($id) {
                $query->where('id', 1); // Filter by the provided department ID
            })
            ->get();

        if ($employees->isEmpty()) {
            return response()->json(['message' => 'Department not found or has no employees.'], 404);
        }

        // Group employees by department and map the response
        $response = $employees->groupBy('department.id')->map(function ($group) use ($locale) {
            $department = $group->first()->department;

            return [
                'id' => $department->id,
                'name' => $department->name[$locale] ?? $department->name,
                'structure_type' => $department->structureType ? [
                    'id' => $department->structureType->id,
                    'name' => $department->structureType->name[$locale] ?? $department->structureType->name,
                ] : null,
                'parent' => $department->parent ? [
                    'id' => $department->parent->id,
                    'name' => $department->parent->name[$locale] ?? $department->parent->name,
                ] : null,
                'active' => $department->active,
                'code' => $department->code,

                // Professor employees
                'professor_employ' => $group->filter(function ($employee) {
                    return $employee->position->id === 4; // Filter by position ID for professors
                })->map(function ($employee) use ($locale, $group) {
                    return [
                        'id' => $employee->id,
                        'id_employ' => $employee->employ->id,
                        'first_name' => $employee->employ->first_name[$locale],
                        'last_name' => $employee->employ->last_name[$locale],
                        'surname' => $employee->employ->surname[$locale],
                        'email' => $employee->employ->email,
                        'address' => $employee->employ->address[$locale],
                        'status' => $employee->employ->status,
                        'birthday' => $employee->employ->birthday,
                        'gender' => $employee->employ->gender,
                        'special' => $employee->employ->special,
                        'photo' => $employee->employ->photo ? url('/upload/images/' . $employee->employ->photo) : null,
                        'phone' => $employee->employ->phone,
                        'dec' => $employee->employ->dec[$locale] ?? $employee->employ->dec,
                        'started_work' => $employee->employ->started_work,
                        'leader' => $employee->employ->leader,
                        'professor' => $employee->employ->professor,
                        'department_id' => $employee->department_id,
                        'position_id' => $employee->position_id,
                        'employ_staff_id' => $employee->employ_staff_id,
                        'employ_form_id' => $employee->employ_form_id,
                        'employ_type_id' => $employee->employ_type_id,
                        'active' => $employee->active,
                        'contrakt_date' => $employee->contrakt_date,
                        'contrakt_number' => $employee->contrakt_number,
                        'employ_form' => $employee->employ_form->name[$locale],
                        'employ_staff' => $employee->employ_staff->name[$locale],
                        'employ_type' => $employee->employ_type->name[$locale],
                        'position' => $employee->position->id,

                        // Nested manage_employ list inside the professor_employ array
                        'manage_employ' => $group->filter(function ($manager) {
                            return $manager->position->id === 7; // Filter by position ID for managers
                        })->map(function ($manager) use ($locale) {
                            return [
                                'id' => $manager->id,
                                'id_employ' => $manager->employ->id,
                                'first_name' => $manager->employ->first_name[$locale],
                                'last_name' => $manager->employ->last_name[$locale],
                                'surname' => $manager->employ->surname[$locale],
                                'email' => $manager->employ->email,
                                'address' => $manager->employ->address[$locale],
                                'status' => $manager->employ->status,
                                'birthday' => $manager->employ->birthday,
                                'gender' => $manager->employ->gender,
                                'special' => $manager->employ->special,
                                'photo' => $manager->employ->photo ? url('/upload/images/' . $manager->employ->photo) : null,
                                'phone' => $manager->employ->phone,
                                'dec' => $manager->employ->dec[$locale] ?? $manager->employ->dec,
                                'started_work' => $manager->employ->started_work,
                                'leader' => $manager->employ->leader,
                                'professor' => $manager->employ->professor,
                                'department_id' => $manager->department_id,
                                'position_id' => $manager->position_id,
                                'employ_staff_id' => $manager->employ_staff_id,
                                'employ_form_id' => $manager->employ_form_id,
                                'employ_type_id' => $manager->employ_type_id,
                                'active' => $manager->active,
                                'contrakt_date' => $manager->contrakt_date,
                                'contrakt_number' => $manager->contrakt_number,
                                'employ_form' => $manager->employ_form->name[$locale],
                                'employ_staff' => $manager->employ_staff->name[$locale],
                                'employ_type' => $manager->employ_type->name[$locale],
                            ];
                        })->values(),
                    ];
                })->values(),
            ];
        })->first(); // Use first() since we are fetching a single department

        return response()->json($response);
    }



}
