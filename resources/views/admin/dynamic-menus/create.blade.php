@extends('layouts.app')

@section('links')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .form-select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .form-section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            position: relative;
        }
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: darkred;
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
            'name' => 'Addition',
            'disabled' => true
            ],
            ]
            ])
        </div>
    </div>
    <!-- / .header -->

    <!-- CARDS -->
    <div class="container-fluid">
        <form method="post" action="{{ route($route_name . '.store') }}" enctype="multipart/form-data" id="add">
            <div class="row">
                <div class="col-8">
                    <div class="card mw-50">
                        <div class="card-body">
                            <form method="post" action="{{ route($route_name . '.store') }}" enctype="multipart/form-data" id="add">
                                @csrf
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
                                                        <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Title</label>
                                                        <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('title.'.$lang->code) is-invalid @enderror" name="title[{{ $lang->code }}]" value="{{ old('title.'.$lang->code) }}" id="title" placeholder="Title...">
                                                        @error('title.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="desc" class="form-label">Description</label>
                                                        <textarea name="text[{{ $lang->code }}]"
                                                                  id="text"
                                                                  data-lang="{{ $lang->code }}"
                                                                  cols="30"
                                                                  rows="10"
                                                                  class="form-control @error('text.'.$lang->code) is-invalid @enderror ckeditor"
                                                                  placeholder="Description...">{{ old('text.'.$lang->code) }}</textarea>
                                                        @error('desc.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="form-group">
                                            <label for="menu_id" class="form-label">Menu</label>
                                            <select class="form-select @error('menu_id') is-invalid @enderror" id="menu_id" name="menu_id" required >

                                                @foreach ($menus as $key => $item)
                                                    <option value="{{ $item->id }}" {{ old('menu_id') == $item->id ? 'selected' : '' }}>{{ $item->title[$main_lang->code] }}</option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                        </div>

                    </div>
                    <div id="formContainer"></div>
                    <div class="form-groupp">
                        <label for="menu_id" class="form-label">ADD Form</label>
                        <select id="formSelector" class="form-select">
                            <option value="1">Form 1</option>
                            <option value="2">Form 2</option>
                            <option value="3">Form 3</option>
                            <option value="4">Form 4</option>
                        </select>
                        <button id="addFormBtn" class="btn">Add</button>
                    </div>


                </div>
                <div class="col-2">
                    <div class="card ">
                        <div class="card-body">
                            @csrf
                            <div class="model-btns d-flex justify-content-end">
                                <a href="{{ route('dynamic-menus.index') }}" type="button" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary ms-2">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('addFormBtn').addEventListener('click', function() {
            const formContainer = document.getElementById('formContainer');
            const selectedForm = document.getElementById('formSelector').value;

            // Create a new form section
            const formSection = document.createElement('div');
            formSection.className = 'form-section';

            // Add a delete button
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-btn';
            deleteBtn.textContent = 'Delete';
            deleteBtn.type = 'button'; // Prevent form submission
            deleteBtn.addEventListener('click', function() {
                formContainer.removeChild(formSection);
            });
            formSection.appendChild(deleteBtn);

            // Create form based on selection
            let formHTML = '';
            if (selectedForm === '1') {
                formHTML = `
                    <form>
                        <input type="text" name="input1" placeholder="Input 1" class="form-input" /><br/>
                        <input type="text" name="input2" placeholder="Input 2" class="form-input" /><br/>
                    </form>
                `;
            } else if (selectedForm === '2') {
                formHTML = `
                    <form>
                        <input type="text" name="input1" placeholder="Input 1" class="form-input" /><br/>
                        <textarea name="editor${Date.now()}" id="editor${Date.now()}"></textarea>
                    </form>
                `;
            } else if (selectedForm === '3') {
                formHTML = `
                    <form>
                        <input type="text" name="input1" placeholder="Input 1" class="form-input" /><br/>
                        <input type="file" name="imageUpload" class="form-input" /><br/>
                    </form>
                `;
            } else if (selectedForm === '4') {
                formHTML = `
                    <form>
                        <input type="text" name="input1" placeholder="Input 1" class="form-input" /><br/>
                        <input type="text" name="input2" placeholder="Input 2" class="form-input" /><br/>
                        <input type="text" name="input3" placeholder="Input 3" class="form-input" /><br/>
                    </form>
                `;
            }

            // Append the form to the section
            formSection.innerHTML += formHTML;

            // Append the section to the container
            formContainer.appendChild(formSection);

            // Initialize CKEditor if form 2 is selected
            if (selectedForm === '2') {
                const editorId = formSection.querySelector('textarea').id;
                CKEDITOR.replace(editorId);
            }
        });
    </script>
@endsection

