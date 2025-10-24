@extends('admin.template.index')

@section('content')
    <h1 class="h3 mb-4">Yeni Sayfa Oluştur</h1>
    <form action="{{ route('admin.pages.store') }}" method="POST">
        @include('admin.pages._form')
    </form>
@endsection
