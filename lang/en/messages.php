<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application messages
    |--------------------------------------------------------------------------
     */

    'http' => [
        '404' => 'Route not found!',
        'healthy' => 'Healthy!',
        'model_not_found' => ':model not found!',
    ],

    'auth_email' => [
        'email_verified' => 'Email successfully verified!',
        'email_verification_failed' => 'Failed email verification!',
        'email_is_already_verified' => 'Email is already verified!',
        'verification_link_sent' => 'Email verification link sent!',
        'email_updated' => 'Email updated!',
        'email_forget' => 'Email forget!',
    ],

    'auth_password' => [
        'password_forgot' => 'Password forgot!',
        'password_updated' => 'Password updated!',
        'password_reset_link_sent' => 'Password reset link sent!',
        'failed_to_send_password_reset_link' => 'Failed to send password reset link!',
        'password_reset' => 'Password reset!',
        'failed_to_reset_password' => 'Failed to reset password!',
    ],

    'auth_facebook' => [
        'facebook_forgot' => 'Facebook account forgot!',
        'facebook_linked' => 'Facebook account linked!',
    ],

    'auth_google' => [
        'google_forgot' => 'Google account forgot!',
        'google_linked' => 'Google account linked!',
    ],

    'auth_google2fa' => [
        'forgot' => 'Google 2fa removed!',
        'disabled' => 'Google 2fa disabled!',
        'enabled' => 'Google 2fa enabled!',
    ],

    'auth_profile' => [
        'updated' => 'Profile updated!',
    ],

    'auth_trusted_device' => [
        'deleted' => 'Trusted device deleted!',
    ],

    'mail' => [
        'button_issues' => 'Issues with the button? Paste following link into your browser:',

        'auth_email_verification' => [
            'subject' => 'Please verify your email',
            'greetings' => 'Hello :first_name :last_name',
            'welcome' => 'Welcome to Blueprint!',
            'verification_requirement' => 'For your security we require all Blueprint users verify their email addresses.',
            'complete_verification' => 'Complete verification',
        ],

        'auth_password_reset_link' => [
            'subject' => 'We have received password reset request',
            'greetings' => 'Hello :first_name :last_name',
            'we_have_received_the_request' => 'We received a request to change your account password',
            'reset_password' => 'Reset password',
            'ignore_if_you_did_not_request_the_reset' => "If you didn't request this, please ignore this email",
        ],
    ],

];
