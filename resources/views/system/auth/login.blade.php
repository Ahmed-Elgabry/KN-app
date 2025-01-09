<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&amp;l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5FS8GGP');
    </script>
    <!-- End Google Tag Manager -->
    <meta charset="utf-8" />
    <title>Nice one</title>
    <meta name="description" content="Nice one Login" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="canonical" href="/" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <base href="{{ asset('') }}">
    <link href="assets/plugins/global/plugins.bundle{{ direction() }}.css?v=1.0" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle{{ direction() }}.css?v=1.1" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <link rel="shortcut icon" href="{{ asset('/logo/favicon.ico') }}" />
    <script type="application/javascript">
        var $global_lang ='{{lang()}}';
    </script>

    <style>
        .more-step {
            padding: 5px 0;
            border-radius: 3px;
        }

        .hint {
            background-color: #3e97ff24;
            border-radius: 7px;
            padding: 5px;
            width: 300px;
            margin: 10px 5px;
        }

        .hint span {
            font-weight: 400;
        }

        .reDownload {
            display: none;
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Page loading(append to body)-->
<div class="page-loader flex-column bg-dark bg-opacity-25">
    <span class="spinner-border text-primary" role="status"></span>
    <span class="text-gray-800 fs-6 fw-semibold mt-5">{{ __('Loading...') }}</span>
</div>
<!--end::Page loading-->
<!--begin::Body-->

<body id="kt_body"
    class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-5 order-2 order-lg-1">
                <!--begin::Form-->
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px px-10">
                        {!! Form::open([
                            'id' => 'main-form',
                            'onsubmit' =>'FormSubmit("' . route('login') . '");return false;',
                            'method' =>  'POST',
                        ]) !!}
                        <!--begin::Form-->
                        <div id="form-alert-message"></div>
                        <div class="originalForm">
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                <h1 class="text-dark fw-bolder mb-3">{{ __('Sign In') }}</h1>
                                <!--end::Title-->
                                <!--begin::Subtitle-->
                                <div class="text-gray-500 fw-semibold fs-6"></div>
                                <!--end::Subtitle=-->
                            </div>
                            <!--begin::Input group=-->
                            <div class="fv-row mb-8">
                                <!--begin::Email-->
                                {!! Form::text('email', old('email') ? old('email') : '', [
                                    'class' => 'form-control bg-transparent',
                                    'placeholder' => __('E-Mail'),
                                ]) !!}
                                <div class="invalid-feedback" id="email-form-error"></div>
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <div class="fv-row mb-3">
                                <!--begin::Password-->
                                {!! Form::password('password', [
                                    'class' => 'form-control bg-transparent',
                                    'id' => 'password',
                                    'placeholder' => __('Password'),
                                ]) !!}
                                <div class="invalid-feedback" id="password-form-error"></div>
                                <!--end::Password-->
                            </div>
                            <!--end::Input group=-->
                        </div>
                        <!--begin::Submit button-->
                        <div class="d-grid login-form">
                            <!--begin::Action-->
                            <button type="submit"  class="show-qrCode btn btn-primary er fs-6 px-8 py-4"
                                data-bs-toggle="modal"
                                data-bs-target="#kt_modal_two_factor_authentication">
                                <span class="indicator-label">{{__('Sign In')}}</span>
                                <span class="indicator-progress">{{__('Please wait ...')}}
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Action-->
                        </div>
                        {!! Form::close() !!}

                        <!--begin::Modal - Two-factor authentication-->
                        <!--begin::Modal header-->
                        {!! Form::open(['id'=>'main-form-code','onsubmit' =>  'FormSubmit("'.route('system.code-post') .'","main-form-code");return false;','method' =>  'POST','url' => route('system.code-post') ]) !!}
                        <div id="form-alert-message"></div>

                        <div class="modal-dialog modal-dialog-centered mw-650px d-none">
                            <!--begin::Modal content-->
                            <div class="modal-content">
                                <!--begin::Modal body-->
                                <div class="modal-body pb-4">
                                    <!--begin::Apps-->
                                    <!--begin::Heading-->
                                    <div class="more-step mb-3 d-flex gap-2 align-items-center">
                                        <i class="fa fa-info-circle text-primary"></i>
                                        <span
                                            class="text-primary">{{ __('Just one more step to finish submition') }}</span>
                                    </div>
                                    <h3 class="text-dark fw-bold mb-7">{{ __('Two Factor Authenticate') }}</h3>
                                    <!--end::Heading-->
                                    <!--Add "second_visit" class here for second Time-->
                                    <div class="switching_visits ">
                                        <!--begin::Description-->
                                        <div class="text-gray-500 fw-semibold fs-6 mb-10">
                                            {{ __('Using an authenticator app like') }}
                                            <img src="{{asset('assets/media/misc/Google Authenticator New 2023.svg')}}"
                                                alt="google Authenticator" class="mw-25px">
                                            <span class="text-primary">Google Authenticator</span>
                                            ,
                                            {{ __('scan the QR code. It will generate a 6 digit code for you to enter below') }}.
                                            <!--begin::QR code image-->
                                            <div class="pt-5 text-center">
                                                <div class="d-flex justify-content-center gap-5 w-100 mb-6 mt-3">
                                                    <a href="https://apps.apple.com/us/app/google-authenticator/id388497605"
                                                        target="_blank"> <img src="{{asset('assets/media/misc/AppStore.png')}}"
                                                            alt="AppStore" class="mw-100px" /> </a>
                                                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en_US"
                                                        target="_blank"> <img src="{{asset('assets/media/misc/GooglePlay.png')}}"
                                                            alt="AppStore" class="mw-100px" /> </a>
                                                </div>
                                                <div style="height: 200px" >
{{--                                                <div class="h-100" id="load-qr"><i class="fa-solid fa-spinner fa-spin"  style="font-size: 50px;"></i></div>--}}
                                                <img src="{{asset('assets/media/misc/loading.gif')}}" alt="QR-code" loading="lazy" class="QR-code" />
                                                </div>
                                            </div>
                                            <!--end::QR code image-->
                                        </div>
                                        <!--end::Description-->
                                    </div>
                                    <!--begin::Form-->
                                    <div data-kt-element="apps-form" class="form" action="#">
                                        <!--begin::Input group-->
                                        <div class="fv-row">
                                            <input type="text"
                                                class=" form-control form-control-lg form-control-solid mb-7 code-email"
                                                placeholder="test@gmail.com"   disabled />
                                            <input type="tel"
                                               id="2fa_code" class="bg-white form-control form-control-lg form-control-solid shadow-sm code-showed-input" oninput="$('.code-hidden-input').val($(this).val())"
                                                placeholder="{{ __('Enter authentication code') }}" name="code" />
                                            <!--begin::Hint-->
                                            {{-- Add "hint" class here for the second time --}}

                                            {{-- <div class="hint reDownload">
                                                <div class="d-flex align-items-center gap-1 cursor-pointer">
                                                    <img src="assets/media/misc/Google Authenticator New 2023.svg" alt="google Authenticator" class="mw-20px mx-1">
                                                    <span>To Redownload Authenicator App  <span class="text-primary">Press here</span> </span>
                                                </div>
                                            </div> --}}
                                            <!--end::Hint-->
                                        </div>
                                        <div class="fv-row">
                                            <div class="mt-5">
                                                <div class="mt-5">
                                                    {{ Form::checkbox(
                                                        'trust_device',
                                                        '1',
                                                        false,
                                                        [
                                                            'id' => 'isPublicUpdate',
                                                            'class' => 'form-check-input',
                                                        ]
                                                    ) }}
                                                     {{ label((__('Trust this device'))) }}

                                                </div>
                                                <div class="invalid-feedback" id="file_name-form-error"></div>
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Form--> <!--end::Options-->
                                </div>
                                <!--end::Modal body-->
                            </div>
                            <!--end::Modal content-->
                        </div>
                        <!--end::Modal header-->
                        <!--end::Modal - Two-factor authentication-->
                        <div class="d-grid code-form d-none">

                            <!--end::Action-->
                            <button type="submit"  class="submit-qrCode second-submit btn btn-primary er fs-6 px-8 py-4"
                            data-bs-toggle="modal"
                            data-bs-target="#kt_modal_two_factor_authentication">
                            <span class="indicator-label">{{__('Sign In')}}</span>
                            <span class="indicator-progress">{{__('Please wait ...')}}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>


                    </button>
                        </div>
                        {!! Form::close() !!}


                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Form-->
            </div>
            <!--end::Body-->
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
                style="background-image: url({{ asset('assets/media/misc/auth-bg.png') }})">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                    <!--begin::Logo-->
                    <a href="{{ route('login') }}" class="mb-0 mb-lg-12">
                       <h1> KN </h1>
                    </a>
                    <!--end::Logo-->
                    <!--begin::Image-->
                    <!--end::Image-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
        <div class="alert-error-disable" style="display: none;">

            <div
                class="alert alert-dismissible bg-light-danger border border-danger border-3 d-flex flex-column flex-sm-row p-5 mb-10">
                <!--begin::Icon-->
                <i class="ki-duotone ki-information-3  fs-2hx text-danger me-4 mb-5 mb-sm-0"><span
                        class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <!--end::Icon-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <!--begin::Title-->
                    <h5 class="mb-1" id="div_title"></h5>
                    <!--end::Title-->
                    <!--begin::Content-->
                    <span id="div_message"></span>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Close-->
                <button type="button"
                    class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                    data-bs-dismiss="alert">
                    <i class="ki-duotone ki-cross fs-1 text-danger"><span class="path1"></span><span
                            class="path2"></span></i>
                </button>
                <!--end::Close-->
            </div>
        </div>
        <div class="alert-success-disable" style="display: none;">
            <!--begin::Alert-->
            <div
                class="alert alert-dismissible bg-light-primary border-3 border-primary d-flex flex-column flex-sm-row p-5 mb-10">
                <!--begin::Icon-->
                <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span
                        class="path2"></span><span class="path3"></span></i>
                <!--end::Icon-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <!--begin::Title-->
                    <h5 class="mb-1" id="div_title"></h5>
                    <!--end::Title-->
                    <!--begin::Content-->
                    <span id="div_message"></span>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Close-->
                <button type="button"
                    class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                    data-bs-dismiss="alert">
                    <i class="ki-duotone ki-cross fs-1 text-success"><span class="path1"></span><span
                            class="path2"></span></i>
                </button>
                <!--end::Close-->
            </div>
            <!--end::Alert-->

        </div>
    </div>
    <!--end::Root-->

    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
    <!--end::Global Theme Bundle-->
    <script src="{{asset('assets/js/custom.js')}}?v={{time()}}"></script>

</body>
<!--end::Body-->

</html>
