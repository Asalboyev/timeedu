@extends('layouts.app')

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
                    @php
                        $faqCount = \App\Models\EducationFaq::where('educational_program_id', $id)->count();
                    @endphp
                    @if ($faqCount < 5)
                        <a href="{{ route('education_faqs.create', $id) }}" class="btn btn-primary lift">
                            Add
                        </a>
                    @endif

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

    <div class="card mt-4">
        <div class="card-body">
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Educational Program</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faqs as $key => $item)
                        <tr>
                            <th scope="row" style="width: 100px">{{ $faqs->firstItem() + $key }}</th>
                            <td>
                                <div class="d-flex align-items-center">

                                    {{ $item->question[$main_lang->code] ?? '--'}}
                                </div>
                            </td>
                            <td>{{ $item->skill ? $item->skill->name[$main_lang->code] : 'null' }}</td>

                            <td style="width: 200px">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('education_faqs.edit',$item->id) }}" class="btn btn-sm btn-info"><i class="fe fe-edit-2"></i></a>
                                    <a class="btn btn-sm btn-danger ms-3" onclick="var result = confirm('Want to delete?');if (result){event.preventDefault();document.getElementById('delete-form{{ $item->id }}').submit();}"><i class="fe fe-trash"></i></a>
                                    <form action="{{ route('education_faqs.destroy', $item->id) }}" id="delete-form{{ $item->id }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $faqs->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
