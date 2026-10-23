<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo doanh thu - {{ $periodLabel }} - Sân Nhóm H</title>
    <style>
        body {
            font-family: DejaVu Sans, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            color: #111827;
            background: #F9FAFB;
            margin: 0;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
        }

        .pdf-container {
            max-width: 960px;
            margin: 0 auto;
            background: #FFFFFF;
            padding: 36px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #E5E7EB;
        }

        .no-print-bar {
            max-width: 960px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-print {
            background: #0D5C40;
            color: #FFFFFF;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0D5C40;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            color: #0D5C40;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }

        .brand-subtitle {
            font-size: 12px;
            color: #6B7280;
            margin: 0;
        }

        .report-title-box {
            text-align: right;
        }

        .report-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px 0;
        }

        .report-meta {
            font-size: 11.5px;
            color: #6B7280;
            margin: 0;
        }

        .kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .kpi-box {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 8px;
            padding: 12px 14px;
        }

        .kpi-box-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #166534;
            margin-bottom: 4px;
        }

        .kpi-box-val {
            font-size: 18px;
            font-weight: 800;
            color: #0D5C40;
        }

        .kpi-box-sub {
            font-size: 10.5px;
            color: #15803D;
            margin-top: 2px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0D5C40;
            margin: 20px 0 10px 0;
            border-left: 4px solid #0D5C40;
            padding-left: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 12px;
        }

        th {
            background: #F3F4F6;
            color: #374151;
            font-weight: 700;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #E5E7EB;
        }

        td {
            padding: 7px 10px;
            border: 1px solid #E5E7EB;
            color: #1F2937;
        }

        tr:nth-child(even) td {
            background: #F9FAFB;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 36px;
            border-top: 1px solid #E5E7EB;
            padding-top: 16px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #6B7280;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            text-align: center;
        }

        .sign-col {
            width: 200px;
        }

        .sign-title {
            font-weight: 700;
            margin-bottom: 60px;
        }

        @media print {
            body {
                background: #FFFFFF;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            .pdf-container {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <a href="{{ route('admin.reports.revenue', request()->all()) }}" style="color: #0D5C40; text-decoration: none; font-weight: 600;">
            &larr; Quay lại trang báo cáo
        </a>
        <button onclick="window.print()" class="btn-print">
            In báo cáo / Lưu PDF
        </button>
    </div>

    <div class="pdf-container">
        <div class="header">
            <div>
                <h1 class="brand-title">HỆ THỐNG SÂN THỂ THAO NHÓM H</h1>
                <p class="brand-subtitle">Địa chỉ: Khu Công nghệ phần mềm, ĐHQG-HCM, Thủ Đức, TP.HCM</p>
                <p class="brand-subtitle">Hotline: 0900.123.456 | Website: sannhomh.vn</p>
            </div>
            <div class="report-title-box">
                <h2 class="report-title">BÁO CÁO DOANH THU</h2>
                <p class="report-meta">Kỳ báo cáo: <strong>{{ $periodLabel }}</strong></p>
                <p class="report-meta">Ngày lập: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        {{-- KPI Blocks: Tổng và 3 phần --}}
        <div class="kpi-row">
            <div class="kpi-box">
                <div class="kpi-box-title">Tổng doanh thu</div>
                <div class="kpi-box-val">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
                <div class="kpi-box-sub">{{ $paidPaymentsCount ?? $completedBookingsCount }} giao dịch thành công</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-box-title">Đặt sân (Booking)</div>
                <div class="kpi-box-val">{{ number_format($bookingRevenue ?? 0, 0, ',', '.') }}đ</div>
                <div class="kpi-box-sub">{{ $bookingPaymentsCount ?? 0 }} giao dịch</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-box-title">Bán hàng (Sale)</div>
                <div class="kpi-box-val">{{ number_format($saleRevenue ?? 0, 0, ',', '.') }}đ</div>
                <div class="kpi-box-sub">{{ $salePaymentsCount ?? 0 }} giao dịch</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-box-title">Gói thành viên</div>
                <div class="kpi-box-val">{{ number_format($subscriptionRevenue ?? 0, 0, ',', '.') }}đ</div>
                <div class="kpi-box-sub">{{ $subscriptionPaymentsCount ?? 0 }} giao dịch</div>
            </div>
        </div>

        {{-- Cơ cấu theo nguồn thanh toán --}}
        <div class="section-title">1. CƠ CẤU DOANH THU THEO NGUỒN THANH TOÁN</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">STT</th>
                    <th>Nguồn doanh thu</th>
                    <th class="text-center" style="width: 100px;">Số GD</th>
                    <th class="text-right" style="width: 150px;">Doanh thu (VNĐ)</th>
                    <th class="text-right" style="width: 90px;">Tỷ trọng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sourcesBreakdown as $idx => $src)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $src['name'] }}</strong></td>
                        <td class="text-center">{{ $src['count'] }}</td>
                        <td class="text-right"><strong>{{ number_format($src['revenue'], 0, ',', '.') }}đ</strong></td>
                        <td class="text-right">{{ $src['percent'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Doanh thu theo loại sân --}}
        <div class="section-title">2. DOANH THU THEO LOẠI MÔN THỂ THAO / SÂN</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">STT</th>
                    <th>Loại môn / Sân</th>
                    <th class="text-center" style="width: 100px;">Số GD</th>
                    <th class="text-right" style="width: 140px;">Doanh thu (VNĐ)</th>
                    <th class="text-right" style="width: 90px;">Tỷ trọng</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sportTypesBreakdown as $idx => $st)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $st['name'] }}</strong></td>
                        <td class="text-center">{{ $st['count'] }}</td>
                        <td class="text-right"><strong>{{ number_format($st['revenue'], 0, ',', '.') }}đ</strong></td>
                        <td class="text-right">{{ $st['percent'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Chưa có dữ liệu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Thống kê theo 12 tháng --}}
        <div class="section-title">3. TỔNG HỢP DOANH THU 12 THÁNG TRONG NĂM {{ $selectedYear }}</div>
        <table>
            <thead>
                <tr>
                    <th>Tháng</th>
                    <th class="text-center">Số GD</th>
                    <th class="text-right">Đặt sân</th>
                    <th class="text-right">Bán hàng</th>
                    <th class="text-right">Gói TV</th>
                    <th class="text-right">Tổng doanh thu</th>
                    <th class="text-right">Tăng trưởng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyBreakdown as $m)
                    <tr style="{{ $m['month_key'] === $selectedMonth ? 'background: #DCFCE7; font-weight: bold;' : '' }}">
                        <td>{{ $m['month_label'] }}</td>
                        <td class="text-center">{{ $m['count'] }}</td>
                        <td class="text-right">{{ number_format($m['booking_revenue'] ?? 0, 0, ',', '.') }}đ</td>
                        <td class="text-right">{{ number_format($m['sale_revenue'] ?? 0, 0, ',', '.') }}đ</td>
                        <td class="text-right">{{ number_format($m['subscription_revenue'] ?? 0, 0, ',', '.') }}đ</td>
                        <td class="text-right">{{ number_format($m['revenue'], 0, ',', '.') }}đ</td>
                        <td class="text-right">
                            @if($m['growth'] !== null)
                                {{ $m['growth'] >= 0 ? '+' : '' }}{{ $m['growth'] }}%
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Danh sách giao dịch thanh toán --}}
        <div class="section-title">4. CHI TIẾT CÁC GIAO DỊCH THANH TOÁN TRONG KỲ</div>
        <table>
            <thead>
                <tr>
                    <th>Mã GD</th>
                    <th>Nguồn</th>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Nội dung</th>
                    <th>Phương thức</th>
                    <th class="text-right">Số tiền</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($allPayments as $p)
                    <tr>
                        <td>{{ $p->transaction_code ?? ('#PAY-' . $p->id) }}</td>
                        <td>{{ $p->type_label }}</td>
                        <td>{{ $p->reference }}</td>
                        <td>{{ $p->customer_name }}</td>
                        <td>{{ $p->description }}</td>
                        <td>{{ $p->method_label }}</td>
                        <td class="text-right"><strong>{{ number_format($p->amount, 0, ',', '.') }}đ</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Không có giao dịch nào trong kỳ này</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signatures">
            <div class="sign-col">
                <div class="sign-title">NGƯỜI LẬP BÁO CÁO</div>
                <div>(Ký và ghi rõ họ tên)</div>
            </div>
            <div class="sign-col">
                <div class="sign-title">KẾ TOÁN TRƯỞNG</div>
                <div>(Ký và ghi rõ họ tên)</div>
            </div>
            <div class="sign-col">
                <div class="sign-title">GIÁM ĐỐC / CHỦ SÂN</div>
                <div>(Ký và đóng dấu)</div>
            </div>
        </div>

        <div class="footer">
            <span>Hệ thống quản lý sân thể thao Nhóm H — Xuất tự động lúc {{ now()->format('d/m/Y H:i:s') }}</span>
            <span>Trang 1/1</span>
        </div>
    </div>

</body>
</html>
