@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label class="form-label">Tên người dùng *</label>
    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control">
    @error('name') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Email *</label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control">
    @error('email') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Mật khẩu {{ isset($user) ? '(Bỏ trống nếu không muốn đổi)' : '*' }}</label>
    <input type="password" name="password" class="form-control">
    @error('password') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Số điện thoại</label>
    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control">
    @error('phone') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Ngày sinh</label>
    <input type="date" name="birthday" value="{{ old('birthday', $user->birthday ?? '') }}" class="form-control">
    <small class="form-text text-muted">Người dùng phải từ 16 tuổi trở lên.</small>
    @error('birthday') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Vai trò *</label>
    <select name="role" class="form-control">
        @foreach (['customer' => 'Khách hàng', 'staff' => 'Nhân viên', 'admin' => 'Quản trị viên'] as $val => $text)
            <option value="{{ $val }}" @selected(old('role', $user->role ?? 'customer') === $val)>{{ $text }}</option>
        @endforeach
    </select>
    @error('role') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái hoạt động *</label>
    <select name="status" class="form-control">
        <option value="1" @selected(old('status', isset($user) ? $user->status : 1) == 1)>Hoạt động</option>
        <option value="0" @selected(old('status', isset($user) ? $user->status : 1) == 0)>Đã khóa</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu người dùng</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
</div>
