@extends('layouts.app')
@section('links')
    <style>
        #menu-list tr {
            cursor: move;
        }

        .submenu-row {
            background-color: #f9f9f9;
        }

        .menu-row {
            background-color: #fff;
        }

        .ui-sortable-helper {
            background-color: #d1ecf1 !important;
        }
    </style>


@endsection
@section('content')
    <!-- HEADER -->
    <div class="header">
        <div class="container-fluid">

            <!-- Body -->
            <div class="header-body">
                <div class="row align-items-end">
                    <div class="col">

                        <!-- Title -->
                        <h1 class="header-title">
                                {{ $title }}
                        </h1>

                    </div>
                    <div class="col-auto">

                        <!-- Button -->
                        <a href="{{ route($route_name.'.create') }}" class="btn btn-primary lift">
                            Add
                        </a>

                    </div>
                </div> <!-- / .row -->
            </div> <!-- / .header-body -->
            @include('app.components.breadcrumb', [
            'datas' => [
            [
            'active' => true,
            'url' => '',
            'name' => $title,
            'disabled' => false
            ]
            ]
            ])
        </div>
    </div> <!-- / .header -->

    <!-- CARDS -->
    <div class="container-fluid">
        <div class="search">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route($route_name.'.index') }}" class="d-flex">
                        <input type="text" class="form-control" name="search" value="{{$search}}" placeholder="Search...">
                        <button type="submit" class="btn btn-success ms-3" style="width: 300px;">Search</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Parent</th>
                            <th scope="col">Active</th>
                            <th scope="col">Active</th>
                            <th scope="col"></th>

                        </tr>
                        </thead>
                        <tbody >
                        @foreach ($educational_programs as $programItem)
                            {{-- Asosiy (parent) element --}}
                            <tr>
                                <td>{{ $programItem['program']->id }}</td>
                                <td><strong>{{ $programItem['program']->name[$languages->first()->code] ?? null }}</strong></td>
                                <td>Parent Educational_programs</td>
                                <td>
                                    @if($programItem['program']->active == 1)
                                        <span style="color: green; font-weight: bold;">Active</span>
                                    @else
                                        <span style="color: red; font-weight: bold;">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route($route_name.'.edit',  $programItem['program']->id) }}" class="btn btn-sm btn-info">
                                        <i class="fe fe-edit-2"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger"
                                       onclick="if(confirm('Are you sure?')) { event.preventDefault(); document.getElementById('delete-form-{{ $programItem['program']->id }}').submit(); }">
                                        <i class="fe fe-trash"></i>
                                    </a>
                                    <form id="delete-form-{{ $programItem['program']->id }}" action="{{ route($route_name.'.destroy', $programItem['program']->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>

                            {{-- Bolalar (children) elementlari --}}
                            @if (!empty($programItem['children']))
                                @foreach ($programItem['children'] as $child)
                                    <tr>
                                        <td>{{ $child['program']->id }}</td>
                                        <td>&mdash; {{ $child['program']->name[$languages->first()->code] ?? null }}</td>
                                        <td>{{ $programItem['program']->name[$languages->first()->code]?? null }}</td>
                                        <td>
                                            @if($programItem['program']->active == 1)
                                                <span style="color: green; font-weight: bold;">Active</span>
                                            @else
                                                <span style="color: red; font-weight: bold;">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route($route_name.'.edit',  $child['program']->id) }}" class="btn btn-sm btn-info">
                                                <i class="fe fe-edit-2"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger"
                                               onclick="if(confirm('Are you sure?')) { event.preventDefault(); document.getElementById('delete-form-{{ $child['program']->id }}').submit(); }">
                                                <i class="fe fe-trash"></i>
                                            </a>
                                            <form id="delete-form-{{ $child['program']->id }}" action="{{ route($route_name.'.destroy', $child['program']->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $count->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

@endsection
