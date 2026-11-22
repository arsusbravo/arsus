@extends('layouts.shuttle', ['entry' => 'resources/js/shuttle/about.js', 'bodyClass' => 'subpage crew-page', 'noNav' => true, 'current' => 'about'])

@section('title', __('shuttle.meta.about_title'))
@section('description', __('shuttle.meta.about_description'))

@section('content')
    @php $back = locale_route('home') . '#about'; @endphp
    <main id="crew" class="monitor on" aria-label="{{ __('shuttle.about.screen_label') }}">
        <div class="frame">
            <div class="screen">
                <div class="topbar rv" style="--i:0">
                    <span>ARSUS-OS v26.1</span>
                    <span class="hide-sm">{{ __('shuttle.about.topbar') }}</span>
                    <a class="close" href="{{ $back }}" data-close>{{ __('shuttle.about.close') }}</a>
                </div>
                <div class="cbody">
                    <figure class="portrait rv" style="--i:1">
                        <img src="{{ asset('images/crew.jpg') }}" alt="{{ __('shuttle.about.photo_alt') }}" />
                        <span class="corner tl"></span><span class="corner tr"></span><span class="corner bl"></span><span class="corner br"></span>
                        <figcaption>&#9679; {{ __('shuttle.about.scan') }}</figcaption>
                    </figure>
                    <div class="data">
                        <p class="prompt" data-type>{{ __('shuttle.about.prompt') }}</p>
                        <h2 class="rv" style="--i:2">{{ __('shuttle.about.name') }} <small>{{ __('shuttle.about.role') }}</small></h2>
                        <p class="status rv" style="--i:3"><i></i> {{ __('shuttle.about.status') }}</p>
                        <dl class="facts rv" style="--i:4">
                            @foreach (['origin', 'base', 'role', 'specialty'] as $f)
                                <div><dt>{{ __('shuttle.about.' . $f . '_label') }}</dt><dd>{{ __('shuttle.about.' . ($f === 'role' ? 'role_value' : $f)) }}</dd></div>
                            @endforeach
                        </dl>
                        <p class="bio" data-type>{{ __('shuttle.about.bio') }}</p>
                        <ul class="traits">
                            @foreach (__('shuttle.about.traits') as $i => $trait)
                                <li class="rv" style="--i:{{ 6 + $i }}"><b>{{ $trait['title'] }}</b><span>{{ $trait['text'] }}</span></li>
                            @endforeach
                        </ul>
                        <div class="stats rv" style="--i:10">
                            @foreach (__('shuttle.about.stats') as $stat)
                                <div><label>{{ $stat['label'] }}</label><span class="bar" style="--v:{{ $stat['value'] }}%"></span></div>
                            @endforeach
                        </div>
                        <div class="actions rv" style="--i:11">
                            <a class="btn" href="{{ locale_route('contact') }}">{{ __('shuttle.open_comms') }} &rarr;</a>
                            <a class="btn ghost" href="{{ $back }}" data-close>{{ __('shuttle.about.return') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="crt crt-lines"></div>
            <div class="crt crt-bar"></div>
            <div class="crt crt-vignette"></div>
        </div>
    </main>
@endsection
