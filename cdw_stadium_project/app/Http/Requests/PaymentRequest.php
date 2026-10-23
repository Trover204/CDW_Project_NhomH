<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('payable_type') && $this->filled('booking_id')) {
            $this->merge([
                'payable_type' => Payment::TYPE_BOOKING,
                'payable_id' => $this->input('booking_id'),
            ]);
        }
    }

    public function rules(): array
    {
        $payment = $this->route('payment');
        $paymentId = $payment instanceof Payment ? $payment->id : $payment;
        $payableType = (string) $this->input('payable_type', Payment::TYPE_BOOKING);
        $sourceTable = Payment::SOURCE_TABLES[$payableType] ?? 'bookings';

        return [
            'payable_type' => ['required', 'string', Rule::in(array_keys(Payment::TYPES))],
            'payable_id' => [
                'required',
                'integer',
                Rule::exists($sourceTable, 'id'),
                Rule::unique('payments', 'payable_id')
                    ->where(fn ($query) => $query->where('payable_type', $payableType))
                    ->ignore($paymentId),
            ],
            'user_id' => ['nullable', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'transaction_code' => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('payments', 'transaction_code')->ignore($paymentId),
            ],
            'status' => ['required', Rule::in(array_keys(Payment::STATUSES))],
            'paid_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'payable_type.required' => 'Vui lòng chọn loại đơn nguồn.',
            'payable_type.in' => 'Loại đơn nguồn không hợp lệ.',
            'payable_id.required' => 'Vui lòng chọn đơn nguồn.',
            'payable_id.exists' => 'Đơn nguồn được chọn không tồn tại.',
            'payable_id.unique' => 'Đơn này đã có giao dịch thanh toán.',
            'amount.required' => 'Vui lòng nhập số tiền thanh toán.',
            'amount.min' => 'Số tiền thanh toán không được nhỏ hơn 0.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'transaction_code.unique' => 'Mã giao dịch đã tồn tại.',
            'status.required' => 'Vui lòng chọn trạng thái thanh toán.',
            'status.in' => 'Trạng thái thanh toán không hợp lệ.',
        ];
    }
}
