@extends('layouts.web')

@section('title', 'Разработки')

@section('links')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="{{ asset('client/css/developments.css') }}">
<script src="{{ asset('client/js/developments.js') }}" defer></script>

@endsection

@section('content')

<main class="main">
    <div class="intro">
        
        @include('includes.header')

        <div class="intro__inner container">
            <p class="pageTitle">{{ translation('developments.pageTitle') }}</p>
            <div class="pageNavigation">
                <span class="pageNavigation__text"><a href="{{ route('index') }}">{{ translation('main.mainPage') }}</a></span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text blue">{{ translation('main.developments') }}</span>
            </div>
        </div>
        <img src="{{ asset('client/media/developments.hero.jpg') }}" alt="hero-kamaz">
    </div>
    <div class="productionSection">
        <div class="section__inner container">
            <p class="sectionTitle">{{ translation('developments.aboutProduction') }}</p>
            <p class="sectionSubtitle">{{ translation('about.text1') }}</p>
            <div class="productionVideoDiv">
                <video class="video" controls muted loop>
                    <source src="{{ asset('client/media/fon-video2.mp4') }}" type="video/mp4" alt="video">
                </video>
                <div class="videoMask">
                    <div class="playButton">
                        <svg class="playBtnSvg" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6.05078L38 24.0008L10 41.9508V6.05078Z" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="backTextDiv">
            <p class="backText">{{ translation('developments.aboutProduction') }}</p>
        </div>
    </div>
    <div id="portfolio" class="section">
        <div class="portfolioInner section__inner container">
            @foreach($developments as $item)
            <div class="portfolioDiv">
                <div class="portfolioCategory">
                    <p>{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : (isset($item->title[$main_lang->code]) ? $item->title[$main_lang->code] : '') }}</p>
                </div>
                <div class="portfolio">
                    <div class="portfolioImage"><img src="{{ default_img($item->lg_img) }}" alt="portfolioImage"></div>
                    <div class="categoriesDiv">
                        <div class="category">
                            <p>{{ isset($item->option1[app()->getLocale()]) ? $item->option1[app()->getLocale()] : (isset($item->option1[$main_lang->code]) ? $item->option1[$main_lang->code] : '') }}</p>
                        </div>
                        <div class="category">
                            <p>{{ isset($item->option2[app()->getLocale()]) ? $item->option2[app()->getLocale()] : (isset($item->option2[$main_lang->code]) ? $item->option2[$main_lang->code] : '') }}</p>
                        </div>
                    </div>
                    <div class="portfolioTextDiv">
                        <div class="portfolioText">
                            {!! isset($item->desc[app()->getLocale()]) ? $item->desc[app()->getLocale()] : (isset($item->desc[$main_lang->code]) ? $item->desc[$main_lang->code] : '') !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- <div class="centeredDiv">
            <div class="centerBtnDiv">
                <p class="centerBtn">ЗАГРУЗИТЬ ЕЩЁ</p>
            </div>
        </div> -->
    </div>
</main>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

@endsection