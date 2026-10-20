@extends('web.layouts.app')

@section('title', 'Xác nhận đặt sân - Sân Nhóm H')

@section('content')


<div class="wrap" style="padding: 40px 0; min-height: 80vh;">
    <div style="font-family:Arial,Helvetica,sans-serif;border:0.5px solid #E1E8E3;border-radius:12px;overflow:hidden;background:#FFFFFF;">
        <div style="padding:18px 20px;">
            <div style="display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:20px;">
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:22px;height:22px;border-radius:50%;background:#178A4C;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;font-weight:700;">1</div><span style="font-size:11.5px;color:#16241C;font-weight:600;">Xác nhận</span>
                </div>
                <div style="width:50px;height:1.5px;background:#E1E8E3;margin:0 10px;"></div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:22px;height:22px;border-radius:50%;background:#E1E8E3;color:#8A968F;font-size:11px;display:flex;align-items:center;justify-content:center;font-weight:700;">2</div><span style="font-size:11.5px;color:#8A968F;">Thanh toán</span>
                </div>
                <div style="width:50px;height:1.5px;background:#E1E8E3;margin:0 10px;"></div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:22px;height:22px;border-radius:50%;background:#E1E8E3;color:#8A968F;font-size:11px;display:flex;align-items:center;justify-content:center;font-weight:700;">3</div><span style="font-size:11.5px;color:#8A968F;">Hoàn tất</span>
                </div>
            </div>

            @if ($errors->any())
                <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li style="font-size: 13px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $reqDate = request('date');
                $reqTime = request('time');
                $endDate = null;
                if ($reqTime) {
                    try {
                        $endDate = \Carbon\Carbon::parse($reqTime)->addHour()->format('H:i');
                    } catch (\Exception $e) {
                        $endDate = '19:00';
                    }
                }
            @endphp

            <form action="{{ route('bookings.store', $court->id) }}" method="POST">
                @csrf
                <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:20px;">
                    <div>
                        <div style="font-size:13.5px;font-weight:700;color:#16241C;margin-bottom:14px;">Thông tin người đặt</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                            <div>
                                <div style="font-size:11px;color:#3A453E;margin-bottom:4px;">Họ và tên *</div>
                                <input type="text" name="customer_name" value="{{ old('customer_name', Auth::user()->name) }}" required style="width: 100%; height:34px;border-radius:8px;border:0.5px solid #C7D3CA;display:flex;align-items:center;padding:0 10px;font-size:12px;color:#16241C;">
                            </div>
                            <div>
                                <div style="font-size:11px;color:#3A453E;margin-bottom:4px;">Số điện thoại *</div>
                                <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required style="width: 100%; height:34px;border-radius:8px;border:0.5px solid #C7D3CA;display:flex;align-items:center;padding:0 10px;font-size:12px;color:#16241C;">
                            </div>
                        </div>
                        <div style="margin-bottom:12px;">
                            <div style="font-size:11px;color:#3A453E;margin-bottom:4px;">Email nhận xác nhận *</div>
                            <input type="email" name="customer_email" value="{{ old('customer_email', Auth::user()->email) }}" required style="width: 100%; height:34px;border-radius:8px;border:0.5px solid #C7D3CA;display:flex;align-items:center;padding:0 10px;font-size:12px;color:#16241C;">
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px;">
                            <div>
                                <div style="font-size:11px;color:#3A453E;margin-bottom:4px;">Ngày đặt *</div>
                                <input type="date" name="booking_date" value="{{ old('booking_date', $reqDate ?? \Carbon\Carbon::now()->format('Y-m-d')) }}" required style="width: 100%; height:34px;border-radius:8px;border:0.5px solid #C7D3CA;display:flex;align-items:center;padding:0 10px;font-size:12px;color:#16241C;">
                            </div>
                            <div>
                                <div style="font-size:11px;color:#3A453E;margin-bottom:4px;">Giờ bắt đầu *</div>
                                <input type="time" name="start_time" value="{{ old('start_time', $reqTime ?? '18:00') }}" required style="width: 100%; height:34px;border-radius:8px;border:0.5px solid #C7D3CA;display:flex;align-items:center;padding:0 10px;font-size:12px;color:#16241C;">
                            </div>
                            <div>
                                <div style="font-size:11px;color:#3A453E;margin-bottom:4px;">Giờ kết thúc *</div>
                                <input type="time" name="end_time" value="{{ old('end_time', $endDate ?? '19:00') }}" required style="width: 100%; height:34px;border-radius:8px;border:0.5px solid #C7D3CA;display:flex;align-items:center;padding:0 10px;font-size:12px;color:#16241C;">
                            </div>
                        </div>

                        <div style="margin-bottom:16px;">
                            <div style="font-size:11px;color:#3A453E;margin-bottom:4px;">Ghi chú cho chủ sân</div>
                            <textarea name="note" style="width: 100%; height:60px;border-radius:8px;border:0.5px solid #C7D3CA;padding:8px 10px;font-size:11.5px;color:#16241C;font-family: inherit;">{{ old('note') }}</textarea>
                        </div>

                        <div style="display:flex;align-items:flex-start;gap:8px;font-size:11.5px;color:#3A453E;">
                            <input type="checkbox" required style="display:inline-block;width:14px;height:14px;border-radius:3px;flex-shrink:0;margin-top:1px;">
                            Tôi đồng ý với <span style="color:#178A4C;">điều khoản đặt sân</span> và chính sách hủy đơn
                        </div>
                    </div>

                    <div style="background:#F7FAF8;border:0.5px solid #E1E8E3;border-radius:12px;padding:16px;align-self:start;">
                        <div style="font-size:12.5px;font-weight:700;color:#16241C;margin-bottom:12px;">Chi tiết đơn đặt</div>
                        <div style="display:flex;gap:10px;align-items:center;margin-bottom:12px;padding-bottom:12px;border-bottom:0.5px solid #E1E8E3;">
                            <div style="width:40px;height:40px;border-radius:8px;background:#E6F0FA;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="ti ti-ball-badminton" style="font-size:18px;color:#2563EB;" aria-hidden="true"></i></div>
                            <div>
                                <div style="font-size:12px;font-weight:600;color:#16241C;">{{ $court->name }}</div>
                                <div style="font-size:10.5px;color:#6B7A70;">{{ $court->facility->address ?? 'Không rõ' }}</div>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:11.5px;color:#6B7A70;margin-bottom:7px;"><span>Đơn giá tạm tính</span><span style="color:#16241C;">120.000đ / giờ</span></div>

                        <div style="display:flex;justify-content:space-between;font-size:13.5px;font-weight:700;color:#16241C;margin-bottom:14px;padding-top:10px;border-top:0.5px solid #E1E8E3;"><span>Thanh toán sẽ tự động cập nhật</span></div>
                        <button type="submit" style="width: 100%; border: none; cursor: pointer; height:36px;border-radius:8px;background:#F97316;display:flex;align-items:center;justify-content:center;font-size:12.5px;color:#fff;font-weight:600;">Tiếp tục thanh toán</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
