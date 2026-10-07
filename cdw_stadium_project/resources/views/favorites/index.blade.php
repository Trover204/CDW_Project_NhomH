@extends('layouts.app')

@section('title', 'Sân yêu thích')

@section('content')
    <h2>Sân yêu thích của tôi</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($courts as $court)
        <div class="court-card">
            <h4>{{ $court->name }}</h4>
            <p>{{ $court->sportType->name ?? '' }} · {{ $court->facility->name ?? '' }}</p>
            <p>{{ $court->facility->address ?? '' }}</p>

            @include('partials.favorite-button', ['court' => $court, 'liked' => true])
        </div>
    @empty
        <p>Bạn chưa có sân yêu thích nào. <a href="{{ route('home') }}">Xem danh sách sân</a></p>
    @endforelse

    {{ $courts->links() }}
@endsection