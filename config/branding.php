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

    'name' => env('APP_NAME', 'Librarian Exam Prep'),
    'tagline' => '100% Free Public Librarian Exam Portal',
    'short_name' => 'Librarian Prep',
    
    // Identity & Imagery
    'logo_icon' => 'fas fa-book-reader',
    'favicon' => '/favicon.ico',
    'primary_color' => '#2563eb',
    'secondary_color' => '#0f172a',
    'accent_color' => '#f59e0b',
    
    // Contact Information
    'contact_email' => env('BRAND_CONTACT_EMAIL', 'support@librarianexamprep.com'),
    'contact_phone' => env('BRAND_CONTACT_PHONE', '+91 98765 43210'),
    'address' => 'New Delhi, India',

    // Social Media Links
    'social' => [
        'youtube' => env('SOCIAL_YOUTUBE', 'https://youtube.com/@librarianexamprep'),
        'telegram' => env('SOCIAL_TELEGRAM', 'https://t.me/librarianexamprep'),
        'whatsapp' => env('SOCIAL_WHATSAPP', 'https://wa.me/919876543210'),
        'twitter' => env('SOCIAL_TWITTER', 'https://twitter.com/librarianprep'),
        'facebook' => env('SOCIAL_FACEBOOK', 'https://facebook.com/librarianprep'),
    ],

    // Global Default SEO Settings
    'seo' => [
        'title' => 'Librarian Exam Prep | Free Mock Tests, Study Materials & PDFs',
        'description' => 'Prepare for KVS, NVS, EMRS, UGC-NET, and State Librarian competitive examinations with free mock tests, PDF notes, video lectures, and topic-wise practice quizzes.',
        'keywords' => 'Librarian Exam, KVS Librarian, NVS Librarian, UGC NET Library Science, Library Science Mock Tests, Free PDF Notes',
        'author' => 'Librarian Exam Prep Team',
        'og_image' => '/images/og-banner.png',
    ],
];
