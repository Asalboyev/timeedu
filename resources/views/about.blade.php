@extends('layouts.web')

@section('title', 'Главная')

@section('links')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="{{ asset('client/css/about.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/home.css') }}">
<script src="{{ asset('client/js/indexSwiper.js') }}" defer></script>
<script src="{{ asset('client/js/faq.js') }}" defer></script>

@endsection

@section('content')

<main class="main">
    <div class="intro">

        @include('includes.header')

        <div class="intro__inner container">
            <p class="pageTitle">{{ translation('about.pageTitle') }}</p>
            <div class="pageNavigation">
                <span class="pageNavigation__text"><a href="{{ route('index') }}">{{ translation('about.mainPage') }}</a></span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text blue">{{ translation('about.currentPage') }}</span>
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
    <div id="about">
        <div class="section__inner container">
            <div class="aboutVideoDiv">
                <div class="videoMask">
                    <div class="playButtonDiv">
                        <svg class="playVideoSvg" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6.05078L38 24.0008L10 41.9508V6.05078Z" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <video class="about__video" loop muted controls>
                    <source src="{{ asset('client/media/video.mp4') }}" type="video/mp4" />
                </video>
            </div>
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
                    <p class="aboutStats__name">{{ translation('about.statsText1') }}</p>
                </div>
                <div class="aboutStats">
                    <div class="aboutStats__top">
                        <p class="stats__number">42</p>
                        <span class="stats__line"></span>
                    </div>
                    <p class="aboutStats__name">{{ translation('about.statsText1') }}</p>
                </div>
            </div>
            <div class="aboutTextContainer">
                <p class="aboutTextTitle">{{ translation('about.aboutTextTitle') }}</p>
                <div class="aboutTextsDiv">
                    <p class="aboutText">{{ translation('about.text1') }}</p>
                    <p class="aboutText">{{ translation('about.text2') }}</p>
                    <p class="aboutText">{{ translation('about.text3') }}</p>
                    <p class="aboutText">{{ translation('about.text4') }}</p>
                    <p class="aboutText">{{ translation('about.text5') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div id="blackContainer">
        <div class="section__inner container">
            @if(isset($questions[0]))
            <div class="faqDiv">
                <p class="sectionTitle">{{ translation('about.faq') }}</p>
                <div class="faqContainer">
                    @foreach($questions as $question)
                    <div class="faqItem">
                        <div class="faqQuestionDiv">
                            <p class="question">{{ isset($question->question[app()->getLocale()]) ? $question->question[app()->getLocale()] : $question->question[$main_lang->code] }}</p>
                            <svg class="faqArrow" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M33.9999 18.3404C33.6252 17.9679 33.1183 17.7588 32.5899 17.7588C32.0615 17.7588 31.5546 17.9679 31.1799 18.3404L23.9999 25.4204L16.9199 18.3404C16.5452 17.9679 16.0383 17.7588 15.5099 17.7588C14.9815 17.7588 14.4746 17.9679 14.0999 18.3404C13.9124 18.5263 13.7637 18.7475 13.6621 18.9912C13.5606 19.2349 13.5083 19.4964 13.5083 19.7604C13.5083 20.0244 13.5606 20.2858 13.6621 20.5295C13.7637 20.7732 13.9124 20.9944 14.0999 21.1804L22.5799 29.6604C22.7658 29.8478 22.987 29.9966 23.2307 30.0982C23.4745 30.1997 23.7359 30.252 23.9999 30.252C24.2639 30.252 24.5253 30.1997 24.7691 30.0982C25.0128 29.9966 25.234 29.8478 25.4199 29.6604L33.9999 21.1804C34.1874 20.9944 34.3361 20.7732 34.4377 20.5295C34.5392 20.2858 34.5915 20.0244 34.5915 19.7604C34.5915 19.4964 34.5392 19.2349 34.4377 18.9912C34.3361 18.7475 34.1874 18.5263 33.9999 18.3404Z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="answerDiv">
                            <div class="answer">{!! isset($question->answer[app()->getLocale()]) ? $question->answer[app()->getLocale()] : $question->answer[$main_lang->code] !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @if(isset($members[0]))
            <div class="teamContainer">
                <p class="sectionTitle">{{ translation('about.team') }}</p>
                <div class="teamDiv">
                    @foreach($members as $item)
                    <div class="member">
                        <div class="memberInfo">
                            <div class="memberInfo__inner">
                                <p class="memberName">{{ isset($item->name[app()->getLocale()]) ? $item->name[app()->getLocale()] : $item->name[$main_lang->code] }}</p>
                                <p class="memberJob">{{ isset($item->position[app()->getLocale()]) ? $item->position[app()->getLocale()] : $item->position[$main_lang->code] }}</p>
                            </div>
                        </div>
                        <img src="{{ default_img($item->md_img) }}" alt="member">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
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
                    <a class="partnersLink">
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