@extends('layouts.web')

@section('title', isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code])

@section('content')

<main class="main">
    <div class="intro">

        @include('includes.header')

        <div class="intro__inner container">
            <p class="pageTitle">{{ isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code] }}</p>
            <div class="pageNavigation">
                <span class="pageNavigation__text"><a href="{{ route('index') }}">{{ translation('main.mainPage') }}</a></span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text blue" style="text-transform: capitalize;">{{ isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code] }}</span>
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
    <div class="section catalogueSection">
        <div class="catalogue container">
            @foreach($categories as $item)
            <div class="product">
                <a class="productLink" href="{{ route('products', ['id' => $item->id]) }}">
                    <div class="product__content">
                        <p class="product__title">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</p>
                        <div class="product__subtitle">{!! isset($item->desc[app()->getLocale()]) ? $item->desc[app()->getLocale()] : $item->desc[$main_lang->code] !!}</div>
                    </div>
                    <div class="product__image"><img src="{{ $item->md_img }}" alt="{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}"></div>
                </a>
            </div>
            @endforeach
        </div>
        <!-- <div class="centeredDiv">
            <div class="centerBtnDiv">
                <p class="centerBtn">Загрузить ещё</p>
            </div>
        </div> -->
        <div class="bgTomchiWhite">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</main>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

@endsection