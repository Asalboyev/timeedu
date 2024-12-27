@extends('layouts.web')

@section('title', isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code])

@section('links')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="{{ asset('client/css/isothermic.css') }}">
@endsection

@section('content')

<main class="main">
    <div class="intro">

        @include('includes.header')

        <div class="intro__inner container">
            <p class="pageTitle">{{ isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code] }}</p>
            <div class="pageNavigation">
                <span class="pageNavigation__text"><a href="{{ route('index') }}">{{ translation('main.mainPage') }}</a></span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text blue">{{ isset($category->title[app()->getLocale()]) ? $category->title[app()->getLocale()] : $category->title[$main_lang->code] }}</span>
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
    <div class="miniTextSection">
        <div class="miniTextDiv">
            <p class="textDiv__text">
                {!! isset($category->desc[app()->getLocale()]) ? $category->desc[app()->getLocale()] : $category->desc[$main_lang->code] !!}
            </p>
        </div>
    </div>
    <div class="tabButtonSection">
        <div class="section__inner container tabButtonDiv">
            @foreach($categories as $item)
            <p class="button {{ $loop->first ? 'active' : '' }}">{{ isset($item->title[app()->getLocale()]) ? $item->title[app()->getLocale()] : $item->title[$main_lang->code] }} ({{ count($item->products) }})</p>
            @endforeach
        </div>
    </div>
    @foreach($categories as $item)
    <div class="tabSection {{ $loop->first ? 'active' : '' }}">
        <div class="isothermicsSection">
        @foreach($item->products as $product)
            <div class="isothermic">
                <div class="isothermic__inner container">
                    <span class="isothermicCountNumber">{{ $loop->iteration < 10 ? '0' : '' }}{{ $loop->iteration }}</span>
                    <div class="isothermicContent">
                        <div class="isothermicTexts">
                            <p class="isothermic__title">{{ isset($product->title[app()->getLocale()]) ? $product->title[app()->getLocale()] : $product->title[$main_lang->code] }}</p>
                            <div class="isothermic__subtitle">{!! isset($product->desc[app()->getLocale()]) ? $product->desc[app()->getLocale()] : $product->desc[$main_lang->code] !!}</div>
                        </div>
                        <div class="isothermicLinkDiv">
                            <p class="isothermic__link isothermic__link{{ $product->id }}">{{ translation('contacts.price') }}</p>
                        </div>
                    </div>
                    <div class="isothermicImageDiv">
                        <div class="swiper isothermicBig{{ $product->id }} mainImagesDiv">
                            <div class="swiper-wrapper">
                                @foreach($product->productImages as $img)
                                <div class="swiper-slide image">
                                    <img src="{{ $img->md_img }}" />
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper isothermicMini{{ $product->id }} miniImagesDiv">
                            <div class="swiper-wrapper">
                                @foreach($product->productImages as $img)
                                <div class="swiper-slide miniImage">
                                    <img src="{{ $img->md_img }}" />
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- <div class="centeredDiv">
            <p class="centerBtn">Посмотреть все</p>
        </div> -->
    </div>
    @endforeach
    <!-- <div class="tabSection">
        <div class="isothermicsSection">
            <div class="isothermic">
                <div class="isothermic__inner container">
                    <span class="isothermicCountNumber">01</span>
                    <div class="isothermicContent">
                        <div class="isothermicTexts">
                            <p class="isothermic__title">Изотермические фургоны</p>
                            <p class="isothermic__subtitle">Прочные и болговечные изотермические фургоны Promavto.uz готовы к жестким условиям эсловиям эксплуации на благо вашего бизнеса.</p>
                        </div>
                        <div class="isothermicLinkDiv">
                            <p class="isothermic__link">Узнать цену</p>
                        </div>
                    </div>
                    <div class="isothermicImageDiv">
                        <div class="swiper isothermicBig1 mainImagesDiv">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide image">
                                    <img src="./media/mini1.png" />
                                </div>
                                <div class="swiper-slide image">
                                    <img src="./media/mini2.png" />
                                </div>
                                <div class="swiper-slide image">
                                    <img src="./media/mini3.png" />
                                </div>
                                <div class="swiper-slide image">
                                    <img src="./media/mini1.png" />
                                </div>
                                <div class="swiper-slide image">
                                    <img src="./media/mini4.png" />
                                </div>
                            </div>
                        </div>
                        <div class="swiper isothermicMini1 miniImagesDiv">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini1.png" />
                                </div>
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini2.png" />
                                </div>
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini3.png" />
                                </div>
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini1.png" />
                                </div>
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini4.png" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="isothermic">
                <div class="isothermic__inner container">
                    <span class="isothermicCountNumber">01</span>
                    <div class="isothermicContent">
                        <div class="isothermicTexts">
                            <p class="isothermic__title">Изотермические фургоны</p>
                            <p class="isothermic__subtitle">Прочные и болговечные изотермические фургоны Promavto.uz готовы к жестким условиям эсловиям эксплуации на благо вашего бизнеса.</p>
                        </div>
                        <div class="isothermicLinkDiv">
                            <p class="isothermic__link">Узнать цену</p>
                        </div>
                    </div>
                    <div class="isothermicImageDiv">
                        <div class="swiper isothermicBig2 mainImagesDiv">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide image">
                                    <img src="./media/mini1.png" />
                                </div>
                                <div class="swiper-slide image">
                                    <img src="./media/mini2.png" />
                                </div>
                                <div class="swiper-slide image">
                                    <img src="./media/mini3.png" />
                                </div>
                                <div class="swiper-slide image">
                                    <img src="./media/mini1.png" />
                                </div>
                                <div class="swiper-slide image">
                                    <img src="./media/mini4.png" />
                                </div>
                            </div>
                        </div>
                        <div class="swiper isothermicMini2 miniImagesDiv">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini1.png" />
                                </div>
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini2.png" />
                                </div>
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini3.png" />
                                </div>
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini1.png" />
                                </div>
                                <div class="swiper-slide miniImage">
                                    <img src="./media/mini4.png" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="centeredDiv">
            <p class="centerBtn">Kor endi</p>
        </div>
    </div> -->
    <div class="bgTomchiWhite">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    @foreach($categories as $item)
    @foreach($item->products as $product)
    <div class="modal modal{{ $product->id }}">
        <div class="modal__inner modal__inner{{ $product->id }}">
            <div class="modalContent">
                <p class="modalText">{{ translation('contacts.formText') }}</p>
                <form class="modal__form" method="post" action="{{ route('application') }}">
                    @csrf
                    <input type="hidden" name="page" value="{{ $product->id }}">
                    <div class="parentDiv">
                        <label class="label" for="name">{{ translation('contacts.formTitle1') }}</label>
                        <input name="name" type="text" class="input" required placeholder="{{ translation('contacts.formTitle1') }}" required>
                    </div>
                    <div class="parentDiv">
                        <label class="label" for="organisation">{{ translation('contacts.formTitle2') }}</label>
                        <input name="company" type="text" class="input" placeholder="{{ translation('contacts.formTitle2') }}" required>
                    </div>
                    <div class="parentDiv">
                        <label class="label" for="email">{{ translation('contacts.formTitle3') }}</label>
                        <input name="email" type="email" class="input" placeholder="{{ translation('contacts.formTitle3') }}" required>
                    </div>
                    <button type="submit" class="buttonSubmit">{{ translation('contacts.send') }}</button>
                </form>
                <svg class="xBtn xBtn{{ $product->id }}" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4219 17.4229C17.5961 17.2483 17.803 17.1098 18.0308 17.0152C18.2586 16.9207 18.5028 16.8721 18.7494 16.8721C18.9961 16.8721 19.2403 16.9207 19.4681 17.0152C19.6959 17.1098 19.9028 17.2483 20.0769 17.4229L29.9994 27.3492L39.9219 17.4229C40.0963 17.2486 40.3032 17.1103 40.531 17.016C40.7588 16.9216 41.0029 16.8731 41.2494 16.8731C41.496 16.8731 41.7401 16.9216 41.9679 17.016C42.1957 17.1103 42.4026 17.2486 42.5769 17.4229C42.7513 17.5973 42.8896 17.8042 42.9839 18.032C43.0783 18.2598 43.1268 18.5039 43.1268 18.7504C43.1268 18.997 43.0783 19.2411 42.9839 19.4689C42.8896 19.6966 42.7513 19.9036 42.5769 20.0779L32.6507 30.0004L42.5769 39.9229C42.7513 40.0972 42.8896 40.3042 42.9839 40.532C43.0783 40.7598 43.1268 41.0039 43.1268 41.2504C43.1268 41.497 43.0783 41.7411 42.9839 41.9689C42.8896 42.1966 42.7513 42.4036 42.5769 42.5779C42.4026 42.7523 42.1957 42.8905 41.9679 42.9849C41.7401 43.0792 41.496 43.1278 41.2494 43.1278C41.0029 43.1278 40.7588 43.0792 40.531 42.9849C40.3032 42.8905 40.0963 42.7523 39.9219 42.5779L29.9994 32.6517L20.0769 42.5779C19.9026 42.7523 19.6957 42.8905 19.4679 42.9849C19.2401 43.0792 18.996 43.1278 18.7494 43.1278C18.5029 43.1278 18.2588 43.0792 18.031 42.9849C17.8032 42.8905 17.5963 42.7523 17.4219 42.5779C17.2476 42.4036 17.1093 42.1966 17.015 41.9689C16.9206 41.7411 16.8721 41.497 16.8721 41.2504C16.8721 41.0039 16.9206 40.7598 17.015 40.532C17.1093 40.3042 17.2476 40.0972 17.4219 39.9229L27.3482 30.0004L17.4219 20.0779C17.2473 19.9038 17.1088 19.6968 17.0143 19.469C16.9197 19.2413 16.8711 18.997 16.8711 18.7504C16.8711 18.5038 16.9197 18.2596 17.0143 18.0318C17.1088 17.804 17.2473 17.5971 17.4219 17.4229Z" fill="#2F2F2F" />
                </svg>  
            </div>
        </div>
    </div>
    @endforeach
    @endforeach
