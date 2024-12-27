@extends('layouts.web')

@section('title', isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code])

@section('links')
<link rel="stylesheet" href="{{ asset('client/css/spectechs.css') }}">
<meta name="description" content="Каталог Promavto">
@endsection

@section('content')

<main id="main" class="main">
    <div id="spectechs" class="intro">

        @include('includes.header')

        <div class="intro__inner container">
            <p class="pageTitle">{{ isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code] }}</p>
            <div class="pageNavigation">
                <span class="pageNavigation__text"><a href="{{ route('index') }}">{{ translation('main.mainPage') }}</a></span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text">{{ isset($category->parent) ? (isset($category->parent->title[app()->getLocale()]) ? $category->parent->title[app()->getLocale()] : $category->parent->title[$main_lang->code] ) : 'Каталог' }}</span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text blue">{{ isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code] }}</span>
            </div>
            <img src="{{ default_img($category->lg_img) }}" alt="hero-kamaz">
        </div>
    </div>
    <div class="miniTextDiv">
        <div class="miniTextDiv__inner">
            <p class="textTitle">{{ isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code] }}</p>
            <div class="miniText__textsDiv">
                <div class="textsDiv__text">{!! isset($category->desc[app()->getLocale()]) ? $category->desc[app()->getLocale()] : $category->desc[$main_lang->code] !!}</div>
            </div>
        </div>
    </div>
    @if(isset($products[0]))
    <div class="solutionsDiv" style="margin-bottom: 40px;">
        <div class="section__inner container">
            <p class="solutionsMainText">{{ translation('main.solutionsMainText') }}</p>
            <div class="solutionsContainer">
                @foreach($products as $item)
                <div class="solutionCard">
                    <a class="solutionCardLink" href="{{ route('product', ['id' => $item->id]) }}">
                        <div class="solutionInfoDiv">
                            <div class="soltuonCardTitle">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</div>
                            <div class="soltuonCardSubtitle">{!! isset($item->desc[app()->getLocale()]) ? $item->desc[app()->getLocale()] : $item->desc[$main_lang->code] !!}</div>
                        </div>
                        <div class="solutionImageDiv"><img src="{{ isset($item->productImages[0]) ? default_img($item->productImages[0]->md_img) : default_img(null) }}" alt="{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}"></div>
                        <div class="readMoreDiv">
                            <span class="readMoreText">{{ translation('main.readMore') }}</span>
                            <svg class="readMoreArrow" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22.5C17.5228 22.5 22 18.0228 22 12.5C22 6.97715 17.5228 2.5 12 2.5C6.47715 2.5 2 6.97715 2 12.5C2 18.0228 6.47715 22.5 12 22.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 16.5L16 12.5L12 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8 12.5H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <!-- <div class="infoTextSection">
        <div class="section__inner container">
            <div class="infoTextsDiv">
                <p class="infoText">Вахтовый автобус специализирован для длительных поездок в разных климатических условиях, поэтому помимо технических характеристик автобуса, важно, чтобы кабина была удобна и комфортна в эксплуатации. Автобус оснащается отопительной системой и вентиляцией для поддержания комфортного температурного уровня и обеспечения циркуляции воздуха в фургоне. Салон автофургона оборудуется высокими сидениями с трехточечными ремнями безопасности для комфортной перевозки рабочих бригад. Вахтовые автобусы оснащаются аварийными дверями для оперативного принятия мер в экстренных ситуациях.</p>
                <div class="infoTextPadding">
                    <p class="paddingTitle">Классификация автобусов по предназначению</p>
                    <p class="paddingSubtitle">Различают два типа вахтовок: пассажирские и грузопассажирские. Последние отличаются наличием грузового отсека, отделенного от салона глухой перегородкой.</p>
                </div>
                <div class="infoTextPadding">
                    <p class="paddingTitle">Преимущества нашей продукции</p>
                    <p class="paddingSubtitle">Вахтовый автобус произведенный на заводе ПРОМАВТО отличается долговечностью эксплуатации. Контроль качества – один из важнейших приоритетов в нашей работе. Именно поэтому мы работаем только с оригинальными сертифицированными комплектующими и высокотехнологичным оборудованием.</p>
                </div>
                <p class="infoText">Мы предлагаем нашим клиентам вахтовые автобусы, изготовленные по современной бескаркасной технологии из 5-слойных сэндвич-панелей (вы можете купить вахтовый автобус, выбрав одну из 3 комплектаций С1, С2, С3). В комплектации С3 наружная обшивка выполнена из армированного стекловолокном пластика с UV-фильтромом толщиной 1,5 мм (опционально 2,7 мм) и внутренняя обшивка из ударопрочного пластика толщиной 1,4 мм.</p>
                <p class="infoText">Большое внимание уделяется комфорту и безопасности пассажиров пути. В настоящее время мы оборудуем наши вахтовые специальные автобусы удобными травмобезопасными сидениями (модели «СП-01», «СП-02», «Турист»), снабженными прочными двух- или трехточечными ремнями безопасности, а также системой регулировки положений спинки.</p>
                <p class="infoText">В салоне предусмотрен «дежурный» режим освещения. Боковая входная дверь оборудуется раскладной ступенькой. Для удобства работы в темное время суток на ступенях устанавливается светодиодная подсветка, которая автоматически включается при открывании двери.</p>
                <p class="infoText">Вахтовый автобус завода ПРОМАВТО применим и для районов Крайнего Севера. Для этих условий производим автобусы из сэндвич-панелей с более толстым слоем утеплителя (80 мм вместо стандартных 50 мм) и дополнительного отопительного оборудования: более мощный автономный отопитель, система подогрева топлива и аккумулятора, обогреватели зеркали пр.</p>
                <p class="infoText">Готовые варианты наших автобусов базируются на шасси отечественных автомобилей ГАЗ (ГАЗ NEXT, ГАЗ САДКО) , КАМАЗ, Урал, ЗиЛ, а также на шасси импортных автомобилей Iveco, MAN, HINO, Mercedes. Превосходная проходимость этих автомобилей позволяет использовать их в любых условиях.</p>
                <div class="infoTextPadding">
                    <p class="paddingTitle">Приобрести вахтовый автобус</p>
                    <p class="paddingSubtitle">К каждому клиенту применяется индивидуальный подход. Предлагаемые комплектации могут быть изменены в соответствии с требованиями заказчика.</p>
                    <p class="paddingSubtitle">Для того, чтобы приобрести готовый вахтовый автобус позвоните в отдел продаж по телефону +7 (800) 200-79-13 (звонок по РФ бесплатный).</p>
                </div>
            </div>
        </div>
    </div> -->
    <div class="bgTomchiWhite">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</main>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

@endsection