<?php
/**
 * Firebase Configuration
 * Update with your Firebase credentials
 */

return [
    'api_key' => env('FIREBASE_API_KEY', 'YOUR_FIREBASE_API_KEY'),
    'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'your-project.firebaseapp.com'),
    'database_url' => env('FIREBASE_DATABASE_URL', 'https://your-project.firebaseio.com'),
    'project_id' => env('FIREBASE_PROJECT_ID', 'your-project-id'),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'your-project.appspot.com'),
    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', ''),
    'app_id' => env('FIREBASE_APP_ID', ''),
    'service_account' => [
        'type' => 'service_account',
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
        'private_key' => env('FIREBASE_PRIVATE_KEY'),
        'client_email' => env('FIREBASE_CLIENT_EMAIL'),
        'client_id' => env('FIREBASE_CLIENT_ID'),
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'token_uri' => 'https://oauth2.googleapis.com/token',
        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL'),
    ]
];
