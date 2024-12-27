@extends('layouts.web')

@section('title', isset($product->title[app()->getLocale()]) ? $product->title[app()->getLocale()] : $product->title[$main_lang->code])

@section('links')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="{{ asset('client/css/inner-inner.css') }}">
<script src="{{ asset('client/js/inner-inner.js') }}" defer></script>
@endsection

@section('content')

<main class="main">
    <div class="intro">

        @include('includes.header')

        <div class="intro__inner container">
            <p class="pageTitle">{{ isset($product->title[app()->getLocale()]) ? $product->title[app()->getLocale()] : $product->title[$main_lang->code] }}</p>
            <div class="pageNavigation">
                <span class="pageNavigation__text"><a href="{{ route('index') }}">{{ translation('main.mainPage') }}</a></span>
                <!-- <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text">Каталог</span> -->
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text">{{ isset($product->productsCategories[0]) ? (isset($product->productsCategories[0]->title[app()->getLocale()]) ? $product->productsCategories[0]->title[app()->getLocale()] : $product->productsCategories[0]->title[$main_lang->code]) : 'Продукты' }}</span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text blue">{{ isset($product->title[app()->getLocale()]) ? $product->title[app()->getLocale()] : $product->title[$main_lang->code] }}</span>
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
    <p class="mainTitlePage">{{ isset($product->title[app()->getLocale()]) ? $product->title[app()->getLocale()] : $product->title[$main_lang->code] }}</p>
    <!-- Swiper -->
    <div class="introSwiper">
        <div class="swiper mainImagesDiv">
            <div class="swiper-wrapper">
                @foreach($product->productImages as $item)
                <div class="swiper-slide bigImage">
                    <img src="{{ default_img($item->lg_img) }}" alt="{{ isset($product->title[app()->getLocale()]) ? $product->title[app()->getLocale()] : $product->title[$main_lang->code] }}" />
                </div>
                @endforeach
            </div>
        </div>
        <div class="miniImagesSection">
            <div class="swiper mySwiper container">
                <div class="swiper-wrapper">
                    @foreach($product->productImages as $item)
                    <div class="swiper-slide miniImage">
                        <img src="{{ default_img($item->lg_img) }}" alt="miniImage" />
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- End - Swiper -->
    <div class="characteristicsSection">
        <div class="characteristics__inner">
            @if(isset($product->desc[app()->getLocale()]) ? $product->desc[app()->getLocale()] : $product->desc[$main_lang->code])
            <div class="textDiv">
                <p class="textDivTitle">{{ translation('main.appointments') }}</p>
                <div class="textDivSubtitle">{!! isset($product->desc[app()->getLocale()]) ? $product->desc[app()->getLocale()] : $product->desc[$main_lang->code] !!}</div>
            </div>
            @endif
            @if(isset($product->info[app()->getLocale()]) ? $product->info[app()->getLocale()] : $product->info[$main_lang->code])
            <div class="textDiv">
                <p class="textDivTitle">Технические зарактеристики</p>
                <div class="table">
                    {!! isset($product->info[app()->getLocale()]) ? $product->info[app()->getLocale()] : $product->info[$main_lang->code] !!}
                    <!-- <div class="miniTable">
                        <p class="key">Колёсная форимула / ведущие колеса</p>
                        <p class="value">6х6 / полноприводный</p>
                    </div>
                    <div class="miniTable">
                        <p class="key">Габаритные размеры,мм (длина /ширина/ высота) </p>
                        <p class="value">9 425 х 2 550 х 3 660</p>
                    </div>
                    <div class="miniTable">
                        <p class="key">Полная масса, кг</p>
                        <p class="value">21,600</p>
                    </div>
                    <div class="miniTable">
                        <p class="key">Нагрузка на передний мост, кг 5,800.0</p>
                        <p class="value">5,800.0</p>
                    </div>
                    <div class="miniTable">
                        <p class="key">Двигатель</p>
                        <p class="value">ЯМЗ 1105 5 СТ</p>
                    </div>
                    <div class="miniTable">
                        <p class="key">Тип толива / Объём топливного бака, л </p>
                        <p class="value">Дизел / 300</p>
                    </div>
                    <div class="miniTable">
                        <p class="key">Расход топлива на 100 км, л </p>
                        <p class="value">42</p>
                    </div> -->
                </div>
            </div>
            @endif
            <!-- <div class="textDiv">
                <p class="textDivTitle">Оснащение:</p>
                <div class="miniTextDiv">
                    <p class="textDivSubtitle">Изготовлена изотермических панелей. Оснашена сварочными аппратамиб кислородными и газовыми баллонами, дизельным генератором,талько грузоподъемностью до 1000 кг,, поршневым воздушным компрессором электричесим вентилятором, моечным аппратом высокого давления с подогревом воды.</p>
                    <p class="textDivSubtitle">Оснащена кварцевыми лампами с полным дистанционным управлением ( включениеб экспозици по таймеру, отключение).</p>
                </div>
            </div> -->
        </div>
    </div>
    <div class="section">
        <div class="section__inner container">
            <p class="sectionTitle">{{ translation('main.relatedProducts') }}</p>
            <div class="catalogue container">
                @foreach($other_products as $item)
                <div class="product">
                    <a class="productLink" href="{{ route('product', ['id' => $item->id]) }}">
                        <div class="product__content">
                            <p class="product__title">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</p>
                            <div class="product__subtitle">{!! isset($item->desc[app()->getLocale()]) ? $item->desc[app()->getLocale()] : $item->desc[$main_lang->code] !!}</div>
                        </div>
                        <div class="product__image"><img src="{{ isset($item->productImages[0]) ? default_img($item->productImages[0]->md_img) : default_img(null) }}" alt="zapcast"></div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <div class="backTextDiv">
            <p class="backText">{{ translation('main.relatedProducts') }}</p>
        </div>
    </div>
</main>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

@endsection