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


    <title>Dashboard | Timeedu</title>

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

                    @if(auth()->user()->role == 'admin')
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
                        <li class="nav-item">
                            <a class="nav-link" href="#posts" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('posts') || request()->is('posts/*') || request()->is('posts_categories') || request()->is('posts_categories/*') ? 'true' : 'false' }}" aria-controls="posts">
                                <i class="fe fe-cast"></i> Posts
                            </a>
                            <div class="collapse {{ request()->is('admin/posts') || request()->is('admin/posts/*') || request()->is('admin/posts_categories') || request()->is('admin/posts_categories/*') ? 'show' : '' }}" id="posts">
                                <ul class="nav nav-sm flex-column">
                                    @if($menu_items->where('route', 'posts')->where('is_active', 1)->first())
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/posts') || request()->is('admin/posts/*') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                                                Posts
                                            </a>
                                        </li>
                                    @endif
                                    @if($menu_items->where('route', 'posts_categories')->where('is_active', 1)->first())
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/posts_categories') || request()->is('admin/posts_categories/*') ? 'active' : '' }}" href="{{ route('posts_categories.index') }}">
                                                Categories
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                       <li class="nav-item">
                        <a class="nav-link" href="#educational-programs" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('educational-programs') || request()->is('educational-programs/*') || request()->is('educational-programs') || request()->is('educational-programs/*') || request()->is('skills') || request()->is('skills/*') || request()->is('activities') || request()->is('activities/*') || request()->is('entrance-requirements') || request()->is('entrance-requirements/*') ? 'true' : 'false' }}" aria-controls="educational-programs">
                            <i class="fe fe-cast"></i> Educational Programs
                        </a>
                        <div class="collapse {{ request()->is('admin/educational-programs') || request()->is('admin/educational-programs/*') || request()->is('admin/educational-programs') || request()->is('admin/educational-programs/*')|| request()->is('admin/skills') || request()->is('admin/skills/*') || request()->is('admin/entrance-requirements') || request()->is('admin/entrance-requirements/*') || request()->is('admin/activities') || request()->is('admin/activities/*') ? 'show' : '' }}" id="educational-programs">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/educational-programs') || request()->is('admin/educational-programs/*') ? 'active' : '' }}" href="{{ route('educational-programs.index') }}">
                                            Educational Programs
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/activities') || request()->is('admin/activities/*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                                            Activities
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/entrance-requirements') || request()->is('admin/entrance-requirements/*') ? 'active' : '' }}" href="{{ route('entrance-requirements.index') }}">
                                            Entrance Requirement
                                        </a>
                                    </li>

                                <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/skills') || request()->is('admin/skills/*') ? 'active' : '' }}" href="{{ route('skills.index') }}">
                                            Skills
                                        </a>
                                    </li>

                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#positions" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('positions') || request()->is('positions/*') || request()->is('employ_staff') || request()->is('employ_staff/*') || request()->is('employ_forms') || request()->is('employ_forms/*')|| request()->is('employ_type') || request()->is('employ_type/*') || request()->is('stracture_types') || request()->is('stracture_types/*') || request()->is('employs') || request()->is('employs/*') || request()->is('departaments') || request()->is('departaments/*') || request()->is('employ_meta') || request()->is('employ_meta/*')? 'true' : 'false' }}" aria-controls="posts">
                            <i class="fe fe-cast"></i> Employees
                        </a>
                        <div class="collapse {{ request()->is('admin/positions') || request()->is('admin/positions/*') || request()->is('admin/employ_staff') || request()->is('admin/employ_staff/*') || request()->is('admin/employ_forms') || request()->is('admin/employ_forms/*') || request()->is('admin/employ_types') || request()->is('admin/employ_types/*') || request()->is('admin/stracture_types') || request()->is('admin/stracture_types/*')  || request()->is('admin/employs') || request()->is('admin/employs/*')  || request()->is('admin/departaments') || request()->is('admin/departaments/*') || request()->is('admin/employ_meta') || request()->is('admin/employ_meta/*') ? 'show' : '' }}" id="positions">
                            <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/positions') || request()->is('admin/positions/*') ? 'active' : '' }}" href="{{ route('positions.index') }}">
                                            Positions
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/employ_staff') || request()->is('admin/employ_staff/*') ? 'active' : '' }}" href="{{ route('employ_staff.index') }}">
                                            Employ Staff
                                        </a>
                                    </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/employ_forms') || request()->is('admin/employ_forms/*') ? 'active' : '' }}" href="{{ route('employ_forms.index') }}">
                                        Employ Form
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/employ_types') || request()->is('admin/employ_types/*') ? 'active' : '' }}" href="{{ route('employ_types.index') }}">
                                         Pmploy Typle
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/stracture_types') || request()->is('admin/stracture_types/*') ? 'active' : '' }}" href="{{ route('stracture_types.index') }}">
                                        Stracture Typle
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/employs') || request()->is('admin/employs/*') ? 'active' : '' }}" href="{{ route('employs.index') }}">
                                        Employs
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/departaments') || request()->is('admin/departaments/*') ? 'active' : '' }}" href="{{ route('departaments.index') }}">
                                        Departaments
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/employ_meta') || request()->is('admin/employ_meta/*') ? 'active' : '' }}" href="{{ route('employ_meta.index') }}">
                                        Employ meta
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                @if($menu_items_groups->where('title', 'Компания')->where('is_active', 1)->first())
                        <li class="nav-item">
                            <a class="nav-link" href="#company" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('admin/feedbacks') || request()->is('admin/feedbacks/*') || request()->is('admin/students') || request()->is('admin/students/*') || request()->is('admin/partners') || request()->is('admin/partners/*') || request()->is('admin/questions') || request()->is('admin/questions/*') || request()->is('admin/banners') || request()->is('admin/banners/*') || request()->is('admin/vacancies') || request()->is('admin/vacancies/*') || request()->is('admin/certificates') || request()->is('admin/certificates/*')  || request()->is('admin/documents') || request()->is('admin/documents/*') || request()->is('admin/services') || request()->is('admin/services/*') || request()->is('admin/kampuses') || request()->is('admin/kampuses/*') ? 'true' : 'false' }}" aria-controls="documents">
                                <i class="fe fe-star"></i> Group
                            </a>
                            <div class="collapse {{ request()->is('admin/feedbacks') || request()->is('admin/feedbacks/*') || request()->is('admin/students') || request()->is('admin/students/*') || request()->is('admin/partners') || request()->is('admin/partners/*') || request()->is('admin/questions') || request()->is('admin/questions/*') || request()->is('admin/banners') || request()->is('admin/banners/*') || request()->is('admin/vacancies') || request()->is('admin/vacancies/*') || request()->is('admin/certificates') || request()->is('admin/certificates/*')  || request()->is('admin/documents') || request()->is('admin/documents/*') || request()->is('admin/services') || request()->is('admin/services/*') || request()->is('admin/kampuses') || request()->is('admin/kampuses/*') ? 'show' : '' }}" id="company">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/banners') || request()->is('admin/banners/*') ? 'active' : '' }}" href="{{ route('banners.index') }}">
                                             Banners
                                        </a>
                                    </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/students') || request()->is('admin/students/*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                                                Students
                                            </a>
                                        </li>

                                    @if($menu_items->where('route', 'partners')->where('is_active', 1)->first())
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/partners') || request()->is('admin/partners/*') ? 'active' : '' }}" href="{{ route('partners.index') }}">
                                                Partners
                                            </a>
                                        </li>
                                    @endif

                                    @if($menu_items->where('route', 'vacancies')->where('is_active', 1)->first())
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/vacancies') || request()->is('admin/vacancies/*') ? 'active' : '' }}" href="{{ route('vacancies.index') }}">
                                                Vacancies
                                            </a>
                                        </li>
                                    @endif
                                    @if($menu_items->where('route', 'questions')->where('is_active', 1)->first())
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/questions') || request()->is('admin/questions/*') ? 'active' : '' }}" href="{{ route('questions.index') }}">
                                                FAQ
                                            </a>
                                        </li>
                                    @endif
                                    @if($menu_items->where('route', 'certificates')->where('is_active', 1)->first())
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/certificates') || request()->is('admin/certificates/*') ? 'active' : '' }}" href="{{ route('certificates.index') }}">
                                                Certificates
                                            </a>
                                        </li>
                                    @endif
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/documents') || request()->is('admin/documents/*') ? 'active' : '' }}" href="{{ route('documents.index') }}">
                                                Documents
                                            </a>
                                        </li>
                                    <li class="nav-item">
                                            <a class="nav-link {{ request()->is('admin/services') || request()->is('admin/services/*') ? 'active' : '' }}" href="{{ route('services.index') }}">
                                                Services
                                            </a>
                                        </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('admin/kampuses') || request()->is('admin/kampuses/*') ? 'active' : '' }}" href="{{ route('kampuses.index') }}">
                                            kampuses
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif

                    <hr class="navbar-divider my-3">
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="#static_info" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('admin/site_infos') || request()->is('admin/site_infos/*') || request()->is('admin/additional_functions') || request()->is('admin/additional_functions/*') || request()->is('admin/users') || request()->is('admin/users/*') || request()->is('admin/translations') || request()->is('admin/translations/*') || request()->is('admin/langs') || request()->is('admin/langs/*') || request()->is('admin/logs') || request()->is('admin/logs/*') ? 'true' : 'false' }}" aria-controls="documents">
                            <i class="fe fe-settings"></i> Setting
                        </a>
                        <div class="collapse {{ request()->is('admin/site_infos') || request()->is('admin/site_infos/*') || request()->is('admin/additional_functions') || request()->is('admin/additional_functions/*') || request()->is('admin/users') || request()->is('admin/users/*') || request()->is('admin/translations') || request()->is('admin/translations/*') || request()->is('admin/langs') || request()->is('admin/langs/*') || request()->is('admin/logs') || request()->is('admin/logs/*') ? 'show' : '' }}" id="static_info">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/site_infos') || request()->is('admin/site_infos/*') ? 'active' : '' }}" href="{{ route('site_infos.index') }}">
                                        General information
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/facts_figures') || request()->is('admin/facts_figures/*') ? 'active' : '' }}" href="{{ route('facts_figures') }}">
                                        FACTS AND FIGURES
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
                                        <i class="fe fe-users"></i> Admin
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/translations') || request()->is('admin/translations/*') ? 'active' : '' }}" href="{{ route('translations.index') }}">
                                        <i class="fe fe-book-open"></i> Translations
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/langs') || request()->is('admin/langs/*') ? 'active' : '' }}" href="{{ route('langs.index') }}">
                                        <i class="fe fe-globe"></i> Lang
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
