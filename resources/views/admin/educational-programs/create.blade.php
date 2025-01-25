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
                error: function(file, message) {
                    alert(message);
                    this.removeFile(file);
                },

                // change default texts
                dictDefaultMessage: "Перетащите сюда файлы для загрузки",
                dictRemoveFile: "Удалить файл",
                dictCancelUpload: "Отменить загрузку",
                dictMaxFilesExceeded: "Не можете загружать больше"
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
            'name' => 'Add',
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
                        <form method="post" action="{{ route($route_name . '.store') }}" enctype="multipart/form-data" id="add">
                            @csrf
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
                                                <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">First Name</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('title.'.$lang->code) is-invalid @enderror" name="first_name[{{ $lang->code }}]" value="{{ old('first_name.'.$lang->code) }}" id="title" placeholder="First_name...">
                                                @error('first_name.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="desc" class="form-label">First Description</label>
                                                <textarea name="firs_description[{{ $lang->code }}]" id="desc" cols="30" rows="10" class="form-control @error('desc.'.$lang->code) is-invalid @enderror ckeditor" name="desc[{{ $lang->code }}]" placeholder="Description...">{{ old('desc.'.$lang->code) }}</textarea>
                                                @error('desc.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">First Name</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('title.'.$lang->code) is-invalid @enderror" name="first_name[{{ $lang->code }}]" value="{{ old('first_name.'.$lang->code) }}" id="title" placeholder="First_name...">
                                                @error('first_name.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="desc" class="form-label">First Description</label>
                                                <textarea name="firs_description[{{ $lang->code }}]" id="desc" cols="30" rows="10" class="form-control @error('desc.'.$lang->code) is-invalid @enderror ckeditor" name="desc[{{ $lang->code }}]" placeholder="Description...">{{ old('desc.'.$lang->code) }}</textarea>
                                                @error('desc.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Second Name</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('second_name.'.$lang->code) is-invalid @enderror" name="second_name[{{ $lang->code }}]" value="{{ old('second_name.'.$lang->code) }}" id="title" placeholder="Second Name...">
                                                @error('second_name.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="desc" class="form-label">Second Description</label>
                                                <textarea name="second_description[{{ $lang->code }}]" id="desc" cols="30" rows="10" class="form-control @error('second_description.'.$lang->code) is-invalid @enderror ckeditor" name="second_description[{{ $lang->code }}]" placeholder="Second description...">{{ old('second_description.'.$lang->code) }}</textarea>
                                                @error('second_description.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Third Name</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('third_name.'.$lang->code) is-invalid @enderror" name="third_name[{{ $lang->code }}]" value="{{ old('third_name.'.$lang->code) }}" id="title" placeholder="Third Name...">
                                                @error('third_name.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="desc" class="form-label">Third Description</label>
                                                <textarea name="second_description[{{ $lang->code }}]" id="desc" cols="30" rows="10" class="form-control @error('third_description.'.$lang->code) is-invalid @enderror ckeditor" name="third_description[{{ $lang->code }}]" placeholder="Third description...">{{ old('third_description.'.$lang->code) }}</textarea>
                                                @error('third_description.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Lang</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('lang.'.$lang->code) is-invalid @enderror" name="lang[{{ $lang->code }}]" value="{{ old('lang.'.$lang->code) }}" id="title" placeholder="Lang...">
                                                @error('lang.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Address</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('map.'.$lang->code) is-invalid @enderror" name="map[{{ $lang->code }}]" value="{{ old('lang.'.$lang->code) }}" id="title" placeholder="Address...">
                                                @error('map.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">Form of education</label>
                                                <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('map.'.$lang->code) is-invalid @enderror" name="form_education[{{ $lang->code }}]" value="{{ old('form_education.'.$lang->code) }}" id="title" placeholder="Kunduzgi || Sirtqi...">
                                                @error('form_education.'.$lang->code)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach

                                        <div class="form-group">
                                            <label for="parent_id" class="form-label">Parental</label>
                                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" data-choices='{"": true}' name="parent_id">
                                                @foreach ($educationalProgram as $key => $item)
                                                    <option value="{{ $item->id }}" {{ old('parent_id') == $item->id ? 'selected' : '' }}>{{ $item->first_name[$main_lang->code] ?? null}}</option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="employs" class="form-label">Employ</label>
                                            <select class="form-control mb-4 @error('employs') is-invalid @enderror" data-choices='{"removeItemButton": true}' multiple name="employs[]">
                                                @foreach ($employ as $key => $item)
                                                    <option value="{{ $item->id }}" {{ (old('employs') ? in_array($item->id, old('employs')) : '') ? 'selected' : '' }}>{{ $item->first_name[$main_lang->code] }}</option>
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
            <div class="col-4">
                <div class="card mw-50">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="text" id="date" name="date" class="form-control" value="{{ old('date') }}" placeholder="{{ date('d-m-Y') }}" data-flatpickr='{"dateFormat": "d-m-Y"}' />
                                </div>

                                    <div class="form-group">
                                        <label for="date" class="form-label">Daytime price</label>
                                        <input type="text" id="date" name="daytime" class="form-control" value="{{ old('daytime') }}" placeholder="Daytime price..."/>
                                    </div>
                                <div class="form-group">
                                        <label for="part_time" class="form-label">Part time price</label>
                                        <input type="text" id="part_time" name="part_time" class="form-control" value="{{ old('part_time') }}" placeholder="Part time price..."  />
                                    </div>

                                    <div class="form-group">
                                        <!-- Dropzone -->
                                        <label for="dropzone" class="form-label">Photo</label>
                                        <div class="dropzone dropzone-multiple" id="dropzone"></div>
                                    </div>
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