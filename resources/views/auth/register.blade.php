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
                    <span class="logo"></span> MrEnglish <small>Enter your informations...</small>
                </div>
                <div class="icon">
                    <i class="fa fa-sign-in"></i>
                </div>
            </div>
            <!-- end brand -->

            <div class="login-content">
                <form id="login-form col" action="{{ route('register') }}" method="post">
                    @csrf

                    <div class="form-group m-b-20 field-loginform-fullname required col-md-12">
                        <label for="fullname"> Fullname </label>
                        <input type="text" 
                                id="fullname" 
                                class="form-control input-lg inverse-mode no-border" 
                                name="fullname"  
                                value="{{old('fullname')}}" 
                                required 
                                autofocus 
                                placeholder="Fullname" 
                                aria-required="true">

                        @error('fullname')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div> 

                    <div class="form-group m-b-20 field-loginform-phone required col-md-12">
                        <label for="phone"> Phone number</label>
                        <input type="text" 
                                id="phone" 
                                class="form-control input-lg inverse-mode no-border" 
                                name="phone"  
                                value="{{old('phone')}}" 
                                required 
                                placeholder="phone" 
                                aria-required="true">

                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>   
                         
                    <div class="form-group m-b-20 field-loginform-password required col-md-12">
                        <label for="password"> Password </label>
                        <input type="password" 
                            id="password" 
                            class="form-control input-lg inverse-mode no-border" 
                            name="password"  
                            required 
                            autocomplete="current-password" 
                            placeholder="Password">

                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>   
                         
                    <div class="form-group m-b-20 field-loginform-password required col-md-12">
                        <label for="password"> Password Confirmation </label>
                        <input type="password" 
                            id="password_confirmation" 
                            class="form-control input-lg inverse-mode no-border" 
                            name="password_confirmation"  
                            required 
                            autocomplete="current-password" 
                            placeholder="Password Confirmation">

                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg" name="login-button">
                            Register
                        </button>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a class="link" href="{{ route('login') }}">
                            If already registred !
                        </a>
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