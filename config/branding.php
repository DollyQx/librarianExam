<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Centralized Website & Application Branding Configuration
    |--------------------------------------------------------------------------
    |
    | Modify website identity, colors, contact details, social links, and SEO
    | parameters centrally without modifying multiple Blade template files.
    |
    */

    'name' => env('APP_NAME', 'BIHAR LET/Librarian Exam'),
    'owner' => 'Sumit Verma',
    'tagline' => 'बिहार LET और Bihar Librarian परीक्षा की तैयारी के लिए Quiz, PDF Notes और Video Lectures.',
    'short_name' => 'Bihar LET & Librarian',
    
    // Identity & Imagery
    'logo_icon' => 'fas fa-book-reader',
    'favicon' => '/favicon.ico',
    'primary_color' => '#1d4ed8',
    'secondary_color' => '#0f172a',
    'accent_color' => '#f59e0b',
    
    // Contact Information
    'contact_email' => env('BRAND_CONTACT_EMAIL', 'support@studyly.online'),
    'contact_phone' => env('BRAND_CONTACT_PHONE', '+91 8271000000'),
    'address' => 'Bihar, India',

    // Social Media Links
    'social' => [
        'youtube' => env('SOCIAL_YOUTUBE', 'https://youtube.com/@choicestudyjunction8380'),
        'telegram' => env('SOCIAL_TELEGRAM', 'https://t.me/SssVvv8271'),
        'whatsapp' => env('SOCIAL_WHATSAPP', 'https://t.me/SssVvv8271'),
    ],

    // Global Default SEO Settings
    'seo' => [
        'title' => 'BIHAR LET/Librarian Exam | बिहार LET और Librarian परीक्षा तैयारी Portal',
        'description' => 'बिहार LET (Library Eligibility Test) और Bihar Librarian प्रतियोगी परीक्षा की तैयारी के लिए नि:शुल्क और सशुल्क क्विज़, PDF नोट्स एवं वीडियो लेक्चर्स।',
        'keywords' => 'Bihar LET, Bihar Librarian Exam, Bihar Librarian Quiz, Bihar LET Syllabus, Bihar Library Science Notes, Sumit Verma, Choice Study Junction',
        'author' => 'Sumit Verma (Choice Study Junction)',
        'og_image' => '/images/og-banner.png',
    ],
];

