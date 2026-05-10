<?php
/**
 * Notification Configuration
 * 
 * Set up email and SMS sending
 */

return [
    'email' => [
        'enabled' => true,
        'from_email' => 'noreply@careerlab.local',
        'from_name' => 'Career Lab',
        // For production, you can use SMTP
        'smtp_enabled' => false,
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_username' => '',
        'smtp_password' => '',
    ],
    'sms' => [
        'enabled' => false, // Requires Twilio account
        'provider' => 'twilio', // twilio, nexmo, etc
        'twilio_account_sid' => '',
        'twilio_auth_token' => '',
        'twilio_phone_number' => '',
    ],
];
