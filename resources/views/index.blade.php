@extends('layouts.web')

@section('title', 'Главная')

@section('links')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="{{ asset('client/css/home.css') }}">
<script src="{{ asset('client/js/indexSwiper.js') }}" defer></script>
<script src="{{ asset('client/js/index.js') }}" defer></script>
<meta name="description" content="Оказываем полный спектр услуг по поставке и производству спецавтомобилей различного назначения в Ташкенте">

@endsection

@section('content')

<div class="hero">

    @include('includes.header')

    <div class="heroContainer">
        <div class="heroContent container">
            @foreach($in_main_categories as $item)
            <div class="heroCard">
                <a href="{{ route('subcategories', ['id' => $item->id]) }}" class="heroCard__content">
                    <div class="heroCard__texts">
                        <p class="heroCard__title">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</p>
                        <div class="heroCard__subtitle">{!! isset($item->desc[app()->getLocale()]) ? $item->desc[app()->getLocale()] : $item->desc[$main_lang->code] !!}</div>
                    </div>
                    <div class="heroCardBtn">
                        <span>{{ translation('main.readMore') }}</span>
                        <svg class="heroCardArrow" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 22.5C17.5228 22.5 22 18.0228 22 12.5C22 6.97715 17.5228 2.5 12 2.5C6.47715 2.5 2 6.97715 2 12.5C2 18.0228 6.47715 22.5 12 22.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 16.5L16 12.5L12 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8 12.5H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </a>
                @if($item->md_img)
                <img src="{{ default_img($item->md_img) }}" alt="{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}" style="object-fit: contain">
                @endif
            </div>
            @endforeach
        </div>
    </div>
    <div class="bgTomchiBlack">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <video preload="yes" class="bigVideo" loop muted autoplay playsinline>
        <source src="{{ asset('client/media/fon-video2.mp4') }}" type="video/mp4" />
    </video>
    <video preload="yes" class="miniVideo" playsinline loop muted autoplay="autoplay" src="{{ asset('client/media/videoMini.mp4') }}" type="video/mp4" />
    <!-- <img class="hero__video" src="{{ asset('client/media/heroBg.png') }}" alt="background-video" /> -->
