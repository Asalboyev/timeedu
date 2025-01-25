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
                init: function() {
                    @if(isset($employ->photo))

                    var thisDropzone = this;

                    document.querySelector('.dropzone').classList.add('dz-max-files-reached');

                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('value', '{{ $employ->photo }}');
                    input.setAttribute('name', 'dropzone_images');

                    let form = document.getElementById('add');
                    form.append(input);

                    var mockFile = {
                        name: '{{ $employ->photo }}',
                        size: 1024 * 512,
                        accepted: true
                    };

                    // thisDropzone.emit("addedfile", mockFile);
                    // thisDropzone.emit("thumbnail", mockFile, '{{ $employ->sm_img }}');
                    // thisDropzone.emit("complete", mockFile);

                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '{{ $employ->sm_img }}');
                    thisDropzone.files.push(mockFile)

                    @endif
                },
                error: function(file, message) {
                    alert(message);
                    this.removeFile(file);
                },

                // change default texts
                dictDefaultMessage: "Peretashchite syuda file for download",
                dictRemoveFile: "Delete file",
                dictCancelUpload: "Otmenit download",
                dictMaxFilesExceeded: "Ne mojete zagrujat bolshe"
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
    <form method="post" action="{{ route($route_name . '.update', $employ->id) }}" enctype="multipart/form-data" id="add">
        @csrf
        @method('put')
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
                                                    <label for="title" class="form-label {{ $lang->code == $main_lang->code ? 'required' : '' }}">First name</label>
                                                    <input type="text" {{ $lang->code == $main_lang->code ? 'required' : '' }} class="form-control @error('first_name.'.$lang->code) is-invalid @enderror" name="first_name[{{ $lang->code }}]" value="{{ old('first_name.'.$lang->code) ?? $employ->first_name[$lang->code]}}" id="title" placeholder="first name...">
                                                    @error('first_name.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name" class="form-label">Last name</label>
                                                    <input type="text" class="form-control @error('last_name.'.$lang->code) is-invalid @enderror" name="last_name[{{ $lang->code }}]" value="{{ old('last_name.'.$lang->code) ?? $employ->last_name[$lang->code] }}" id="last_name" placeholder="Last name...">
                                                    @error('last_name.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="surname" class="form-label">Surname</label>
                                                    <input type="text" class="form-control @error('surname.'.$lang->code) is-invalid @enderror" name="surname[{{ $lang->code }}]" value="{{ old('surname.'.$lang->code) ?? $employ->surname[$lang->code] }}" id="surname" placeholder="Surname...">
                                                    @error('surname.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" class="form-control @error('address.'.$lang->code) is-invalid @enderror" name="address[{{ $lang->code }}]" value="{{ old('address.'.$lang->code)?? $employ->address[$lang->code] }}" id="address" placeholder="Address..">
                                                    @error('address.'.$lang->code)
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="form-label">Email</label>
                                    <input type="email" id="date" name="email" class="form-control" value="{{ old('email') ?? $employ->email}}" placeholder="email"  />
                                </div>
                                <div class="form-group">
                                    <label for="date" class="form-label">phone</label>
                                    <input type="phone" id="date" name="phone" class="form-control" value="{{ old('phone') ?? $employ->phone }}" placeholder="phone"  />
                                </div>
                            </div>
                            <!-- Button -->
                            <div class="model-btns d-flex justify-content-end">
                                <a href="{{ route('posts_categories.index') }}" type="button" class="btn btn-secondary">cancel</a>
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
                                    <label for="date" class="form-label">Birthday</label>
                                    <input type="date" id="date" name="birthday" class="form-control" value="{{ old('birthday')?? $employ->birthday }}" placeholder="{{ date('d-m-Y') }}" />
                                </div>
                                <div class="form-group">
                                    <label for="date" class="form-label">start time</label>
                                    <input type="date" id="date" name="start_time" class="form-control" value="{{ old('start_time') ?? $employ->start_time }}" placeholder="{{ date('d-m-Y') }}"  />
                                </div>
                                <div class="form-group">
                                    <label for="menu_id" class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $employ->gender == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $employ->gender == 0 ? 'selected' : '' }}>NeActive</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="menu_id" class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="male" {{ $employ->gender == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $employ->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="special"
                                                       id="switchTwo"
                                                       value="1"
                                                        {{ $employ->special ? 'checked' : '' }}>
                                                <label class="form-check-label" for="switchTwo"></label>
                                            </div>
                                        </div>
                                        <div class="col ms-n2">
                                            <small class="text-muted">
                                                Special
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="professor"
                                                       id="switchTwo"
                                                       value="1"
                                                        {{ $employ->professor ? 'checked' : '' }}>
                                                <label class="form-check-label" for="switchTwo"></label>
                                            </div>
                                        </div>
                                        <div class="col ms-n2">
                                            <small class="text-muted">
                                                Professor
                                            </small>
                                        </div>
                                    </div>
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
    </form>
</div>
@endsection
