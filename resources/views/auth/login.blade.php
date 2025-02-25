@extends('auth.layout')

@section('title', "Login")


@section('content')
<!-- begin #page-container -->
<div id="page-container" class="fade">
    <!-- begin login -->
    <div class="login login-v2">
        <!-- begin brand -->
        <div class="login-header">
            <div class="brand">
                <span class="logo"></span> Progress Solution <small>Введите логин и пароль...</small>
            </div>
            <div class="icon">
                <i class="fa fa-sign-in"></i>
            </div>
        </div>
        <!-- end brand -->

        <div class="login-content">
            <form id="login-form col" action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group m-b-20 field-loginform-phone required col-md-12" style="padding:0">

                    <input type="text" id="username" class="form-control input-lg inverse-mode no-border" name="username" value="{{old('username')}}" required autofocus placeholder="Имя пользователя" aria-required="true">

                    @error('username')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group m-b-20 field-loginform-password required col-md-12" style="padding:0">

                    <input type="password" style="position: relative;" id="inputElementIdOne" class="form-control input-lg inverse-mode no-border" name="password" required autocomplete="current-password" placeholder="Пароль">
                    <i style="position: absolute; right: 15px; top: 15px; font-size: 16px; cursor: pointer;" id="eye1" onclick="eyeFunction()" class="fa fa-eye"></i>
                    <i style="position: absolute; right: 15px; top: 15px; font-size: 16px; cursor: pointer;" id="eye1None" onclick="eyeFunction()" class="fa fa-eye-slash"></i>
                    @error('username')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="login-buttons">
                    <button type="submit" class="btn btn-success btn-block btn-lg" name="login-button">Авторизоваться</button>
                </div>


            </form>
        </div>
    </div>
    <!-- end login -->
    <ul class="login-bg-list clearfix">
    </ul>
</div>
<!-- end page container -->
@endsection
