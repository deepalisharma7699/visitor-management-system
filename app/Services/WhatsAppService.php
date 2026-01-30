<?php

namespace App\Services;

use App\Helpers\CountryHelper;
use App\Mail\OTPMail;
use App\Mail\WelcomeMail;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WhatsAppService
{
    protected $provider;
    protected $twilioClient;

    public function __construct()
    {
        $this->provider = config('services.whatsapp.provider', 'twilio');
        
        // Only initialize Twilio client if credentials are configured
        if ($this->provider === 'twilio' && $this->isTwilioConfigured()) {
            try {
                $this->twilioClient = new Client(
                    config('services.twilio.account_sid'),
                    config('services.twilio.auth_token')
                );
                Log::debug('Twilio client initialized successfully');
            } catch (\Exception $e) {
                Log::warning('Failed to initialize Twilio client', [
                    'error' => $e->getMessage()
                ]);
                $this->twilioClient = null;
            }
        } else {
           // Log::debug('Twilio not configured, skipping client initialization');
            $this->twilioClient = null;
        }
    }

    /**
     * Generate a 4-digit OTP code
     */
    public function generateOTP(): string
    {
        return str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP via WhatsApp or Email
     */
    public function sendOTP(string $mobile, string $otp, string $name = '', string $email = ''): bool
    {
        Log::info('OTP Send Attempt', [
            'mobile' => $mobile,
            'name' => $name,
            'email' => $email,
            'provider' => $this->provider,
            'otp' => $otp, // Log OTP for debugging (remove in production)
        ]);

        $whatsappSuccess = false;
        $emailSuccess = false;

        try {
            // Try WhatsApp first if provider is configured
            if ($this->provider === 'twilio' && $this->isTwilioConfigured()) {
                Log::info('Attempting to send OTP via Twilio');
                $parsedNumber = $this->parseInternationalNumber($mobile);
                $whatsappSuccess = $this->sendViaTwilio($parsedNumber['full'], $otp, $name);
                Log::info('Twilio send result', ['success' => $whatsappSuccess]);
            } elseif ($this->provider === 'interakt' && $this->isInteraktConfigured()) {
                Log::info('Attempting to send OTP via Interakt');
                $parsedNumber = $this->parseInternationalNumber($mobile);
                $whatsappSuccess = $this->sendViaInterakt($parsedNumber['full'], $otp, $name);
                Log::info('Interakt send result', ['success' => $whatsappSuccess]);
            }

            // ALWAYS send email if provided, regardless of WhatsApp success
            if ($email) {
                Log::info('Sending OTP via email (in addition to WhatsApp)');
                $emailSuccess = $this->sendViaEmail($email, $otp, $name);
            }

            // Success if either method worked
            if ($whatsappSuccess || $emailSuccess) {
                Log::info('OTP sent successfully', [
                    'whatsapp' => $whatsappSuccess,
                    'email' => $emailSuccess
                ]);
                return true;
            }

            // Final fallback: Log OTP (for development/testing)
            Log::warning('OTP sent via log only (no WhatsApp or Email succeeded)', [
                'mobile' => $mobile,
                'otp' => $otp,
                'name' => $name
            ]);
            
            return true; // Return true for development mode

        } catch (\Exception $e) {
            Log::error('OTP Send Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'mobile' => $mobile,
                'email' => $email
            ]);
            
            // Try email as last resort if not already attempted
            if ($email && !$emailSuccess) {
                try {
                    Log::info('Attempting email as last resort after exception');
                    return $this->sendViaEmail($email, $otp, $name);
                } catch (\Exception $emailException) {
                    Log::error('Email fallback also failed', [
                        'error' => $emailException->getMessage()
                    ]);
                }
            }
            
            return false;
        }
    }

    /**
     * Send OTP via Twilio
     */
    protected function sendViaTwilio(string $mobile, string $otp, string $name = ''): bool
    {
        try {
            if (!$this->twilioClient) {
                Log::warning('Twilio client not initialized');
                return false;
            }
            
            Log::debug('Twilio send attempt', ['mobile' => $mobile, 'name' => $name]);
            
            // Parse phone number to get country info
            $phoneInfo = CountryHelper::parsePhoneNumber($mobile);
            $flag = $phoneInfo['flag'];
            
            $greeting = $name ? "Hello {$name} {$flag}!" : "Hello {$flag}!";
            $message = "{$greeting}\n\nYour Mayfair VMS verification code is: *{$otp}*\n\n⏰ This code will expire in 5 minutes.\n\n🔒 For security reasons, please do not share this code with anyone.\n\nThank you for visiting Mayfair!";

            $result = $this->twilioClient->messages->create(
                "whatsapp:{$mobile}",
                [
                    'from' => config('services.twilio.whatsapp_from'),
                    'body' => $message
                ]
            );
            
            Log::info('Twilio message sent successfully', [
                'sid' => $result->sid,
                'status' => $result->status
            ]);

            return true;
            
        } catch (\Exception $e) {
            Log::error('Twilio send failed', [
                'error' => $e->getMessage(),
                'mobile' => $mobile
            ]);
            throw $e;
        }
    }

    /**
     * Send OTP via Interakt
     */
    protected function sendViaInterakt(string $mobile, string $otp, string $name = ''): bool
    {
        try {
            Log::debug('Interakt send attempt', ['mobile' => $mobile, 'name' => $name]);
            
            // Parse international number
            $parsedNumber = $this->parseInternationalNumber($mobile);
            
            $apiKey = config('services.interakt.api_key');
            
            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification for development
            ])->withHeaders([
                'Authorization' => 'Basic ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post(config('services.interakt.base_url') . '/message/', [
                'userId' => '',
                'fullPhoneNumber' => $parsedNumber['full'],
                'callbackData' => 'otp_verification',
                'type' => 'Text',
                'data' => [
                    'message' => $otp . ' is your verification code. For your security, do not share this code. Expires in 10 minutes.'
                ],
            ]);
            
            if ($response->successful()) {
                Log::info('Interakt message sent successfully', [
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Interakt send failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
            
        } catch (\Exception $e) {
            Log::error('Interakt send exception', [
                'error' => $e->getMessage(),
                'mobile' => $mobile
            ]);
            throw $e;
        }
    }

    /**
     * Parse international phone number
     */
    protected function parseInternationalNumber(string $mobile): array
    {
        // Remove any non-numeric characters except +
        $mobile = preg_replace('/[^0-9+]/', '', $mobile);
        
        // Ensure it starts with +
        if (!str_starts_with($mobile, '+')) {
            $mobile = '+' . $mobile;
        }
        
        // Extract country code and number
        $countryCodeMap = [
            '+1' => 1,     // US/Canada
            '+44' => 2,    // UK
            '+91' => 2,    // India
            '+971' => 3,   // UAE
            '+65' => 2,    // Singapore
            '+61' => 2,    // Australia
            '+966' => 3,   // Saudi Arabia
            '+974' => 3,   // Qatar
            '+86' => 2,    // China
            '+81' => 2,    // Japan
            '+82' => 2,    // South Korea
        ];
        
        foreach ($countryCodeMap as $code => $length) {
            if (str_starts_with($mobile, $code)) {
                return [
                    'country_code' => $code,
                    'number' => substr($mobile, strlen($code)),
                    'full' => $mobile,
                ];
            }
        }
        
        // Default fallback
        return [
            'country_code' => '+1',
            'number' => ltrim($mobile, '+'),
            'full' => $mobile,
        ];
    }

    /**
     * Get country code from mobile number
     */
    protected function getCountryCodeFromMobile(string $mobile): string
    {
        $mobile = preg_replace('/[^0-9+]/', '', $mobile);
        
        $countryMap = [
            '+1' => 'US',      // USA/Canada
            '+44' => 'GB',     // United Kingdom
            '+91' => 'IN',     // India
            '+971' => 'AE',    // UAE
            '+65' => 'SG',     // Singapore
            '+61' => 'AU',     // Australia
            '+966' => 'SA',    // Saudi Arabia
            '+974' => 'QA',    // Qatar
            '+86' => 'CN',     // China
            '+81' => 'JP',     // Japan
            '+82' => 'KR',     // South Korea
        ];
        
        foreach ($countryMap as $code => $country) {
            if (str_starts_with($mobile, $code)) {
                return $country;
            }
        }
        
        return 'US'; // Default
    }

    /**
     * Get country flag emoji using intl
     */
    protected function getCountryFlagEmoji(string $countryCode): string
    {
        if (!extension_loaded('intl')) {
            return '🌍'; // Fallback if intl not available
        }
        
        // Convert country code to regional indicator symbols
        $countryCode = strtoupper($countryCode);
        $flag = '';
        
        for ($i = 0; $i < strlen($countryCode); $i++) {
            $flag .= mb_chr(ord($countryCode[$i]) + 127397, 'UTF-8');
        }
        
        return $flag;
    }

    /**
     * Check if Twilio is configured
     */
    protected function isTwilioConfigured(): bool
    {
        $sid = config('services.twilio.account_sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.whatsapp_from');
        
        $configured = !empty($sid) && !empty($token) && !empty($from) && 
                      $sid !== '' && $token !== '' && $from !== '';
        
        if (!$configured) {
            Log::debug('Twilio configuration check: NOT configured', [
                'has_sid' => !empty($sid) && $sid !== '',
                'has_token' => !empty($token) && $token !== '',
                'has_from' => !empty($from) && $from !== ''
            ]);
        }
        
        return $configured;
    }

    /**
     * Check if Interakt is configured
     */
    protected function isInteraktConfigured(): bool
    {
        $apiKey = config('services.interakt.api_key');
        $baseUrl = config('services.interakt.base_url');
        
        $configured = !empty($apiKey) && !empty($baseUrl);
        Log::debug('Interakt configuration check', [
            'configured' => $configured,
            'has_api_key' => !empty($apiKey),
            'has_base_url' => !empty($baseUrl)
        ]);
        
        return $configured;
    }

    /**
     * Send OTP via Email
     */
    protected function sendViaEmail(string $email, string $otp, string $name = ''): bool
    {
        try {
            Log::info('Sending OTP via email', [
                'email' => $email,
                'name' => $name
            ]);

            Mail::to($email)->send(new OTPMail($otp, $name, 5));
            
            Log::info('Email OTP sent successfully', ['email' => $email]);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Email OTP Send Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $email
            ]);
            return false;
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOTP(string $storedOTP, string $inputOTP): bool
    {
        return $storedOTP === $inputOTP;
    }

    /**
     * Send welcome message after successful registration
     */
    public function sendWelcomeMessage(string $mobile, string $name, string $visitorType, string $email = ''): bool
    {
        $whatsappSuccess = false;
        $emailSuccess = false;

        try {
            // Parse international number
            $parsedNumber = $this->parseInternationalNumber($mobile);
            $formattedMobile = $parsedNumber['full'];
            
            $message = "Welcome to Mayfair, {$name}! 🙏\n\n";
            $message .= "Your registration as a " . ucfirst($visitorType) . " has been completed successfully.\n\n";
            $message .= "Thank you for visiting us. Have a great day!";

            // Try WhatsApp if configured
            if ($this->provider === 'twilio' && $this->isTwilioConfigured()) {
                try {
                    $this->twilioClient->messages->create(
                        "whatsapp:{$formattedMobile}",
                        [
                            'from' => config('services.twilio.whatsapp_from'),
                            'body' => $message
                        ]
                    );
                    $whatsappSuccess = true;
                    Log::info('Welcome message sent via Twilio', ['mobile' => $mobile]);
                } catch (\Exception $e) {
                    Log::warning('Twilio welcome message failed', ['error' => $e->getMessage()]);
                }
            } elseif ($this->provider === 'interakt' && $this->isInteraktConfigured()) {
                try {
                    Http::withOptions([
                        'verify' => false, // Disable SSL verification for development
                    ])->withHeaders([
                        'Authorization' => 'Basic ' . config('services.interakt.api_key'),
                        'Content-Type' => 'application/json',
                    ])->post(config('services.interakt.base_url') . '/message/', [
                        'fullPhoneNumber' => $formattedMobile,
                        'callbackData' => 'welcome_message',
                        'type' => 'Text',
                        'data' => [
                            'message' => $message,
                        ],
                    ]);
                    $whatsappSuccess = true;
                    Log::info('Welcome message sent via Interakt', ['mobile' => $mobile]);
                } catch (\Exception $e) {
                    Log::warning('Interakt welcome message failed', ['error' => $e->getMessage()]);
                }
            }

            // ALWAYS send email if provided
            if ($email) {
                try {
                    Log::info('Sending welcome email', ['email' => $email]);
                    Mail::to($email)->send(new WelcomeMail($name, $visitorType, $mobile));
                    $emailSuccess = true;
                    Log::info('Welcome email sent successfully', ['email' => $email]);
                } catch (\Exception $e) {
                    Log::error('Welcome email failed', [
                        'error' => $e->getMessage(),
                        'email' => $email
                    ]);
                }
            }

            // Success if either method worked
            Log::info('Welcome message send result', [
                'whatsapp' => $whatsappSuccess,
                'email' => $emailSuccess
            ]);

            return $whatsappSuccess || $emailSuccess || true; // Always return true for now

        } catch (\Exception $e) {
            Log::error('Welcome Message Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
