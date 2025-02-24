@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold">Xush kelibsiz, {{ auth()->user()->name }}!</h2>

    @if(auth()->user()->role->name === 'super_admin')
        <p>Siz Super Adminsiz va hamma narsani boshqarishingiz mumkin.</p>
    @elseif(auth()->user()->role->name === 'teacher')
        <p>Siz o‘qituvchisiz va o‘quvchilarga topshiriqlar qo‘shishingiz mumkin.</p>
    @elseif(auth()->user()->role->name === 'student')
        <p>Siz o‘quvchisiz va uyga vazifalarni bajarishingiz mumkin.</p>
    @endif
@endsection
