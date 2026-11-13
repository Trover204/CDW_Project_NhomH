<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        if ($this->has('start_time') && is_string($this->start_time)) {
            $merge['start_time'] = substr($this->start_time, 0, 5);
        }
        if ($this->has('end_time') && is_string($this->end_time)) {
            $merge['end_time'] = substr($this->end_time, 0, 5);
        }
        if (! empty($merge)) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'court_id' => 'required|exists:courts,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'customer_name' => 'required|string|max:50',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:191',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'subtotal' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,checked_in,completed,cancelled,rejected',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $status = (string) $this->input('status', 'pending');
                if (in_array($status, ['cancelled', 'rejected'], true)) {
                    return;
                }

                $booking = $this->route('booking');
                $ignoreId = $booking instanceof Booking ? $booking->id : $booking;

                $courtId = (int) $this->input('court_id');
                $bookingDate = (string) $this->input('booking_date');
                $startTime = (string) $this->input('start_time');

                $exists = Booking::query()
                    ->where('court_id', $courtId)
                    ->whereDate('booking_date', $bookingDate)
                    ->where(function ($q) use ($startTime) {
                        $q->where('start_time', $startTime)
                            ->orWhere('start_time', $startTime.':00');
                    })
                    ->whereNotIn('status', ['cancelled', 'rejected'])
                    ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('start_time', 'Khung giờ bắt đầu này trên sân đã có đơn đặt hoạt động khác.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Vui lòng chọn người dùng.',
            'court_id.required' => 'Vui lòng chọn sân thể thao.',
            'customer_name.required' => 'Vui lòng nhập tên khách hàng.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại khách hàng.',
            'customer_email.required' => 'Vui lòng nhập email khách hàng.',
            'booking_date.required' => 'Vui lòng chọn ngày đặt sân.',
            'start_time.required' => 'Vui lòng chọn giờ bắt đầu.',
            'end_time.required' => 'Vui lòng chọn giờ kết thúc.',
            'end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        ];
    }
}

