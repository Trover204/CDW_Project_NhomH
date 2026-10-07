@auth
    <form action="{{ route('favorites.toggle', $court) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="fav-btn {{ $liked ? 'active' : '' }}"
                title="{{ $liked ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
            <i class="ti {{ $liked ? 'ti-heart-filled' : 'ti-heart' }}"></i>
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="fav-btn" title="Đăng nhập để yêu thích">
        <i class="ti ti-heart"></i>
    </a>
@endauth