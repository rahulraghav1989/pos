@extends('main')

@section('content')

        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">
                <div class="card card-pages shadow-none">
    
                    <div class="card-body">
                        <div class="text-center m-t-0 m-b-15">
                                <a href="#" class="logo logo-admin" style="font-size: 20px;
    color: #3d57e1;
    font-weight: 600;"><!-- <img src="{{ asset('posview') }}/assets/images/logo-dark.png" alt="" height="24"> -->Just1Click</a>
                        </div>
                        <h5 class="font-18 text-center">Sign in to continue to Just1Click.</h5>
                        @if(session()->has('error'))
                            <div class="card-body">                                 
                                <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                    {{ session()->get('error') }}
                                </div>
                            </div>
                        @endif
                        <form class="form-horizontal m-t-30" method="POST" action="{{ route('login') }}">
                            @csrf
    
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="username">{{ __('Username') }}</label>

                                    <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                <label for="password">{{ __('Password') }}</label>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block btn-lg waves-effect waves-light">
                                        {{ __('Login') }}
                                    </button>

                                    
                                </div>
                            </div>
                        </form>
                    </div>
    
                </div>
            </div>
@endsection
        <!-- END wrapper -->

        