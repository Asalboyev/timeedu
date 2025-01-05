<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/img/favicon.svg" type="image/x-icon" />

    <!-- Map CSS -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css" />

    <!-- Libs CSS -->
    <link rel="stylesheet" href="/assets/css/libs.bundle.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="/assets/css/theme.bundle.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Select all textareas with the class 'ckeditor'
            const textareas = document.querySelectorAll('textarea.ckeditor');

            textareas.forEach(textarea => {
                ClassicEditor
                    .create(textarea, {
                        ckfinder: {
                            uploadUrl: '{{ route('upload', ['_token' => csrf_token()]) }}'
                        },
                        toolbar: [
                            'heading', '|', 'bold', 'italic', 'link', 'bulletedList',
                            'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo',
                            'imageUpload'
                        ],
                        image: {
                            toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
                        }
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor 5:', error);
                    });
            });
        });
    </script>


    <title>Dashboard | NDC.uz</title>

    @yield('links')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css">
    <style>
        .required:after {
            content: '*';
            color: red;
        }

        .active {
            color: #12263f;
        }
        .ck-editor__editable {
            min-height: 200px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.5rem;
            background-color: #fff;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .ck-editor__editable:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .dz-success-mark,
        .dz-error-mark,
        .dz-details {
            display: none;
        }

        .imb-block {
            width: 80px;
            height: 80px;
        }

        .imb-block>img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dropzone {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .dz-default.dz-message {
            width: 100%;
            margin-bottom: 12px;
        }

        .dz-preview {
            width: fit-content;
            margin-right: 12px;
            margin-bottom: 32px;
            max-width: 120px;
            height: 120px;
        }

        .dz-preview .dz-image {
            width: 100%;
            height: 100%;
        }

        .dz-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div id="preloader" style="
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(255,255,255,0.9);
        width: 100%;
        height: 100%;
        z-index: 9999;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
        <img src="/assets/img/preloader.gif" style="width: 100px;">
    </div>
    @if(!isset($no_sidebar))
    <!-- NAVIGATION -->
    <nav class="navbar navbar-vertical fixed-start navbar-expand-md navbar-light" id="sidebar">
        <div class="container-fluid">

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Brand -->
            <a class="navbar-brand">
                <img src="/assets/img/logo.png" class="navbar-brand-img mx-auto" alt="...">
            </a>

            <!-- User (xs) -->
            <div class="navbar-user d-md-none">

                <!-- Dropdown -->
                <div class="dropdown">

                    <!-- Toggle -->
                    <a href="#" id="sidebarIcon" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar avatar-sm avatar-online">
                            <img src="/assets/img/avatars/profiles/avatar-6.jpg" class="avatar-img rounded-circle" alt="...">
                        </div>
                    </a>

                    <!-- Menu -->
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarIcon">
                        <a href="/sign-in.html" class="dropdown-item">Logout</a>
                    </div>

                </div>

            </div>

            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidebarCollapse">

                <!-- Navigation -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin') }}">
                            <i class="fe fe-home"></i> Control Panel
                        </a>
                    </li>
                    <hr class="navbar-divider my-3">
{{--                    @if($menu_items->where('route', 'applications')->where('is_active', 1)->first())--}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/applications') || request()->is('admin/applications/*') ? 'active' : '' }}" href="{{ route('applications.index') }}">
                            <i class="fe fe-archive"></i> Surveys
                        </a>
                    </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#menus" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('admin/menus') || request()->is('admin/menus/*') || request()->is('admin/dynamic-menus') || request()->is('admin/dynamic-menus/*') || request()->is('admin/menus') || request()->is('admin/menus/*') || request()->is('admin/menus') || request()->is('admin/menus/*') ? 'true' : 'false' }}" aria-controls="menus">
                                <i class="fe fe-layers"></i> Menus
                            </a>
                            <div class="collapse {{ request()->is('admin/menus') || request()->is('admin/menus/*') || request()->is('admin/dynamic-menus') || request()->is('admin/dynamic-menus/*') || request()->is('admin/dynamic-menus') || request()->is('admin/dynamic-menus/*') || request()->is('admin/dynamic-menus') || request()->is('admin/dynamic-menus/*') ? 'show' : '' }}" id="menus">
                                <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/menus') || request()->is('admin/menus/*') ? 'active' : '' }}" href="{{ route('menus.index') }}">
                                                Menus
                                            </a>
                                        </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/dynamic-menus') || request()->is('admin/dynamic-menus/*') ? 'active' : '' }}" href="{{ route('dynamic-menus.index') }}">
                                            Dynamic-menus
                                        </a>
                                    </li>


                                    {{--                                @if($menu_items->where('route', 'banners')->where('is_active', 1)->first())--}}

                                </ul>
                            </div>
                        </li>




                    <hr class="navbar-divider my-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#static_info" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('admin/site_infos') || request()->is('admin/site_infos/*') || request()->is('admin/additional_functions') || request()->is('admin/additional_functions/*') || request()->is('admin/users') || request()->is('admin/users/*') || request()->is('admin/translations') || request()->is('admin/translations/*') || request()->is('admin/langs') || request()->is('admin/langs/*') || request()->is('admin/logs') || request()->is('admin/logs/*') ? 'true' : 'false' }}" aria-controls="documents">
                            <i class="fe fe-settings"></i> Настройки сайта
                        </a>
                        <div class="collapse {{ request()->is('admin/site_infos') || request()->is('admin/site_infos/*') || request()->is('admin/additional_functions') || request()->is('admin/additional_functions/*') || request()->is('admin/users') || request()->is('admin/users/*') || request()->is('admin/translations') || request()->is('admin/translations/*') || request()->is('admin/langs') || request()->is('admin/langs/*') || request()->is('admin/logs') || request()->is('admin/logs/*') ? 'show' : '' }}" id="static_info">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/site_infos') || request()->is('admin/site_infos/*') ? 'active' : '' }}" href="{{ route('site_infos.index') }}">
                                        Общие данные
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/additional_functions') || request()->is('admin/additional_functions/*') ? 'active' : '' }}" href="{{ route('additional_functions.index') }}">
                                        Дополнительные сервисы
                                    </a>
                                </li>
                                @if(auth()->user()->role == 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/logs') || request()->is('admin/logs/*') ? 'active' : '' }}" href="{{ route('logs.index') }}">
                                        <i class="fe fe-file-text"></i> Логи
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fe fe-users"></i> Админы
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/translations') || request()->is('admin/translations/*') ? 'active' : '' }}" href="{{ route('translations.index') }}">
                                        <i class="fe fe-book-open"></i> Переводы
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/langs') || request()->is('admin/langs/*') ? 'active' : '' }}" href="{{ route('langs.index') }}">
                                        <i class="fe fe-globe"></i> Языки
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @if(request()->is('admin/config/Mmzf9N7QuCXDSk32'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/config/Mmzf9N7QuCXDSk32') ? 'active' : '' }}" href="{{ route('config') }}">
                            Config
                        </a>
                    </li>
                    @endif

                </ul>

                <!-- Push content down -->
                <div class="mt-auto"></div>


                <!-- User (md) -->
                <div class="navbar-user d-none d-md-flex" id="sidebarUser">

                    <!-- Dropup -->
                    <div class="dropup">

                        <!-- Toggle -->
                        <a href="#" id="sidebarIconCopy" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-sm avatar-online">
                                <img src="/assets/img/avatars/profiles/default_user.png" class="avatar-img rounded-circle" alt="...">
                            </div>
                        </a>

                        <!-- Menu -->
                        <div class="dropdown-menu" aria-labelledby="sidebarIconCopy">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="dropdown-item">Выйти</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>

                    </div>

                </div>

            </div> <!-- / .navbar-collapse -->

        </div>
    </nav>
    @endif
    <!-- MAIN CONTENT -->
    <div class="main-content">


        @yield('content')


    </div><!-- / .main-content -->

    <!-- JAVASCRIPT -->
    <!-- Map JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>

    <!-- Vendor JS -->
    <script src="/assets/js/vendor.bundle.js"></script>

    <!-- Theme JS -->
    <script src="/assets/js/theme.bundle.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js" integrity="sha512-u9akINsQsAkG9xjc1cnGF4zw5TFDwkxuc9vUp5dltDWYCSmyd0meygbvgXrlc/z7/o4a19Fb5V0OUE58J7dcyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    @yield('scripts')

    @if (session()->has('success') && session('success') == false)
    <script type="text/javascript">
        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top',
            },
            types: [{
                type: 'error',
                background: '#b82c46',
                icon: {
                    className: 'fe fe-x',
                    tagName: 'span',
                    color: '#fff'
                },
                dismissible: false
            }]
        });
        notyf.open({
            type: 'error',
            message: <?= json_encode(session('message')) ?>
        });
    </script>
    @endif

    @if (session()->has('success') && session('success') == true)
    <script type="text/javascript">
        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top',
            },
            types: [{
                type: 'success',
                background: '#00ae65',
                icon: {
                    className: 'fe fe-check-circle',
                    tagName: 'span',
                    color: '#fff'
                },
                dismissible: false
            }]
        });
        notyf.open({
            type: 'success',
            message: <?= json_encode(session('message')) ?>
        });
    </script>
    @endif

    <script>
        var preloader = document.getElementById('preloader');

        preloader.classList.add('d-none');
    </script>

</body>

</html>
