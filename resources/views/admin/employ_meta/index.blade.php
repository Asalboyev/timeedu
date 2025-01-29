@extends('layouts.app')
@section('links')
    <style>
        .nested-row {
            display: none;
            background-color: #f9f9f9;
        }
        .toggle-btn {
            cursor: pointer;
            font-weight: bold;
            color: blue;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Employ</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Employ staff</th>
                            <th>Employ form</th>
                            <th>Employ staff type</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($employ_meta as $employ_meta_item)
                            <tr class="main-row">
                                <td>{{ $employ_meta_item->id }}</td>
                                <td>
                                    <span class="toggle-btn" data-target="nested-{{ $employ_meta_item->id }}">+</span>
                                    {{ $employ_meta_item->employ ? $employ_meta_item->employ->first_name[$main_lang->code] : 'Main' }}
                                </td>
                                <td >{{ $employ_meta_item->department ? $employ_meta_item->department->name[$main_lang->code] : 'Main' }}</td>
                                <td>{{ $employ_meta_item->position ? $employ_meta_item->position->name[$main_lang->code] : 'Main' }}</td>
                                <td>{{ $employ_meta_item->employ_staff ? $employ_meta_item->employ_staff->name[$main_lang->code] : 'Main' }}</td>
                                <td>{{ $employ_meta_item->employ_form ? $employ_meta_item->employ_form->name[$main_lang->code] : 'Main' }}</td>
                                <td>{{ $employ_meta_item->employ_type ? $employ_meta_item->employ_type->name[$main_lang->code] : 'Main' }}</td>
                                <td>
                                    @if($employ_meta_item->active == 1)
                                        <span style="color: green; font-weight: bold;">Active</span>
                                    @else
                                        <span style="color: red; font-weight: bold;">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('employ_meta.edit',$employ_meta_item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fe fe-edit-2"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger" onclick="if(confirm('Are you sure?')) { event.preventDefault(); document.getElementById('delete-form-{{ $employ_meta_item->id }}').submit(); }">
                                        <i class="fe fe-trash"></i>
                                    </a>
                                    <form id="delete-form-{{ $employ_meta_item->id }}" action="{{ route($route_name.'.destroy', $employ_meta_item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            <tr id="nested-{{ $employ_meta_item->id }}" class="nested-row">
                                <td colspan="9">
                                    <strong>Details:</strong>
                                    <ul>
                                        <li>Department: {{ $employ_meta_item->department->name[$main_lang->code] ?? 'N/A' }}</li>
                                        <li>Position: {{ $employ_meta_item->position->name[$main_lang->code] ?? 'N/A' }}</li>
                                        <li>Employ Staff: {{ $employ_meta_item->employ_staff->name[$main_lang->code] ?? 'N/A' }}</li>
                                        <li>Employ Form: {{ $employ_meta_item->employ_form->name[$main_lang->code] ?? 'N/A' }}</li>
                                        <li>Employ Staff Type: {{ $employ_meta_item->employ_type->name[$main_lang->code] ?? 'N/A' }}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $employ_meta->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".toggle-btn").forEach(button => {
                button.addEventListener("click", function () {
                    let targetId = this.getAttribute("data-target");
                    let targetRow = document.getElementById(targetId);
                    if (targetRow.style.display === "none" || targetRow.style.display === "") {
                        targetRow.style.display = "table-row";
                        this.innerText = "-";
                    } else {
                        targetRow.style.display = "none";
                        this.innerText = "+";
                    }
                });
            });
        });
    </script>
@endsection
