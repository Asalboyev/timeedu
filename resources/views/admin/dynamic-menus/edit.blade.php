
@extends('layouts.app')

@section('links')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


    <!-- Add this in your <head> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />

    <!-- Add this before the closing </body> tag -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    <script>
        window.onload = function() {
            var add_post = new Dropzone("div#dropzone", {
                url: "{{ url('/admin/upload_from_dropzone') }}",
                paramName: "file",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                addRemoveLinks: true,
                maxFiles: 1,
                maxFilesize: 2, // MB
                success: (file, response) => {
                    let input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('value', response.file_name);
                    input.setAttribute('name', 'dropzone_images');

                    let form = document.getElementById('add');
                    form.append(input);
                    console.log(response);
                },
                removedfile: function(file) {
                    file.previewElement.remove();
                    if (file.xhr) {
                        let data = JSON.parse(file.xhr.response);
                        let removing_img = document.querySelector('[value="' + data.file_name + '"]');
                        removing_img.remove();
                    } else {
                        let data = file.name.split('/')[file.name.split('/').length - 1]
                        let removing_img = document.querySelector('[value="' + data + '"]');
                        removing_img.remove();
                    }
                },
                init: function() {
                    @if(isset($dynamic_menu -> background))

                    var thisDropzone = this;

                    document.querySelector('.dropzone').classList.add('dz-max-files-reached');

                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('value', '{{ $dynamic_menu->background }}');
                    input.setAttribute('name', 'dropzone_images');

                    let form = document.getElementById('add');
                    form.append(input);

                    var mockFile = {

                        name: '{{ $dynamic_menu->background }}',
                        size: 1024 * 512,
                        accepted: true
                    };

                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '{{ $dynamic_menu->sm_img }}');
                    thisDropzone.files.push(mockFile)

                    @endif
                },
                error: function(file, message) {
                    alert(message);
                    this.removeFile(file);
                },

                // change default texts
                dictDefaultMessage: "Drag files here to upload",
                dictRemoveFile: "Delete file",
                dictCancelUpload: "Cancel download",
                dictMaxFilesExceeded: "Can't load more"
            });
        };
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .form-grouppp {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 15rem;
            height: 2rem;
            margin-bottom: 20px;
            justify-content: flex-end;
            /* Align elements to the right */
        }

        .form-select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btnn {
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
            margin-bottom: 40px;
            padding: 10px;
            position: relative;
        }

        .delete-btn {
            position: absolute;
            top: 30px;
            right: 25px;
            background: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: darkred;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            /* Match the height of your form controls */
        }

        .select2-container .select2-selection__rendered {
            line-height: 38px;
            /* Center the text vertically */
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
    </div> <!-- / .header -->

    <!-- CARDS -->
    <div class="container-fluid">
        <form method="post" action="{{ route($route_name . '.update',$dynamic_menu->id) }}" enctype="multipart/form-data" id="add">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-8">
                    {{--                    nima qanaqa bolishi kerak xozr --}}
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
                                                <div class="tab-pane mt-3 fade {{ $loop->first ? 'show active' : '' }}" id="{{ $lang->code }}" role="tabpanel"
                                                     aria-labelledby="{{ $lang->code }}-tab">
                                                    <div class="form-group">
                                                        <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Short
                                                            tile</label>
                                                        <input type="text" {{ $lang->code == $main_lang->code ? 'required' : ''
                                                }} class="form-control @error('short_title.'.$lang->code) is-invalid
                                                @enderror" name="short_title[{{ $lang->code }}]" value="{{
                                                old('short_title.'.$lang->code) ?? $dynamic_menu->title[$lang->code] ?? null }}" id="short_title" placeholder="Short title...">
                                                        @error('short_title.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Title</label>
                                                        <input type="text" {{ $lang->code == $main_lang->code ? 'required' : ''
                                        }} class="form-control @error('title.'.$lang->code) is-invalid
                                        @enderror" name="title[{{ $lang->code }}]" value="{{
                                        old('title.'.$lang->code) ?? $dynamic_menu->title[$lang->code] ?? "No  text" }}" id="title" placeholder="Title...">
                                                        @error('title.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="file" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">
                                                            File {{$lang->code}}
                                                        </label>
                                                        <input type="file"
                                                               class="form-control @error('file.'.$lang->code) is-invalid @enderror"
                                                               name="file[{{ $lang->code }}]"
                                                               id="file_{{ $lang->code }}"
                                                               placeholder="File...">
                                                        <div id="file-name-display-{{ $lang->code }}" style="margin-top: 5px; font-style: italic; color: gray;">
                                                            {{ $dynamic_menu->file[$lang->code] ?? 'No file selected' }}
                                                        </div>
                                                        @error('file.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>

                                                    <script>
                                                        document.getElementById('file_{{ $lang->code }}').addEventListener('change', function(e) {
                                                            const fileName = e.target.files[0]?.name || 'No file selected';
                                                            document.getElementById('file-name-display-{{ $lang->code }}').textContent = fileName;
                                                        });
                                                    </script>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="form-group">
                                            <!-- Dropzone -->
                                            <label for="dropzone" class="form-label">Photo</label>
                                            <div class="dropzone dropzone-multiple" id="dropzone"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="menu_id" class="form-label">Menu</label>
                                            <select class="form-select searchable @error('menu_id') is-invalid @enderror" id="menu_id" name="menu_id" required>
                                                @foreach ($menus as $key => $item)
                                                    <option value="{{ $item->id }}" {{ old('menu_id')==$dynamic_menu->id ? 'selected' :
                                                             '' }} >
                                                        {{ $item->title[$main_lang->code] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('menu_id')
                                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                            @enderror
                                        </div>

                                    </div>

                                </div>

                        </div>
                    </div>
                    <div id="formContainer">
                        {{--                        @dd($formmenu);--}}
                        @foreach($formmenu as $fmenu)
                            <div class="card mw-50" id="formmenu-card-{{ $loop->index }}">
                                <div class="card-body">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- Yopish tugmasi -->
                                            <button type="button" class="btn-close float-end" aria-label="Close"
                                                    onclick="removeFormMenu('formmenu-card-{{ $loop->index }}')"></button>

                                            <!-- Til uchun tablar -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                @foreach($langs as $lang)
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                                id="lang-{{ $lang->code }}-tab-{{ $loop->parent->index }}" data-bs-toggle="tab"
                                                                data-bs-target="#lang-{{ $lang->code }}-{{ $loop->parent->index }}" type="button" role="tab"
                                                                aria-controls="lang-{{ $lang->code }}-{{ $loop->parent->index }}"
                                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                            {{ $lang->title }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <!-- Har bir til uchun kontent -->
                                            <div class="tab-content">
                                                @foreach($langs as $lang)
                                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} mt-3"
                                                         id="lang-{{ $lang->code }}-{{ $loop->parent->index }}" role="tabpanel">
                                                        <!-- Sarlavha maydoni -->
                                                        <div class="form-group">
                                                            <label for="title-{{ $lang->code }}-{{ $loop->parent->index }}"
                                                                   class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">
                                                                Title
                                                            </label>
                                                            <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }}
                                                            class="form-control @error('title.'.$lang->code) is-invalid @enderror"
                                                                   name="formmenu[{{ $loop->parent->index }}][title][{{ $lang->code }}]"
                                                                   id="title-{{ $lang->code }}-{{ $loop->parent->index }}"
                                                                   placeholder="Title..."
                                                                   value="{{ old('title.'.$lang->code, $fmenu->title[$lang->code] ?? '') }}">
                                                            @error('title.'.$lang->code)
                                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>

                                                        <!-- Tavsif maydoni -->
                                                        <div class="form-group">
                                                            <label for="desc-{{ $lang->code }}-{{ $loop->parent->index }}" class="form-label">
                                                                Description
                                                            </label>
                                                            <textarea name="formmenu[{{ $loop->parent->index }}][text][{{ $lang->code }}]"
                                                                      id="desc-{{ $lang->code }}-{{ $loop->parent->index }}"
                                                                      class="form-control @error('text.'.$lang->code) is-invalid @enderror ckeditor"
                                                                      placeholder="Description...">{{ $fmenu->text[$lang->code] ?? '' }}</textarea>
                                                            @error('text.'.$lang->code)
                                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Tartib maydoni -->
                                            <div class="form-group">
                                                <label for="order-{{ $loop->index }}" class="form-label">Order</label>
                                                <input type="number" value="{{ $fmenu->order ?? 0 }}"
                                                       class="form-control @error('order') is-invalid @enderror"
                                                       name="formmenu[{{ $loop->index }}][order]" id="order-{{ $loop->index }}"
                                                       placeholder="Order...">
                                                @error('order')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                                @enderror
{{--                                                <input tyname="formmenu[{{ $loop->index }}][dinamik_menu_id]" value="{{$dynamic_menu->id}}">--}}
                                            </div>

                                            <!-- Rasm yuklash -->
                                            <div class="form-group">
                                                <label for="dropzone-{{ $loop->index }}" class="form-label">Photo</label>
                                                <div class="dropzone dropzone-multiple" id="dropzone-{{ $loop->index }}"></div>
                                                <input type="hidden" name="formmenu[{{ $loop->index }}][dropzone_images]"
                                                       id="hiddenInput_dropzone_{{ $loop->index }}"
                                                       value="{{ implode(',', $fmenu->formImages->pluck('img')->toArray()) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{--                        formmneu2--}}
                        @foreach($formmenu1 as $fmenu1)
                            <div class="card mw-50" id="formmenu-card-{{ $loop->index }}">
                                <div class="card-body">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" class="btn-close float-end" aria-label="Close"
                                                    onclick="removeFormMenu('formmenu-card-{{ $loop->index }}')"></button>

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                @foreach($langs as $lang)
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                                id="tab-{{ $lang->code }}-tab" data-bs-toggle="tab"
                                                                data-bs-target="#tab-{{ $lang->code }}" type="button" role="tab"
                                                                aria-controls="tab-{{ $lang->code }}"
                                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                            {{ $lang->title }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content" id="myTabContent">
                                                @foreach($langs as $lang)
                                                    <div class="tab-pane mt-3 fade {{ $loop->first ? 'show active' : '' }}"
                                                         id="tab-{{ $lang->code }}" role="tabpanel"
                                                         aria-labelledby="tab-{{ $lang->code }}-tab">
                                                        <div class="form-group">
                                                            <label for="title"
                                                                   class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Title</label>
                                                            <input type="text" {{ $lang->code == $main_lang->code ? 'required' : ''
                                                }}
                                                            class="form-control
                                                @error('formmenu2.'.$loop->index.'.title.'.$lang->code) is-invalid
                                                @enderror"
                                                                   name="formmenu2[{{ $loop->iteration }}][title][{{ $lang->code }}]"
                                                                   value="{{ old('formmenu2.'.$loop->parent->index.'.title.'.$lang->code)
                                                ?? $fmenu->title[$lang->code] ?? '' }}"
                                                                   id="title" placeholder="Title...">
                                                            @error('formmenu2.'.$loop->index.'.title.'.$lang->code)
                                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="form-group">
                                                <label for="categories" class="form-label">Categories</label>
                                                <select id="categories"
                                                        class="form-control mb-4 @error('formmenu2.'.$loop->index.'.categories') is-invalid @enderror"
                                                        data-choices='{"removeItemButton": true}' multiple
                                                        name="formmenu2[{{ $loop->iteration }}][categories][]">
                                                    @foreach ($all_categories as $item)
                                                        <option value="{{ $item->id }}" @if(old('formmenu2.'.$loop->
                                                index.'.categories') && in_array($item->id,
                                                old('formmenu2.'.$loop->index.'.categories')))
                                                            selected
                                                                @elseif(isset($fmenu1) && $fmenu->postsmenuCategories &&
                                                                in_array($item->id,
                                                                $fmenu1->postsmenuCategories->pluck('id')->toArray()))
                                                                    selected
                                                                @endif>
                                                            {{ $item->title[$main_lang->code] }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('formmenu2.'.$loop->index.'.categories')
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="order" class="form-label">Order</label>
                                                <input type="number" value="{{ $fmenu1->order }}"
                                                       class="form-control @error('formmenu2.'.$loop->index.'.order') is-invalid @enderror"
                                                       name="formmenu2[{{ $loop->iteration }}][order]" id="order"
                                                       placeholder="Order...">
                                                @error('formmenu2.'.$loop->index.'.order')
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        @endforeach
                        {{--     formmnenu3                   --}}
                        @foreach($formmenu3 as $fmenu3)
                            <div class="card mw-50" id="formmenu-card-{{ $loop->index }}">
                                <div class="card-body">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" class="btn-close float-end" aria-label="Close" onclick="removeFormMenu('formmenu-card-{{ $loop->index }}')"></button>


                                            <!-- Language Tabs -->
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                @foreach($langs as $lang)
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                                id="locale-{{ $lang->code }}-tab" data-bs-toggle="tab"
                                                                data-bs-target="#locale-{{ $lang->code }}" type="button" role="tab"
                                                                aria-controls="locale-{{ $lang->code }}"
                                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                            {{ $lang->title }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <!-- Tab Content -->
                                            <div class="tab-content" id="myTabContent">
                                                @foreach($langs as $lang)
                                                    <div class="tab-pane mt-3 fade {{ $loop->first ? 'show active' : '' }}"
                                                         id="locale-{{ $lang->code }}" role="tabpanel"
                                                         aria-labelledby="locale-{{ $lang->code }}-tab">
                                                        <div class="form-group">
                                                            <label for="locale-title-{{ $lang->code }}"
                                                                   class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Title</label>
                                                            <input type="text" {{ $lang->code == $main_lang->code ? 'required' : ''
                                                }}
                                                            class="form-control @error('title.'.$lang->code) is-invalid @enderror"
                                                                   name="formmenu3[{{ $loop->iteration }}][title][{{ $lang->code }}]"
                                                                   value="{{ old('title.'.$lang->code) ?? $fmenu3->title[$lang->code] ?? ''
                                                }}"
                                                                   id="locale-title-{{ $lang->code }}"
                                                                   placeholder="Title...">
                                                            @error('title.'.$lang->code)
                                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="locale-desc-{{ $lang->code }}"
                                                                   class="form-label">Description</label>
                                                            <textarea
                                                                    name="formmenu3[{{ $loop->iteration }}][text][{{ $lang->code }}]"
                                                                    id="locale-desc-{{ $lang->code }}"
                                                                    class="form-control @error('text.'.$lang->code) is-invalid @enderror ckeditor"
                                                                    placeholder="Description...">{{ $fmenu3->text[$lang->code] ?? '' }}</textarea>
                                                            @error('desc.'.$lang->code)
                                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Position Field -->
                                            <div class="form-group">
                                                <label for="position" class="form-label">Position</label>
                                                <select name="formmenu3[{{ $loop->iteration }}][position]" class="form-select">
                                                    <option value="1" {{ old('menu_id')==1 || $fmenu3->position == 1 ? 'selected'
                                                : '' }}>Right</option>
                                                    <option value="0" {{ old('menu_id')==0 || $fmenu3->position == 0 ? 'selected'
                                                : '' }}>Left</option>
                                                </select>
                                            </div>

                                            <!-- Order Field -->
                                            <div class="form-group">
                                                <label for="order" class="form-label">Order</label>
                                                <input type="number" class="form-control @error('path') is-invalid @enderror"
                                                       name="formmenu3[{{ $loop->iteration }}][order]"
                                                       value="{{ $fmenu3->order ?? '' }}" id="order" placeholder="Order...">
                                                @error('in_main')
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>

                                            <!-- Dropzone for Photo Upload -->

                                            <div class="form-group">
                                                <label for="dropzoneform3-{{ $loop->index }}" class="form-label">Photo</label>
                                                <div class="dropzone dropzone-multiple" id="dropzoneform3-{{ $loop->index }}"></div>

                                                <!-- Hidden Input for Uploaded Image Names -->
                                                <input type="hidden" name="formmenu3[{{ $loop->iteration }}][dropzone_images]"
                                                       id="hiddenInput_dropzoneform3_{{ $loop->index }}"
                                                       value="{{ implode(',', $fmenu3->formImages->pluck('img')->toArray()) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-grouppp">
                        <label for="menu_id" class="form-label">ADD Form</label>
                        <select id="formSelector" class="form-select">
                            <option value="1">Form 1</option>
                            <option value="2">Form 2</option>
                            <option value="3">Form 3</option>

                        </select>
                        <a id="addFormBtn" class="btnn">Add</a>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card ">
                        <div class="card-body">
                            @csrf
                            <div class="model-btns d-flex justify-content-end">
                                <a href="{{ route('dynamic-menus.index') }}" type="button"
                                   class="btn btn-secondary">Cancel</a>
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

    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script src="path/to/your/script.js"></script>

    <script>
        function removeFormMenu(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.remove(); // Elementni DOM'dan olib tashlaydi
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            @foreach($formmenu as $fmenu)
            (() => {
                // Dropzone konfiguratsiyasi uchun ID va mavjud rasm ma'lumotlari
                const dropzoneId = `dropzone-{{ $loop->index }}`;
                const hiddenInputId = `hiddenInput_dropzone_{{ $loop->index }}`;
                const existingImages = @json($fmenu->formImages->pluck('img')->toArray()); // Mavjud rasmlar

                // Dropzone konfiguratsiyasi chaqiriladi
                configureDropzone(dropzoneId, hiddenInputId, existingImages);
            })();
            @endforeach

            // Dropzone konfiguratsiyasi funksiyasi
            function configureDropzone(dropzoneId, hiddenInputId, existingImages) {
                // Dropzone elementini tekshirish
                const dropzoneElement = document.getElementById(dropzoneId);
                if (!dropzoneElement) {
                    console.error(`Dropzone elementi topilmadi: ${dropzoneId}`);
                    return;
                }

                // Dropzone konfiguratsiyasi
                const dropzone = new Dropzone(`#${dropzoneId}`, {
                    url: "{{ url('/admin/upload_from_dropzone') }}",
                    paramName: "file",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    addRemoveLinks: true,
                    maxFiles: 10,
                    maxFilesize: 2, // MB
                    acceptedFiles: "image/*", // Faqat rasm fayllarini qabul qilish
                    dictDefaultMessage: "Drag files here to upload",
                    dictRemoveFile: "Delete file",
                    dictMaxFilesExceeded: "You can't upload more files.",

                    init: function () {
                        const hiddenInput = document.getElementById(hiddenInputId);

                        // Mavjud rasmlarni yuklash
                        if (existingImages && existingImages.length > 0) {
                            existingImages.forEach((image) => {
                                const imagePath = `/upload/images/${image}`; // Rasmning to'liq yo'li
                                const mockFile = { name: image, size: 12345, accepted: true };

                                this.emit("addedfile", mockFile); // Faylni qo'shish
                                this.emit("thumbnail", mockFile, imagePath); // Thumbnail qo'shish
                                mockFile.previewElement.classList.add("dz-complete"); // To'liq yuklangan sifatida belgilash

                                // Faylni o'chirishni boshqarish
                                mockFile.previewElement.querySelector(".dz-remove").addEventListener("click", function () {
                                    let fileNames = hiddenInput.value.split(',');
                                    fileNames = fileNames.filter(name => name !== image);
                                    hiddenInput.value = fileNames.join(',');
                                });
                            });
                        }
                    },

                    success: function (file, response) {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        if (response.file_name) {
                            if (hiddenInput.value) {
                                hiddenInput.value += `,${response.file_name}`;
                            } else {
                                hiddenInput.value = response.file_name;
                            }
                        } else {
                            console.error("Uploaddan keyin fayl nomi qaytarilmadi.");
                        }
                    },

                    removedfile: function (file) {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        let fileNames = hiddenInput.value.split(',');
                        const fileName = file.name;

                        // Fayl nomini yashirin inputdan o'chirish
                        fileNames = fileNames.filter(name => name !== fileName);
                        hiddenInput.value = fileNames.join(',');

                        // Dropzone elementini o'chirish
                        if (file.previewElement != null) {
                            file.previewElement.remove();
                        }
                    },

                    error: function (file, message) {
                        console.error(`Faylni yuklashda xato: ${message}`);
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @foreach($formmenu3 as $fmenu)
            (() => {
                const dropzoneId = `dropzone-{{ $loop->index }}`;
                const hiddenInputId = `hiddenInput_dropzone_{{ $loop->index }}`;
                const existingImages = @json($fmenu->formImages->pluck('img')->toArray()); // Mavjud rasmlarni olish

                // Dropzone sozlamalari
                const dropzone = new Dropzone(`#${dropzoneId}`, {
                    url: "{{ url('/admin/upload_from_dropzone') }}",
                    paramName: "file",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    addRemoveLinks: true,
                    maxFiles: 10,
                    maxFilesize: 2, // MB
                    dictDefaultMessage: "Drag files here to upload",
                    dictRemoveFile: "Delete file",
                    dictMaxFilesExceeded: "Can't load more files",

                    init: function () {
                        const hiddenInput = document.getElementById(hiddenInputId);

                        // Mavjud rasmlarni yuklash
                        existingImages.forEach((image) => {
                            const imagePath = `/upload/images/${image}`; // Rasmlarning to'liq yo'lini kiriting
                            const mockFile = { name: image, size: 12345, accepted: true };

                            this.emit("addedfile", mockFile); // Faylni qo'shadi
                            this.emit("thumbnail", mockFile, imagePath); // Thumbnail o'rnatadi
                            mockFile.previewElement.classList.add("dz-complete"); // Fayl yuklangan deb belgilaydi

                            // O'chirish tugmasini boshqarish
                            mockFile.previewElement.querySelector(".dz-remove").addEventListener("click", function () {
                                let fileNames = hiddenInput.value.split(',');
                                fileNames = fileNames.filter(name => name !== image);
                                hiddenInput.value = fileNames.join(',');
                            });
                        });
                    },

                    success: function (file, response) {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        if (hiddenInput.value) {
                            hiddenInput.value += `,${response.file_name}`;
                        } else {
                            hiddenInput.value = response.file_name;
                        }
                    },

                    removedfile: function (file) {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        let fileNames = hiddenInput.value.split(',');
                        const fileName = file.name;

                        // Fayl nomini yashirin inputdan olib tashlash
                        fileNames = fileNames.filter(name => name !== fileName);
                        hiddenInput.value = fileNames.join(',');

                        // Dropzone elementini o'chirish
                        if (file.previewElement != null) {
                            file.previewElement.remove();
                        }
                    }
                });
            })();
            @endforeach
        });
    </script>
    <script>
        document.getElementById('addFormBtn').addEventListener('click', function () {
            const formContainer = document.getElementById('formContainer');
            const selectedForm = document.getElementById('formSelector').value;

            // Initialize the count for each form type
            const form1Count = {{ $formmenu->count() }}; // PHPdan $formmenu'ni soni olinadi.
            {{--const form2Count = {{ $formmenu1->count() }}; // PHPdan $formmenu'ni soni olinadi.--}}
            {{--const form3Count = {{ $formmenu3->count() }}; // PHPdan $formmenu'ni soni olinadi.--}}

            const form2Count = document.querySelectorAll('.form-section[data-form-type="2"]').length;
            const form3Count = document.querySelectorAll('.form-section[data-form-type="3"]').length;

            // Check if the number of forms for the selected type is below the limit (10)
            if (selectedForm === '1' && form1Count >= 10) {
                alert("Har bir formadan faqat 10 tadan yaratishingiz mumkin.");
                return;
            } else if (selectedForm === '2' && form2Count >= 10) {
                alert("Har bir formadan faqat 10 tadan yaratishingiz mumkin.");
                return;
            } else if (selectedForm === '3' && form3Count >= 10) {
                alert("Har bir formadan faqat 10 tadan yaratishingiz mumkin.");
                return;
            }

            // Create a new form section
            const formSection = document.createElement('div');
            formSection.className = 'form-section';
            formSection.setAttribute('data-form-type', selectedForm); // Set the form type

            const uniqueDropzoneId = `dropzone-${Date.now()}`; // Unique ID for Dropzone

            let formHTML = '';

            if (selectedForm === '1') {
                // Form 1 HTML (same as before)
                formHTML = `

                <div class="card mw-50">
                    <div class="card-body">
                        @csrf
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
@foreach($langs as $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                            id="lang-{{ $lang->code }}-tab" data-bs-toggle="tab"
                                            data-bs-target="#lang-{{ $lang->code }}" type="button" role="tab"
                                            aria-controls="lang-{{ $lang->code }}"
                                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $lang->title }}</button>
                                    </li>
                                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
@foreach($langs as $lang)
                <div class="tab-pane mt-3 fade {{ $loop->first ? 'show active' : '' }}"
                                         id="lang-{{ $lang->code }}" role="tabpanel"
                                         aria-labelledby="lang-{{ $lang->code }}-tab">
                                        <div class="form-group">
                                            <label for="title-{{ $lang->code }}"
                                                   class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Title</label>
                                            <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }}
                class="form-control @error('title.'.$lang->code) is-invalid @enderror"
                                                   name="formmenu[${form1Count}][title][{{ $lang->code }}]" value="{{ old('title.'.$lang->code) }}"
                                                   id="title-{{ $lang->code }}" placeholder="Title...">
                                            @error('title.'.$lang->code)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                </div>
                <div class="form-group">
                    <label for="desc-{{ $lang->code }}" class="form-label">Description</label>
                                            <textarea name="formmenu[${form1Count}][text][{{ $lang->code }}]" id="desc-{{ $lang->code }}"
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
                                        <label for="in_main" class="form-label">Order</label>
                                        <input type="text"  class="form-control @error('path') is-invalid @enderror" required name="formmenu[${form1Count}][order]" value="{{ old('path') }}" id="order" placeholder="Order...">

                                        @error('in_main')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                </div>
                                    <input type="hidden" name="formmenu[${form1Count}][type]" value="formmenu">

<div class="form-group">
<!-- Dropzone -->
<label for="${uniqueDropzoneId}" class="form-label">Photo</label>
    <div class="dropzone dropzone-multiple" id="${uniqueDropzoneId}"></div>

    <!-- Hidden input to store the uploaded image URL or names -->
    <input type="hidden" name="formmenu[${form1Count}][dropzone_images]" id="hiddenInput_${uniqueDropzoneId}">
</div>
                            </div>
                        </div>
                    </div>
                </div>`;  // Your Form 1 HTML here
            } else if (selectedForm === '2') {
                // Form 2 HTML (same as before)
                formHTML = ` <div class="card mw-50">
        <div class="card-body">
            @csrf
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
@foreach($langs as $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="tab-{{ $lang->code }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#tab-{{ $lang->code }}" type="button" role="tab"
                                    aria-controls="tab-{{ $lang->code }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $lang->title }}
                </button>
            </li>
@endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
@foreach($langs as $lang)
                <div class="tab-pane mt-3 fade {{ $loop->first ? 'show active' : '' }}"
                                id="tab-{{ $lang->code }}" role="tabpanel"
                                aria-labelledby="tab-{{ $lang->code }}-tab">
                                <div class="form-group">
                                    <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Title</label>
                                    <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }}
                class="form-control @error('title.'.$lang->code) is-invalid @enderror"
                                        name="formmenu2[${form2Count}][title][{{ $lang->code }}]" value="{{ old('title.'.$lang->code) }}"
                                        id="title" placeholder="Title...">
                                    @error('title.'.$lang->code)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                </div>
            </div>
@endforeach
                </div>
                                    <input type="hidden" name="formmenu2[${form2Count}][type]" value="formmenu1">

                <div class="form-group">
                    <label for="categories" class="form-label">Categories</label>
                    <select required  id="categories" class="form-control mb-4 @error('categories') is-invalid @enderror"
                            data-choices='{"removeItemButton": true}' multiple name="formmenu2[${form2Count}][categories][]">
                            @foreach ($all_categories as $item)
                <option value="{{ $item->id }}" {{ (old('categories') ? in_array($item->id, old('categories')) : '') ? 'selected' : '' }}>
                                    {{ $item->title[$main_lang->code] }}
                </option>
@endforeach
                </select>
@error('categories')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                </div>
                  <div class="form-group">
                                        <label for="in_main" class="form-label">Order</label>
                                        <input type="text" required  class="form-control @error('path') is-invalid @enderror" name="formmenu2[${form2Count}][order]" value="{{ old('path') }}" id="order" placeholder="Order...">

                                        @error('in_main')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
                                    </span>

                                        @enderror
                </div>
</div>
</div>
</div>
</div>`;  // Your Form 2 HTML here
            } else if (selectedForm === '3') {
                // Form 3 HTML (same as before)
                formHTML = `<div class="card mw-50">
                    <div class="card-body">
                        @csrf
                <div class="row">
                    <div class="col-12">
                      <ul class="nav nav-tabs" id="myTab" role="tablist">
@foreach($langs as $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                        id="locale-{{ $lang->code }}-tab" data-bs-toggle="tab"
                        data-bs-target="#locale-{{ $lang->code }}" type="button" role="tab"
                        aria-controls="locale-{{ $lang->code }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $lang->title }}</button>
            </li>
        @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
@foreach($langs as $lang)
                <div class="tab-pane mt-3 fade {{ $loop->first ? 'show active' : '' }}"
                 id="locale-{{ $lang->code }}" role="tabpanel"
                 aria-labelledby="locale-{{ $lang->code }}-tab">
                <div class="form-group">
                    <label for="locale-title-{{ $lang->code }}"
                           class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Title</label>
                    <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }}
                class="form-control @error('title.'.$lang->code) is-invalid @enderror"
                           name="formmenu3[${form3Count}][title][{{ $lang->code }}]" value="{{ old('title.'.$lang->code) }}"
                           id="locale-title-{{ $lang->code }}" placeholder="Title...">
                    @error('title.'.$lang->code)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="locale-desc-{{ $lang->code }}" class="form-label">Description</label>
                    <textarea name="formmenu3[${form3Count}][text][{{ $lang->code }}]" id="locale-desc-{{ $lang->code }}"
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
                        <label for="menu_id" class="form-label">Position</label>
                        <select name="formmenu3[${form3Count}][position]" class="form-select">
                            <option value="1">right</option>
                            <option value="0">left</option>
                        </select>
                    </div>
  <div class="form-group">
                                        <label for="in_main" class="form-label">Order</label>
                                        <input type="hidden" name="formmenu3[${form3Count}][type]" value="formmenu3">
                                        <input type="text"  class="form-control @error('path') is-invalid @enderror" name="formmenu3[${form3Count}][order]" value="{{ old('path') }}" id="order" placeholder="Order...">
                                        @error('in_main')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                </div>
<div class="form-group">
<!-- Dropzone -->
<label for="${uniqueDropzoneId}" class="form-label">Photo</label>
    <div class="dropzone dropzone-multiple" id="${uniqueDropzoneId}"></div>

    <!-- Hidden input to store the uploaded image URL or names -->
    <input type="hidden" name="formmenu3[${form3Count}][dropzone_images]" id="hiddenInput_${uniqueDropzoneId}">
</div

            </div>
        </div>
    </div>
</div>`;  // Your Form 3 HTML here
            }

            // Add the form to the section
            formSection.innerHTML += formHTML;

            // Add a delete button
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-btn btn btn-danger mt-3';
            deleteBtn.textContent = 'Delete';
            deleteBtn.type = 'button';
            deleteBtn.addEventListener('click', function () {
                formContainer.removeChild(formSection);
            });
            formSection.appendChild(deleteBtn);

            // Append the section to the container
            formContainer.appendChild(formSection);

            // Initialize CKEditor, Choices.js, and Dropzone after the DOM is updated
            setTimeout(function () {
                // Initialize CKEditor
                const textareas = formSection.querySelectorAll('.ckeditor');
                textareas.forEach(function (textarea) {
                    ClassicEditor.create(textarea).catch(function (error) {
                        console.error(error);
                    });
                });

                // Initialize Choices.js
                const selects = formSection.querySelectorAll('select[data-choices]');
                selects.forEach(function (select) {
                    new Choices(select, { removeItemButton: true });
                });

                // Initialize Dropzone
                const dropzoneElement = document.getElementById(uniqueDropzoneId);
                new Dropzone(`div#${uniqueDropzoneId}`, {
                    url: "{{ url('/admin/upload_from_dropzone') }}",
                    paramName: "file",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    addRemoveLinks: true,
                    maxFiles: 10,
                    maxFilesize: 2, // MB
                    success: (file, response) => {
                        // Get the hidden input field for this dropzone
                        let hiddenInput = document.getElementById(`hiddenInput_${uniqueDropzoneId}`);

                        // Parse the current value or initialize an empty array
                        let fileNames = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];

                        // Add the new file name to the array
                        fileNames.push(response.file_name);

                        // Update the hidden input with the updated array
                        hiddenInput.value = JSON.stringify(fileNames);
                    },
                    removedfile: function (file) {
                        // Remove the file name from the hidden input when the file is removed
                        let hiddenInput = document.getElementById(`hiddenInput_${uniqueDropzoneId}`);
                        let fileName = file.name.split('/').pop(); // Extract file name

                        // Parse the current value or initialize an empty array
                        let fileNames = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];

                        // Remove the file name from the array
                        let index = fileNames.indexOf(fileName);
                        if (index !== -1) {
                            fileNames.splice(index, 1); // Remove the file name
                        }

                        // Update the hidden input with the updated array
                        hiddenInput.value = JSON.stringify(fileNames);

                        // Remove the file preview
                        file.previewElement.remove();
                    },
                    error: function (file, message) {
                        alert(message);
                        this.removeFile(file);
                    },
                    dictDefaultMessage: "Drag files here to upload",
                    dictRemoveFile: "Delete file",
                    dictCancelUpload: "Cancel download",
                    dictMaxFilesExceeded: "Can't load more"
                });




                // Now you can access the imageUrls array for further usage, such as submitting them with the form.

            }, 0);
        });

        $(document).ready(function () {
            $('#menu_id').select2({
                placeholder: "Select a menu",
                allowClear: true
            });
        });
    </script>
@endsection