</main>

@endsection

@section('scripts')

<script src="{{ asset('client/js/isothermics.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
    @foreach($categories as $item)
    @foreach($item -> products as $product)
    var swiper{{$product -> id}} = new Swiper(".isothermicMini{{ $product->id }}", {
        spaceBetween: 20,
        slidesPerView: 5,
        freeMode: true,
        watchSlidesProgress: true,
    });
    new Swiper(".isothermicBig{{ $product->id }}", {
        slidesPerView: 1,
        spaceBetween: 20,
        thumbs: {
            swiper: swiper{{$product -> id}},
        },
    });

    // modal
    var btns{{ $product->id }} = document.querySelectorAll(".isothermic__link{{ $product->id }}")
    var modal{{ $product->id }} = document.querySelector(".modal{{ $product->id }}")
    var modalContent{{ $product->id }} = document.querySelector(".modal__inner{{ $product->id }}")
    var xBtn{{ $product->id }} = document.querySelector(".xBtn{{ $product->id }}")

    btns{{ $product->id }}.forEach(btn => {
        btn.addEventListener("click", () => {
            modal{{ $product->id }}.style.display = "flex"
        })
    })

    xBtn{{ $product->id }}.addEventListener("click", () => {
        modal{{ $product->id }}.style.display = "none"
    })

    if (window.innerWidth > 600) {
        window.addEventListener("click", (e) => {
            if (e.target == modal{{ $product->id }}) {
                modal{{ $product->id }}.style.display = "none"
            } else if (e.target == xBtn{{ $product->id }}) {
                modal{{ $product->id }}.style.display = "none"
            } else if (e.target == modalContent{{ $product->id }}) {
                modal{{ $product->id }}.style.display = "flex"
            }
        })
    }

    @endforeach
    @endforeach
</script>
@endsection