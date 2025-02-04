<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employ;
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
                    return $employee->position->id === 12; // Filter by position ID
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
                        'position' => $employee->position,
                        'employ_form' => $employee->employ_form->name[$locale],
                        'employ_staff' => $employee->employ_staff->name[$locale],
                        'employ_type' => $employee->employ_type->name[$locale],
                    ];
                })->values(),

                // Management employees
                'manage_employ' => $group->filter(function ($employee) {
                    return $employee->position->id === 13; // Filter by position ID
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
                        'position' => $employee->position,
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


    public function getDepartmentEmployees($id)
    {
        $locale = app()->getLocale(); // Get the current locale
        $employeeBoss = Employ::whereHas('employMeta',function ($query) use ($id){
            $query->whereHas('department', function ($subQuery) use ($id){
               $subQuery->where('id', $id);
            })->whereHas('position', function ($subQuery){
                $subQuery->where('active', 1)->where('id',4);
            });
        })->with(['employMeta'=>function ($query) {
            $query->with(['department.structureType','department.parent','position']);
        }])->first();
        $simpleEmployees = Employ::whereHas('employMeta',function ($query) use ($id){
            $query->whereHas('department', function ($subQuery) use ($id){
                $subQuery->where('id', $id);
            })->whereHas('position', function ($subQuery){
                $subQuery->where('active', 1)->where('id','!=',4);
            });
        })->with(['employMeta'=>function ($query) {
            $query->with(['department.structureType','department.parent','position']);
        }])->get();
        $employees =  [
            'department_boss' => $employeeBoss,
            'simple_employee'=> $simpleEmployees,
        ];
         return response()->json($employees);

    }
    public function getDepartmentEmployeesuser($id)
    {
        // EmployMeta modelini id orqali olish va barcha bog'liq ma'lumotlarni yuklash
        $simpleEmployee = EmployMeta::with([
            'employ',
            'department',
            'position',
            'employ_form',
            'employ_staff',
            'employ_type'
        ])->find($id);

        // Agar ma'lumot topilmasa, 404 xato qaytarish
        if (!$simpleEmployee) {
            return response()->json(['message' => 'Employ meta topilmadi'], 404);
        }

        // Topilgan ma'lumotlarni JSON formatida qaytarish
        return response()->json([
            'id' => $simpleEmployee->id,
            'first_name' => $simpleEmployee->employ->first_name ?? null,
            'last_name' => $simpleEmployee->employ->last_name ?? null,
            'surname' => $simpleEmployee->employ->surname ?? null,
            'email' => $simpleEmployee->employ->email ?? null,
            'phone' => $simpleEmployee->employ->phone ?? null,
            'birthday' => $simpleEmployee->employ->birthday ?? null,
            'gender' => $simpleEmployee->employ->gender ?? null,
            'status' => $simpleEmployee->employ->status ?? null,
            'photo' => $simpleEmployee->employ->photo ?? null,
            'department' => $simpleEmployee->department->name ?? null,
            'position' => $simpleEmployee->position->name ?? null,
            'employ_form' => $simpleEmployee->employ_form->name ?? null,
            'employ_staff' => $simpleEmployee->employ_staff->name ?? null,
            'employ_type' => $simpleEmployee->employ_type->name ?? null,
            'employ_meta' => $simpleEmployee
        ]);
    }
    public function showEmployeesByPosition(Request $request)
    {
        $locale = app()->getLocale(); // Get the current locale

        $employeeBoss = Employ::whereHas('employMeta',function ($query){
            $query->whereHas('position', function ($subQuery){
                $subQuery->where('id',4);
            });
        })->with(['employMeta'=>function ($query) {
            $query->with(['department.structureType','department.parent','position']);
        }])->get();
        return $employeeBoss;
    }


    public function getfakultet()
    {
        $locale = app()->getLocale(); // Get the current locale

        $faculted = Department::where('structure_type_id',3)->get();
        return $faculted;
    }

    public function showfakultet($id)
    {
        $locale = app()->getLocale(); // Get the current locale

        $employeeBoss = Employ::whereHas('employMeta',function ($query) use ($id){
            $query->whereHas('department', function ($subQuery) use ($id){
                $subQuery->where('id', $id);
            })->whereHas('position', function ($subQuery){
                $subQuery->where('active', 1)->where('id',8);
            });
        })->with(['employMeta'=>function ($query) {
            $query->with(['department.structureType','department.parent','position']);
        }])->first();
        $simpleEmployees = Employ::whereHas('employMeta',function ($query) use ($id){
            $query->whereHas('department', function ($subQuery) use ($id){
                $subQuery->where('id', $id);
            })->whereHas('position', function ($subQuery){
                $subQuery->where('active', 1)->where('id',9);
            });
        })->with(['employMeta'=>function ($query) {
            $query->with(['department.structureType','department.parent','position']);
        }])->get();
        $employees =  [
            'department_boss' => $employeeBoss,
            'simple_employee'=> $simpleEmployees,
        ];
        return response()->json($employees);
    }
    public function showfakultetuser($id)
    {
        // EmployMeta modelini id orqali olish va barcha bog'liq ma'lumotlarni yuklash
        $simpleEmployee = EmployMeta::with([
            'employ',
            'department',
            'position',
            'employ_form',
            'employ_staff',
            'employ_type'
        ])->find($id);

        // Agar ma'lumot topilmasa, 404 xato qaytarish
        if (!$simpleEmployee) {
            return response()->json(['message' => 'Employ meta topilmadi'], 404);
        }

        // Topilgan ma'lumotlarni JSON formatida qaytarish
        return response()->json([
            'id' => $simpleEmployee->id,
            'first_name' => $simpleEmployee->employ->first_name ?? null,
            'last_name' => $simpleEmployee->employ->last_name ?? null,
            'surname' => $simpleEmployee->employ->surname ?? null,
            'email' => $simpleEmployee->employ->email ?? null,
            'phone' => $simpleEmployee->employ->phone ?? null,
            'birthday' => $simpleEmployee->employ->birthday ?? null,
            'gender' => $simpleEmployee->employ->gender ?? null,
            'status' => $simpleEmployee->employ->status ?? null,
            'photo' => $simpleEmployee->employ->photo ?? null,
            'department' => $simpleEmployee->department->name ?? null,
            'position' => $simpleEmployee->position->name ?? null,
            'employ_form' => $simpleEmployee->employ_form->name ?? null,
            'employ_staff' => $simpleEmployee->employ_staff->name ?? null,
            'employ_type' => $simpleEmployee->employ_type->name ?? null,
            'employ_meta' => $simpleEmployee
        ]);
    }

    public function getKafedralar()
    {
        $locale = app()->getLocale(); // Get the current locale

        $faculted = Department::where('structure_type_id',4)->get();
        return $faculted;
    }

    public function showKafedralar($id)
    {
        $locale = app()->getLocale(); // Get the current locale

        $employeeBoss = Employ::whereHas('employMeta',function ($query) use ($id){
            $query->whereHas('department', function ($subQuery) use ($id){
                $subQuery->where('id', $id);
            })->whereHas('position', function ($subQuery){
                $subQuery->where('active', 1)->where('id',10);
            });
        })->with(['employMeta'=>function ($query) {
            $query->with(['department.structureType','department.parent','position']);
        }])->first();
        $simpleEmployees = Employ::whereHas('employMeta',function ($query) use ($id){
            $query->whereHas('department', function ($subQuery) use ($id){
                $subQuery->where('id', $id);
            })->whereHas('position', function ($subQuery){
                $subQuery->where('active', 1)->where('id',11);
            });
        })->with(['employMeta'=>function ($query) {
            $query->with(['department.structureType','department.parent','position']);
        }])->get();
        $employees =  [
            'department_boss' => $employeeBoss,
            'simple_employee'=> $simpleEmployees,
        ];
        return response()->json($employees);
    }
    public function showkafedralaruser($id)
    {
        // EmployMeta modelini id orqali olish
        $simpleEmployee = EmployMeta::with(['employ' => function ($query) {
        }])->find($id);
        // Agar ma'lumot topilmasa, 404 xato qaytarish
        if (!$simpleEmployee) {
            return response()->json(['message' => 'Employ meta topilmadi'], 404);
        }

        // Topilgan ma'lumotlarni JSON formatida qaytarish
        return response()->json([
            'id' => $simpleEmployee->id,
            'first_name' => $simpleEmployee->employ->first_name,
            'last_name' => $simpleEmployee->employ->last_name,
            'surname' => $simpleEmployee->employ->surname,
            'email' => $simpleEmployee->employ->email,
            'phone' => $simpleEmployee->employ->phone,
            'birthday' => $simpleEmployee->employ->birthday,
            'gender' => $simpleEmployee->employ->gender,
            'status' => $simpleEmployee->employ->status,
            'photo' => $simpleEmployee->employ->photo,
            'employ_meta' => $simpleEmployee // employMeta ma'lumotlarini qaytarish
        ]);
    }
}
