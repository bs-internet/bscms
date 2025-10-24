@extends('admin.template.index')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ $page->title }} sayfasını düzenle</h1>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-outline-primary">Yeni sayfa</a>
    </div>
    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
        @method('PUT')
        @include('admin.pages._form')
    </form>
@endsection
