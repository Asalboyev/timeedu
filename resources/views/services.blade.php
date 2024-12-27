@extends('layouts.web')

@section('title', 'Услуги')

@section('links')

<link rel="stylesheet" href="{{ asset('client/css/services.css') }}">

@endsection

@section('content')

<main class="main">
    <div class="intro">

        @include('includes.header')

        <div class="intro__inner container">
            <p class="pageTitle">{{ translation('main.services') }}</p>
            <div class="pageNavigation">
                <span class="pageNavigation__text"><a href="{{ route('index') }}">{{ translation('main.mainPage') }}</a></span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text blue">{{ translation('main.services') }}</span>
            </div>
        </div>
        <div class="bgTomchiBlack">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="mainContainer">
        <div class="mainImageContainer">
            <div class="mainImage"><img class="mainImage__img" src="{{ $services[0]->md_img }}" alt="service-image"></div>
        </div>
        <div class="mainContent">
            <div class="serviceList">
                @foreach($services as $item)
                <div id="service{{ $item->id }}" class="service">
                    <div class="serviceImage"><img src="{{ default_img($item->md_img) }}" alt="serviceImage"></div>
                    <p class="serviceName">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</p>
                    <div class="serviceTextDiv">
                        <div class="serviceText">{!! isset($item->desc[app()->getLocale()]) ? $item->desc[app()->getLocale()] : $item->desc[$main_lang->code] !!}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="serviceMiniList">
                <div class="miniServicesDiv">
                    @foreach($services as $item)
                    <div class="miniServiceDiv"><a class="miniService" href="#service{{ $item->id }}">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</a></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('scripts')

<script>
    const image = document.querySelector(".mainImage__img")
    @foreach($services as $item)
    var service{{ $item->id }} = document.querySelector("#service{{ $item->id }}")
    @endforeach

    window.addEventListener("scroll", () => {
        const scrollNumber = window.pageYOffset;
        @foreach($services as $item)
        if (scrollNumber >= service{{ $item->id }}.offsetTop - 100) {
            image.setAttribute("src", "{{ default_img($item->md_img) }}")
        }
        @endforeach
    })
</script>

@endsection