</div>
<main class="main">
    <div id="about" class="section">
        <div class="section__inner container">
            <p class="sectionTitle">{{ translation('main.about') }}</p>
            <p class="sectionSubtitle">{{ translation('about.text1') }}</p>
            <div class="aboutStatsDiv">
                <div class="aboutStats">
                    <div class="aboutStats__top">
                        <p class="stats__number">26</p>
                        <span class="stats__line"></span>
                    </div>
                    <div class="aboutStats__name">{{ translation('about.statsText1') }}</div>
                </div>
                <div class="aboutStats">
                    <div class="aboutStats__top">
                        <p class="stats__number">84</p>
                        <span class="stats__line"></span>
                    </div>
                    <p class="aboutStats__name">{{ translation('about.statsText2') }}</p>
                </div>
                <div class="aboutStats">
                    <div class="aboutStats__top">
                        <p class="stats__number">04</p>
                        <span class="stats__line"></span>
                    </div>
                    <p class="aboutStats__name">{{ translation('about.statsText2') }}</p>
                </div>
                <div class="aboutStats">
                    <div class="aboutStats__top">
                        <p class="stats__number">42</p>
                        <span class="stats__line"></span>
                    </div>
                    <p class="aboutStats__name">{{ translation('about.statsText2') }}</p>
                </div>
            </div>
            <div class="aboutPlayVideoDiv">
                <p class="playVideo__text">{{ translation('main.also') }}, <span>{{ translation('main.watchVideo') }}</span></p>
                <svg class="playSvg" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="19" stroke="#1D539C" stroke-width="2" />
                    <path d="M26.4502 17.4996L18.4002 12.8829C17.9646 12.6314 17.4702 12.4996 16.9672 12.501C16.4642 12.5024 15.9705 12.6369 15.5363 12.8908C15.1021 13.1447 14.7428 13.509 14.495 13.9467C14.2471 14.3844 14.1195 14.8799 14.1252 15.3829V24.6496C14.1252 25.4054 14.4254 26.1303 14.9599 26.6648C15.4944 27.1993 16.2193 27.4996 16.9752 27.4996C17.4755 27.4987 17.9669 27.3665 18.4002 27.1162L26.4502 22.4996C26.8827 22.2492 27.2418 21.8895 27.4915 21.4566C27.7412 21.0237 27.8726 20.5327 27.8726 20.0329C27.8726 19.5331 27.7412 19.0422 27.4915 18.6092C27.2418 18.1763 26.8827 17.8166 26.4502 17.5662V17.4996ZM25.6168 20.9912L17.5668 25.6746C17.3864 25.7768 17.1826 25.8306 16.9752 25.8306C16.7678 25.8306 16.5639 25.7768 16.3835 25.6746C16.2036 25.5707 16.0542 25.4213 15.9503 25.2414C15.8465 25.0614 15.7918 24.8573 15.7918 24.6496V15.3496C15.7918 15.1418 15.8465 14.9377 15.9503 14.7578C16.0542 14.5779 16.2036 14.4285 16.3835 14.3246C16.5647 14.2239 16.7679 14.1695 16.9752 14.1662C17.1823 14.1705 17.3853 14.2248 17.5668 14.3246L25.6168 18.9746C25.7968 19.0784 25.9463 19.2278 26.0502 19.4077C26.1542 19.5877 26.2089 19.7918 26.2089 19.9996C26.2089 20.2074 26.1542 20.4115 26.0502 20.5914C25.9463 20.7713 25.7968 20.9207 25.6168 21.0246V20.9912Z" fill="#1D539C" />
                </svg>
            </div>
        </div>
        <div class="backTextDiv">
            <p class="backText">{{ translation('main.about') }}</p>
        </div>
        <div class="bgTomchiWhite">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="videoModal">
        <div class="videoModal__content">
            <video class="video" loop muted controls>
                <source src="{{ asset('client/media/video.mp4') }}" type="video/mp4" />
            </video>
        </div>
    </div>
    @if($developments && isset($developments->products[0]))
    <div id="developments" class="section">
        <div class="section__inner container">
            <p class="sectionTitle">Разработки</p>
            <p class="sectionSubtitle">ООО «USASIA JOINT DISTRIBUTIONS» основана 04 апреля 2017 г. специализирующая в модернизации автомобильной техники, а также изготовлении и установке грузовых автофургонов </p>
        </div>
        <div class="swiper developmentsSwiper">
            <div class="swiper-wrapper developmentSwiperWrapper">
                @foreach($developments->products as $item)
                <div class="swiper-slide developmentsSlide">
                    <a class="developmentsLink" href="#">
                        <div class="swiperContent">
                            <div class="developmentsSwiper__content">
                                <div class="timeDiv">
                                    <svg class="calendar" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.8332 3.33366H14.1665V2.50033C14.1665 2.27931 14.0787 2.06735 13.9224 1.91107C13.7661 1.75479 13.5542 1.66699 13.3332 1.66699C13.1122 1.66699 12.9002 1.75479 12.7439 1.91107C12.5876 2.06735 12.4998 2.27931 12.4998 2.50033V3.33366H7.49984V2.50033C7.49984 2.27931 7.41204 2.06735 7.25576 1.91107C7.09948 1.75479 6.88752 1.66699 6.6665 1.66699C6.44549 1.66699 6.23353 1.75479 6.07725 1.91107C5.92097 2.06735 5.83317 2.27931 5.83317 2.50033V3.33366H4.1665C3.50346 3.33366 2.86758 3.59705 2.39874 4.06589C1.9299 4.53473 1.6665 5.17062 1.6665 5.83366V15.8337C1.6665 16.4967 1.9299 17.1326 2.39874 17.6014C2.86758 18.0703 3.50346 18.3337 4.1665 18.3337H15.8332C16.4962 18.3337 17.1321 18.0703 17.6009 17.6014C18.0698 17.1326 18.3332 16.4967 18.3332 15.8337V5.83366C18.3332 5.17062 18.0698 4.53473 17.6009 4.06589C17.1321 3.59705 16.4962 3.33366 15.8332 3.33366ZM16.6665 15.8337C16.6665 16.0547 16.5787 16.2666 16.4224 16.4229C16.2661 16.5792 16.0542 16.667 15.8332 16.667H4.1665C3.94549 16.667 3.73353 16.5792 3.57725 16.4229C3.42097 16.2666 3.33317 16.0547 3.33317 15.8337V10.0003H16.6665V15.8337ZM16.6665 8.33366H3.33317V5.83366C3.33317 5.61265 3.42097 5.40068 3.57725 5.2444C3.73353 5.08812 3.94549 5.00033 4.1665 5.00033H5.83317V5.83366C5.83317 6.05467 5.92097 6.26663 6.07725 6.42291C6.23353 6.5792 6.44549 6.66699 6.6665 6.66699C6.88752 6.66699 7.09948 6.5792 7.25576 6.42291C7.41204 6.26663 7.49984 6.05467 7.49984 5.83366V5.00033H12.4998V5.83366C12.4998 6.05467 12.5876 6.26663 12.7439 6.42291C12.9002 6.5792 13.1122 6.66699 13.3332 6.66699C13.5542 6.66699 13.7661 6.5792 13.9224 6.42291C14.0787 6.26663 14.1665 6.05467 14.1665 5.83366V5.00033H15.8332C16.0542 5.00033 16.2661 5.08812 16.4224 5.2444C16.5787 5.40068 16.6665 5.61265 16.6665 5.83366V8.33366Z" fill="currentColor" />
                                    </svg>
                                    <p class="time">{{ date('d.m.Y', strtotime($item->created_at)) }}</p>
                                </div>
                                <p class="development__title">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</p>
                                <span class="development__subtitle">{!! isset($item->desc[app()->getLocale()]) ? $item->desc[app()->getLocale()] : $item->desc[$main_lang->code] !!}</span>
                            </div>
                        </div>
                        <img src="{{ default_img($item->md_img) }}" alt="{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <div class="centeredDiv">
            <div class="centerBtnDiv"><a class="centerBtn" href="#">Посмотреть все</a></div>
        </div>
        <div class="backTextDiv">
            <p class="backText">Разработки</p>
        </div>
        <div class="bgTomchiWhite">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    @endif
    <div id="catalogue" class="section">
        <div class="section__inner container">
            <p class="sectionTitle">{{ translation('main.catalogue') }}</p>
            <div class="tabButtonDiv">
                @foreach($in_main_categories as $item)
                <span class="tab__btn {{ $loop->first ? 'active' : '' }}">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</span>
                @endforeach
            </div>
            @foreach($in_main_categories as $item)

            @php
            $children = $item->children()->latest()->get();
            $four_categories = $item->children()->latest()->take(3)->get();
            @endphp

            <div class="catalogueToggle {{ $loop->first ? 'active' : '' }}">
                <div class="catalogueContainer">
                    @foreach($four_categories as $product)
                    <div class="product">
                        <a class="productLink" href="{{ route('products', ['id' => $product->id]) }}">
                            <div class="product__content">
                                <p class="product__title">{{ isset($product->title[app()->getLocale()]) ? $product->title[app()->getLocale()] : $product->title[$main_lang->code] }}</p>
                                <div class="product__subtitle">{!! isset($product->desc[app()->getLocale()]) ? $product->desc[app()->getLocale()] : $product->desc[$main_lang->code] !!}</div>
                            </div>
                            <div class="product__image"><img src="{{ default_img($product->md_img) }}" alt="zapcast"></div>
                        </a>
                    </div>
                    @endforeach
                </div>
                @if(count($children) > 4)
                <div class="centeredDiv">
                    <div class="centerBtnDiv"><a class="centerBtn" href="{{ route('products', ['id' => $product->id]) }}">Посмотреть все</a></div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="backTextDiv">
            <p class="backText">{{ translation('main.catalogue') }}</p>
        </div>
        <div class="bgTomchiWhite">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div id="isothermic">
        <div class="section__inner container">
            <div class="isothermic__content">
                <div class="isothermic__texts">
                    <p class="isothermic__title">{{ translation('main.isothermal') }}</p>
                    <p class="isothermic__subtitle">{{ translation('main.isothermalText') }}</p>
                </div>
                <div class="isothermicBtn"><a class="isothermic__btn" href="https://plaza.choopon.uz/subcategories/11">{{ translation('main.readMore') }}</a></div>
            </div>
            <div class="isothermic__image"><img src="{{ asset('client/media/truck.png') }}" alt="truck"></div>
        </div>
    </div>
    <div id="services" class="section">
        <div class="section__inner container">
            <p class="sectionTitle">{{ translation('main.services') }}</p>
            <div class="servicesGridContainer">
                <div class="servicesList">
                    @foreach($services as $item)
                    <div class="service">
                        <div class="serviceNameDiv">
                            <span class="service__name">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }}</span>
                            <svg class="serviceArrow" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M33.9999 18.3404C33.6252 17.9679 33.1183 17.7588 32.5899 17.7588C32.0615 17.7588 31.5546 17.9679 31.1799 18.3404L23.9999 25.4204L16.9199 18.3404C16.5452 17.9679 16.0383 17.7588 15.5099 17.7588C14.9815 17.7588 14.4746 17.9679 14.0999 18.3404C13.9124 18.5263 13.7637 18.7475 13.6621 18.9912C13.5606 19.2349 13.5083 19.4964 13.5083 19.7604C13.5083 20.0244 13.5606 20.2858 13.6621 20.5295C13.7637 20.7732 13.9124 20.9944 14.0999 21.1804L22.5799 29.6604C22.7658 29.8478 22.987 29.9966 23.2307 30.0982C23.4745 30.1997 23.7359 30.252 23.9999 30.252C24.2639 30.252 24.5253 30.1997 24.7691 30.0982C25.0128 29.9966 25.234 29.8478 25.4199 29.6604L33.9999 21.1804C34.1874 20.9944 34.3361 20.7732 34.4377 20.5295C34.5392 20.2858 34.5915 20.0244 34.5915 19.7604C34.5915 19.4964 34.5392 19.2349 34.4377 18.9912C34.3361 18.7475 34.1874 18.5263 33.9999 18.3404Z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="serviceTextDiv">
                            <p class="service__text">{!! isset($item->subtitle[app()->getLocale()]) ? $item->subtitle[app()->getLocale()] : $item->subtitle[$main_lang->code] !!}</p>
                            <div class="heroCardBtn">
                                <span><a href="{{ route('services') }}#service{{ $item->id }}">{{ translation('main.readMore') }}</a></span>
                                <svg class="heroCardArrow" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22.5C17.5228 22.5 22 18.0228 22 12.5C22 6.97715 17.5228 2.5 12 2.5C6.47715 2.5 2 6.97715 2 12.5C2 18.0228 6.47715 22.5 12 22.5Z" stroke="#1D539C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 16.5L16 12.5L12 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8 12.5H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="serviceImageDiv"><img loading="lazy" src="{{ asset('client/media/serviceKamaz.png') }}" alt="serviceKamaz"></div>
            </div>
        </div>
        <div class="backTextDiv">
            <p class="backText">{{ translation('main.services') }}</p>
        </div>
        <div class="bgTomchiBlack">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    @if(isset($partners[0]))
    <div id="partners" class="section">
        <div class="section__inner container">
            <p class="sectionTitle">{{ translation('main.partners') }}</p>
        </div>
        <div class="swiper parntersSwiper">
            <div class="swiper-wrapper parntersSwiperWrapper">
                @foreach($partners as $item)
                <div class="swiper-slide partners">
                    <a class="partnersLink" href="#">
                        <div class="partners__image">
                            <img src="{{ default_img($item->md_img) }}" alt="partner">
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="swiperBtnDiv">
                <div class="container relativeBtn">
                    <div class="prevBtn">
                        <svg class="swiperChevron" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25 30L15 20L25 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="nextBtn">
                        <svg class="swiperChevron" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25 30L15 20L25 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="backTextDiv">
            <p class="backText">{{ translation('main.partners') }}</p>
        </div>
    </div>
    @endif
</main>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

@endsection