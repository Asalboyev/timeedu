@extends('layouts.web')

@section('title', 'Контакты')

@section('links')

<link rel="stylesheet" href="{{ asset('client/css/contact.css') }}">

@endsection

@section('content')

<main class="main">
    <div class="intro">

        @include('includes.header')

        <div class="intro__inner container">
            <p class="pageTitle">{{ translation('contacts.pageTitle') }}</p>
            <div class="pageNavigation">
                <span class="pageNavigation__text"><a href="{{ route('index') }}">{{ translation('main.mainPage') }}</a></span>
                <span class="pageNavigation__text">/</span>
                <span class="pageNavigation__text blue">{{ translation('contacts.currentPage') }}</span>
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
    <div class="mapSection">
        @if($site_info->instagram)
        <div class="instagramTextDiv">
            <a href="{{ $site_info->instagram ?? null }}" class="instagramText">instagram
                <svg class="instaLogo" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                </svg> {{ $site_info->instagram ?? null }}
            </a>
        </div>
        @endif
        <div class="mapDiv">
            {!! $site_info->map ?? null !!}
        </div>
        <div class="contactsContainer">
            <div class="contacts__inner container">
                <div class="contactsDiv">
                    <p class="contact">{{ isset($site_info->address[app()->getLocale()]) ? $site_info->address[app()->getLocale()] : null }}</p>
                    <p class="contactInfo">promavto.uz</p>
                </div>
                <div class="otherDiv">
                    <div class="contactsDiv">
                        <div class="numbersDiv">
                            @php
                            if(isset($site_info->phone_number)) {
                                $phones = explode('|', $site_info->phone_number);
                            }
                            @endphp
                            @if(isset($phones))
                            @foreach($phones as $phone)
                            <a class="contact" href="tel:{{ $phone }}" aria-label="phoneNumber">{{ $phone }}</a>
                            @endforeach
                            @endif
                        </div>
                        <p class="contactInfo">{{ isset($site_info->work_time[app()->getLocale()]) ? $site_info->work_time[app()->getLocale()] : null }}</p>
                    </div>
                    <div class="contactsDiv">
                        <a class="contact" href="mailto:{{ $site_info->email ?? null }}" aria-label="email">{{ $site_info->email ?? null }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="miniContainer">
        <p class="formText">{{ translation('contacts.formText') }}</p>
        <form action="{{ route('application') }}" class="form" method="post">
            @csrf
            <input type="hidden" name="page" value="0">
            <div class="parentDiv">
                <label class="label" for="name">{{ translation('contacts.formTitle1') }}</label>
                <input id="name" required name="name" type="text" class="input" placeholder="{{ translation('contacts.name') }}" required>
            </div>
            <div class="parentDiv">
                <label class="label" for="organisation">{{ translation('contacts.formTitle2') }}</label>
                <input id="organisation" name="company" type="text" class="input" placeholder="{{ translation('contacts.formTitle2') }}" required>
            </div>
            <div class="parentDiv">
                <label class="label" for="email">{{ translation('contacts.formTitle3') }}</label>
                <input id="email" required name="email" type="email" class="input" placeholder="{{ translation('contacts.email') }}" required>
            </div>
            <button type="submit" class="buttonSubmit">{{ translation('contacts.send') }}</button>
        </form>
    </div>
    <div class="bgTomchiBlack">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</main>

@endsection

@section('scripts')



@endsection