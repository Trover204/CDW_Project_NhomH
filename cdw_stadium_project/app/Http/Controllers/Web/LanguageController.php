<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    /**
     * Chuyển đổi ngôn ngữ tiếng Việt (vi) - tiếng Anh (en).
     */
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, ['vi', 'en'], true)) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
