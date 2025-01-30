@extends('layouts.app')

@section('links')

    <script>
        window.onload = function() {
            var add_post = new Dropzone("div#dropzone", {
                url: "{{ url('/admin/upload_from_dropzone') }}",
                paramName: "file",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                addRemoveLinks: true,
                maxFiles: 10,
                maxFilesize: 2, // MB
                success: (file, response) => {
                    let input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('value', response.file_name);
                    input.setAttribute('name', 'dropzone_images[]');

                    let form = document.getElementById('add');
                    form.append(input);
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
                error: function(file, message) {
                    alert(message);
                    this.removeFile(file);
                },

                // change default texts
                dictDefaultMessage: "Перетащите сюда файлы для загрузки",
                dictRemoveFile: "Удалить файл",
                dictCancelUpload: "Отменить загрузку",
                dictMaxFilesExceeded: "Не можете загружать больше",

                @if(old('dropzone_images') || isset($kampus->kampusImages[0]))
                init: function() {
                    var thisDropzone = this;

                    // document.querySelector('.dropzone').classList.add('dz-max-files-reached');

                    @foreach((old('dropzone_images') ?? $kampus->kampusImages()->pluck('img')->toArray()) as $img)

                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('value', '{{ $img }}');
                    input.setAttribute('name', 'dropzone_images[]');

                    var form = document.getElementById('add');
                    form.append(input);

                    var mockFile = {
                        name: '{{ $img }}',
                        size: 1024 * 512,
                        accepted: true
                    };

                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '/upload/images/{{ $img }}');
                    thisDropzone.files.push(mockFile)

                    @endforeach
                }
                @endif
            });
        };
    </script>
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
            'name' => 'Update',
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
                        <form method="post" action="{{ route($route_name . '.update', $kampus->id) }}" enctype="multipart/form-data" id="add">
                            @csrf
                            @method('PUT')
                            <div class="row">
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
                                                <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}"> Name</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('name.'.$lang->code) is-invalid @enderror" name="name[{{ $lang->code }}]" value="{{ old('name.'.$lang->code) ?? ($kampus->name[$lang->code] ?? '') }}" id="title" placeholder="Name...">
                                                @error('name.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                             <div class="form-group">
                                                    <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Firs Name</label>
                                                    <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('firs_name.'.$lang->code) is-invalid @enderror" name="first_name[{{ $lang->code }}]" value="{{ old('first_name.'.$lang->code) ?? ($kampus->first_name[$lang->code] ?? '') }}" id="title" placeholder="first name...">
                                                    @error('first_name.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="desc" class="form-label">Firs Description</label>
                                                    <textarea name="first_description[{{ $lang->code }}]" id="desc" cols="30" rows="10" class="form-control @error('desc.'.$lang->code) is-invalid @enderror ckeditor" placeholder="First description...">{{ old('first_description.'.$lang->code)?? $kampus->first_description[$lang->code] ?? '' }}</textarea>
                                                    @error('first_description.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Second Name</label>
                                                    <input type="text" class="form-control @error('second_name.'.$lang->code) is-invalid @enderror" name="second_name[{{ $lang->code }}]" value="{{ old('second_name.'.$lang->code) ?? ($kampus->second_name[$lang->code] ?? '') }}" id="title" placeholder="second_name name...">
                                                    @error('second_name.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="desc" class="form-label">Secon Description</label>
                                                    <textarea name="second_description[{{ $lang->code }}]" id="desc" cols="30" rows="10" class="form-control @error('second_description.'.$lang->code) is-invalid @enderror ckeditor" name="second_description[{{ $lang->code }}]" placeholder="second description...">{{ old('secon_description.'.$lang->code)?? $kampus->second_description[$lang->code] ?? '' }}</textarea>
                                                    @error('second_description.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}" Third>Name</label>
                                                    <input type="text" class="form-control @error('title.'.$lang->code) is-invalid @enderror" name="third_name[{{ $lang->code }}]" value="{{ old('third_name.'.$lang->code) ?? ($kampus->third_name[$lang->code] ?? '') }}" id="title" placeholder="Third Name...">
                                                    @error('third_name.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="desc" class="form-label">Third Description</label>
                                                    <textarea name="third_description[{{ $lang->code }}]" id="desc" cols="30" rows="10" class="form-control @error('third_description.'.$lang->code) is-invalid @enderror ckeditor" name="third_description[{{ $lang->code }}]" placeholder="Third_description...">{{ old('third_description.'.$lang->code)?? $kampus->third_description[$lang->code] ?? '' }}</textarea>
                                                    @error('third_description.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>

                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <!-- Dropzone -->
                                    <label for="dropzone" class="form-label">Photo</label>
                                    <div class="dropzone dropzone-multiple" id="dropzone"></div>
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
            <div class="col-4">
                <div class="card mw-50">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="date" class="form-label">Slug</label>
                                    <input type="text" id="date" name="slug" class="form-control" value="{{ old('slug')?? $kampus->slug ?? null }}" placeholder="slug"  />
                                </div>
                                <div class="form-group">
                                    <label for="date" class="form-label">Educational programs</label>
                                    <input type="text" id="date" name="educational_programs" class="form-control" value="{{ old('educational_programs')?? $kampus->educational_programs ?? null }}" placeholder="educational_programs"  />
                                </div>
                                <div class="form-group">
                                    <label for="date" class="form-label">Audience size</label>
                                    <input type="text" id="date" name="audience_size" class="form-control" value="{{ old('audience_size')?? $kampus->audience_size ?? null }}" placeholder="audience_size"  />
                                </div>
                                <div class="form-group">
                                    <label for="date" class="form-label">Green zone</label>
                                    <input type="text" id="date" name="green_zone" class="form-control" value="{{ old('green_zone') ?? $kampus->green_zone ?? null }}" placeholder="green_zone"  />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection