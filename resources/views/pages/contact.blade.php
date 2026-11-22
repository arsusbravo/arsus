@extends('layouts.shuttle', ['entry' => 'resources/js/shuttle/contact.js', 'bodyClass' => 'subpage comms-page', 'noNav' => true, 'current' => 'contact'])

@section('title', __('shuttle.meta.contact_title'))
@section('description', __('shuttle.meta.contact_description'))

@section('content')
    @php $back = locale_route('home') . '#contact'; @endphp
    <main id="crew" class="monitor on" aria-label="{{ __('shuttle.comms.screen_label') }}">
        <div class="frame">
            <div class="screen">
                <div class="topbar rv" style="--i:0">
                    <span>ARSUS-OS v26.1</span>
                    <span class="hide-sm">{{ __('shuttle.comms.topbar') }}</span>
                    <a class="close" href="{{ $back }}" data-close>{{ __('shuttle.comms.close') }}</a>
                </div>

                <div class="comms">
                    <div class="tx">
                        <p class="prompt" data-type>{{ __('shuttle.comms.prompt') }}</p>
                        <h1 class="rv" style="--i:1">{{ __('shuttle.comms.title') }} <small>{{ __('shuttle.comms.subtitle') }}</small></h1>
                        <p class="status rv" style="--i:2"><i></i> {{ __('shuttle.comms.status_open') }}</p>

                        <form id="comms-form" class="rv" style="--i:3" novalidate>
                            <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true" />

                            <div class="row">
                                <label class="field">
                                    <span>{{ __('shuttle.comms.name') }} <em>{{ __('shuttle.comms.name_hint') }}</em></span>
                                    <input type="text" name="name" autocomplete="name" placeholder="{{ __('shuttle.comms.name_placeholder') }}" required minlength="2" maxlength="100" />
                                    <small class="err" role="alert"></small>
                                </label>
                                <label class="field">
                                    <span>{{ __('shuttle.comms.email') }} <em>{{ __('shuttle.comms.email_hint') }}</em></span>
                                    <input type="email" name="email" autocomplete="email" placeholder="{{ __('shuttle.comms.email_placeholder') }}" required maxlength="255" />
                                    <small class="err" role="alert"></small>
                                </label>
                            </div>

                            <div class="row">
                                <label class="field">
                                    <span>{{ __('shuttle.comms.company') }} <em>{{ __('shuttle.comms.company_hint') }}</em></span>
                                    <input type="text" name="company" autocomplete="organization" placeholder="{{ __('shuttle.comms.company_placeholder') }}" maxlength="100" />
                                    <small class="err" role="alert"></small>
                                </label>
                                <label class="field">
                                    <span>{{ __('shuttle.comms.subject') }} <em>{{ __('shuttle.comms.subject_hint') }}</em></span>
                                    <select name="subject" required>
                                        <option value="">{{ __('shuttle.comms.subject_select') }}</option>
                                        @foreach (['general', 'quote', 'web', 'mobile', 'cloud', 'support', 'other'] as $subject)
                                            <option value="{{ $subject }}">{{ __('contact.subject_' . $subject) }}</option>
                                        @endforeach
                                    </select>
                                    <small class="err" role="alert"></small>
                                </label>
                            </div>

                            <label class="field">
                                <span>{{ __('shuttle.comms.message') }} <em>{{ __('shuttle.comms.message_hint') }}</em></span>
                                <textarea name="message" rows="5" placeholder="{{ __('shuttle.comms.message_placeholder') }}" required minlength="10" maxlength="5000"></textarea>
                                <small class="err" role="alert"></small>
                            </label>

                            <div class="form-actions">
                                <button class="btn" type="submit"><span class="btn-text">{{ __('shuttle.comms.transmit') }} &rarr;</span></button>
                                <a class="btn ghost" href="{{ $back }}" data-close>{{ __('shuttle.comms.return') }}</a>
                            </div>
                            <p class="form-error" id="form-error" role="alert" hidden></p>
                        </form>

                        <div class="sent" id="sent" hidden tabindex="-1">
                            <div class="sent-ring" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></div>
                            <h2>{{ __('shuttle.comms.sent_title') }}</h2>
                            <p id="sent-text">{{ __('shuttle.comms.sent_text') }}</p>
                            <button class="btn ghost" type="button" id="again">{{ __('shuttle.comms.again') }}</button>
                        </div>
                    </div>

                    <aside class="side rv" style="--i:4">
                        <div class="radar-box">
                            <canvas id="radar" width="360" height="360" aria-hidden="true"></canvas>
                            <p class="radar-cap" id="radar-cap">{{ __('shuttle.comms.radar_scan') }}</p>
                        </div>
                        <div class="signal" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><b>{{ __('shuttle.comms.signal') }}</b></div>
                        <dl class="facts one">
                            <div><dt>{{ __('shuttle.comms.direct_line') }}</dt><dd><a href="#" id="direct-email" data-user="info" data-domain="arsus.nl" rel="nofollow">{{ __('shuttle.comms.reveal_email') }}</a></dd></div>
                            <div><dt>{{ __('shuttle.comms.home_base') }}</dt><dd>{{ __('shuttle.comms.home_base_value') }}</dd></div>
                            <div><dt>{{ __('shuttle.comms.open_for') }}</dt><dd>{{ __('shuttle.comms.open_for_value') }}</dd></div>
                        </dl>
                    </aside>
                </div>
            </div>
            <div class="crt crt-lines"></div>
            <div class="crt crt-bar"></div>
            <div class="crt crt-vignette"></div>
        </div>
    </main>
@endsection
