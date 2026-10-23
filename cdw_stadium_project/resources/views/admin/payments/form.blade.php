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

@php
    $selectedType = old('payable_type', $payment->payable_type ?? \App\Models\Payment::TYPE_BOOKING);
    $selectedPayableId = (int) old('payable_id', $payment->payable_id ?? 0);
@endphp

<div class="form-group">
    <label class="form-label">Nguồn phát sinh thanh toán *</label>
    <select name="payable_type" id="payable-type-select" class="form-control" required>
        @foreach (\App\Models\Payment::TYPES as $typeVal => $typeText)
            <option value="{{ $typeVal }}" @selected($selectedType === $typeVal)>{{ $typeText }}</option>
        @endforeach
    </select>
    @error('payable_type') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Đơn nguồn (Booking / Sale / Subscription) *</label>
    <select name="payable_id" id="payable-id-select" class="form-control" required>
        <option value="">-- Chọn đơn nguồn --</option>
        @foreach ($bookings as $b)
            <option value="{{ $b->id }}"
                    data-type="booking"
                    data-user="{{ $b->user_id }}"
                    data-amount="{{ (int) $b->total_price }}"
                    @selected($selectedType === 'booking' && $selectedPayableId === $b->id)>
                [Đặt sân] #BK-{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }} — {{ $b->customer_name }} ({{ $b->court->name ?? 'Sân' }}, {{ number_format($b->total_price, 0, ',', '.') }}đ)
            </option>
        @endforeach
        @foreach ($sales as $s)
            <option value="{{ $s->id }}"
                    data-type="sale"
                    data-user="{{ $s->user_id }}"
                    data-amount="{{ (int) $s->total_price }}"
                    @selected($selectedType === 'sale' && $selectedPayableId === $s->id)>
                [Bán hàng] #SL-{{ str_pad($s->id, 5, '0', STR_PAD_LEFT) }} — {{ $s->customer_name ?? 'Khách lẻ' }} ({{ $s->quantity }}x {{ $s->product->name ?? 'SP' }}, {{ number_format($s->total_price, 0, ',', '.') }}đ)
            </option>
        @endforeach
        @foreach ($subscriptions as $sub)
            <option value="{{ $sub->id }}"
                    data-type="subscription"
                    data-user="{{ $sub->user_id }}"
                    data-amount="{{ (int) $sub->price }}"
                    @selected($selectedType === 'subscription' && $selectedPayableId === $sub->id)>
                [Gói TV] #SUB-{{ str_pad($sub->id, 5, '0', STR_PAD_LEFT) }} — {{ $sub->user->name ?? 'User' }} ({{ $sub->plan->name ?? 'Plan' }}, {{ number_format($sub->price, 0, ',', '.') }}đ)
            </option>
        @endforeach
    </select>
    @error('payable_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Người dùng (Tùy chọn với khách lẻ)</label>
    <select name="user_id" id="payment-user-select" class="form-control">
        <option value="">-- Khách lẻ / Tự động theo đơn --</option>
        @foreach ($users as $u)
            <option value="{{ $u->id }}" @selected(old('user_id', $payment->user_id ?? '') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
        @endforeach
    </select>
    @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Số tiền (VNĐ) *</label>
    <input type="number" name="amount" id="payment-amount-input" class="form-control" min="0" step="1" value="{{ old('amount', isset($payment) ? (int) $payment->amount : 0) }}" required>
    @error('amount') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Phương thức thanh toán *</label>
    <select name="payment_method" class="form-control" required>
        @foreach (\App\Models\Payment::METHODS as $mVal => $mText)
            <option value="{{ $mVal }}" @selected(old('payment_method', $payment->payment_method ?? 'vnpay') === $mVal)>{{ $mText }} ({{ $mVal }})</option>
        @endforeach
    </select>
    @error('payment_method') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Mã giao dịch (Để trống sẽ tự sinh)</label>
    <input type="text" name="transaction_code" class="form-control" value="{{ old('transaction_code', $payment->transaction_code ?? '') }}">
    @error('transaction_code') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Thời gian thanh toán</label>
    <input type="datetime-local" name="paid_at" class="form-control" value="{{ old('paid_at', isset($payment->paid_at) ? $payment->paid_at->format('Y-m-d\TH:i') : '') }}">
    @error('paid_at') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái *</label>
    <select name="status" class="form-control" required>
        @foreach (\App\Models\Payment::STATUSES as $val => $text)
            <option value="{{ $val }}" @selected(old('status', $payment->status ?? 'paid') === $val)>{{ $text }}</option>
        @endforeach
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Ghi chú</label>
    <textarea name="note" class="form-control" rows="2">{{ old('note', $payment->note ?? '') }}</textarea>
    @error('note') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu Thanh toán</button>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Hủy</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('payable-type-select');
    const idSelect = document.getElementById('payable-id-select');
    const userSelect = document.getElementById('payment-user-select');
    const amountInput = document.getElementById('payment-amount-input');

    function filterPayableOptions(resetSelection = false) {
        const currentType = typeSelect.value;
        Array.from(idSelect.options).forEach(opt => {
            if (!opt.value) return;
            const match = opt.dataset.type === currentType;
            opt.hidden = !match;
            opt.disabled = !match;
        });
        if (resetSelection) {
            idSelect.value = '';
        }
    }

    typeSelect.addEventListener('change', function () {
        filterPayableOptions(true);
    });

    idSelect.addEventListener('change', function () {
        const selected = idSelect.options[idSelect.selectedIndex];
        if (!selected || !selected.value) return;
        if (selected.dataset.amount) {
            amountInput.value = selected.dataset.amount;
        }
        if (selected.dataset.user) {
            userSelect.value = selected.dataset.user;
        }
    });

    filterPayableOptions(false);
});
</script>
