@extends('layouts.app')

@section('links')
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
            </div> <!-- / .row -->
        </div> <!-- / .header-body -->
        @include('app.components.breadcrumb', [
        'datas' => [
        [
        'active' => false,
        'url' => route($route_name.'.index'),
        'name' => $title,
        'disabled' => false
        ],
        [
        'active' => true,
        'url' => '',
        'name' => 'Editing',
        'disabled' => true
        ],
        ]
        ])
    </div>
</div> <!-- / .header -->

<!-- CARDS -->
<div class="container-fluid">
    <div class="row">
        <div class="col-8">
            <div class="card mw-50">
                <div class="card-body">
                    <form method="post" action="{{ route('education_faqs.update', $faq->id) }}" enctype="multipart/form-data" id="add">
                        @csrf
                        @method('put')
                        <input type="hidden" name="educational_program_id" value="{{$faq->educational_program_id }}">

                        <div class="row">
                            <div class="col-12">

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    @foreach($langs as $lang)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $lang->code }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $lang->code }}" type="button" role="tab" aria-controls="{{ $lang->code }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $lang->title }}</button>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    @foreach($langs as $lang)
                                        <div class="tab-pane mt-3 fade {{ $loop->first ? 'show active' : '' }}" id="{{ $lang->code }}" role="tabpanel" aria-labelledby="{{ $lang->code }}-tab">
                                            <div class="form-group">
                                                <label for="question{{ $lang->code }}" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Вопрос</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('question.'.$lang->code) is-invalid @enderror" name="question[{{ $lang->code }}]" value="{{ old('question.'.$lang->code) ?? $faq->question[$lang->code] ?? null }}" id="question{{ $lang->code }}" placeholder="Вопрос...">
                                                @error('question.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="answer{{ $lang->code }}" class="form-label">Ответ</label>
                                                <textarea name="answer[{{ $lang->code }}]" id="answer{{ $lang->code }}" cols="30" rows="10" class="form-control @error('answer.'.$lang->code) is-invalid @enderror ckeditor" placeholder="Ответ...">{{ old('answer.'.$lang->code) ?? $faq->answer[$lang->code] ?? null }}</textarea>
                                                @error('answer.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label for="employs" class="form-label">Skill</label>
                                    <select class="form-control mb-4 @error('skill_id') is-invalid @enderror" data-choices='{"1": true}' name="skill_id" required>
                                        @foreach ($skills as $key => $item)
                                            <option value="{{ $item->id }}"
                                                    {{ (old('skill_id', $faq->skill_id) == $item->id) ? 'selected' : '' }}>
                                                {{ $item->name[$main_lang->code] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employs')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <!-- Button -->
                        <div class="model-btns d-flex justify-content-end">
                            <a href="{{ route($route_name.'.index') }}" type="button" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-2">Save</button>
                        </div>
                </div>
            </div>
        </div>

    </div>
    </form>
</div>
@endsection
