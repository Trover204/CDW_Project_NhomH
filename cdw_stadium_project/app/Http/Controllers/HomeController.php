<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $courts = [
            [
                'tag' => 'Bóng đá',
                'name' => 'Sân bóng Quận 9',
                'loc' => 'Thủ Đức, TP.HCM',
                'price' => 250000,
                'slots' => 6,
                'icon' => '
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7l4 3-1.5 4.5h-5L8 10z"/>
                ',
            ],

            [
                'tag' => 'Cầu lông',
                'name' => 'Cầu lông Bình Thạnh',
                'loc' => 'Bình Thạnh, TP.HCM',
                'price' => 120000,
                'slots' => 10,
                'icon' => '
                    <path d="M4 20l6-6M13 4l7 7-9 9-5-5z"/>
                    <circle cx="6" cy="18" r="2"/>
                ',
            ],

            [
                'tag' => 'Pickleball',
                'name' => 'Pickleball Quận 7',
                'loc' => 'Quận 7, TP.HCM',
                'price' => 180000,
                'slots' => 3,
                'icon' => '
                    <rect x="4" y="9" width="16" height="9" rx="1.5"/>
                    <path d="M12 9V4M9 4h6"/>
                ',
            ],

            [
                'tag' => 'Bóng chuyền',
                'name' => 'Bóng chuyền Gò Vấp',
                'loc' => 'Gò Vấp, TP.HCM',
                'price' => 200000,
                'slots' => 8,
                'icon' => '
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M3 12h18"/>
                    <path d="M12 3c3 3 3 15 0 18"/>
                    <path d="M7 5c2 4 2 10 0 14"/>
                    <path d="M17 5c-2 4-2 10 0 14"/>
                ',
            ],
        ];

        foreach ($courts as &$court) {
            $court['price_formatted'] =
                number_format($court['price'], 0, ',', '.') . 'đ';
        }

        unset($court);

        return view('web.home', compact('courts'));
    }
}