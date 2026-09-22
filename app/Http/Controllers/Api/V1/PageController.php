<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use \App\Traits\ApiResponse;

    public function about()
    {
        return $this->success('About Us content retrieved successfully.', [
            'title' => 'About Us',
            'content' => 'Welcome to LeadPro. We are dedicated to providing the best lead management solutions.'
        ]);
    }

    public function privacyPolicy()
    {
        return $this->success('Privacy Policy retrieved successfully.', [
            'title' => 'Privacy Policy',
            'content' => 'Your privacy is critically important to us. This policy outlines how we handle your data.'
        ]);
    }
}
