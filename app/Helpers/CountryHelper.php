<?php

namespace App\Helpers;

class CountryHelper
{
    /**
     * Get all supported countries with their details
     */
    public static function getCountries(): array
    {
        return [
            [
                'code' => 'IN',
                'name' => 'India',
                'dial_code' => '+91',
                'flag' => '🇮🇳',
                'max_length' => 10,
            ],
            [
                'code' => 'US',
                'name' => 'United States',
                'dial_code' => '+1',
                'flag' => '🇺🇸',
                'max_length' => 10,
            ],
            [
                'code' => 'GB',
                'name' => 'United Kingdom',
                'dial_code' => '+44',
                'flag' => '🇬🇧',
                'max_length' => 10,
            ],
            [
                'code' => 'AE',
                'name' => 'United Arab Emirates',
                'dial_code' => '+971',
                'flag' => '🇦🇪',
                'max_length' => 9,
            ],
            [
                'code' => 'SG',
                'name' => 'Singapore',
                'dial_code' => '+65',
                'flag' => '🇸🇬',
                'max_length' => 8,
            ],
            [
                'code' => 'AU',
                'name' => 'Australia',
                'dial_code' => '+61',
                'flag' => '🇦🇺',
                'max_length' => 9,
            ],
            [
                'code' => 'SA',
                'name' => 'Saudi Arabia',
                'dial_code' => '+966',
                'flag' => '🇸🇦',
                'max_length' => 9,
            ],
            [
                'code' => 'QA',
                'name' => 'Qatar',
                'dial_code' => '+974',
                'flag' => '🇶🇦',
                'max_length' => 8,
            ],
            [
                'code' => 'CN',
                'name' => 'China',
                'dial_code' => '+86',
                'flag' => '🇨🇳',
                'max_length' => 11,
            ],
            [
                'code' => 'JP',
                'name' => 'Japan',
                'dial_code' => '+81',
                'flag' => '🇯🇵',
                'max_length' => 10,
            ],
            [
                'code' => 'KR',
                'name' => 'South Korea',
                'dial_code' => '+82',
                'flag' => '🇰🇷',
                'max_length' => 10,
            ],
            [
                'code' => 'CA',
                'name' => 'Canada',
                'dial_code' => '+1',
                'flag' => '🇨🇦',
                'max_length' => 10,
            ],
            [
                'code' => 'DE',
                'name' => 'Germany',
                'dial_code' => '+49',
                'flag' => '🇩🇪',
                'max_length' => 11,
            ],
            [
                'code' => 'FR',
                'name' => 'France',
                'dial_code' => '+33',
                'flag' => '🇫🇷',
                'max_length' => 9,
            ],
            [
                'code' => 'IT',
                'name' => 'Italy',
                'dial_code' => '+39',
                'flag' => '🇮🇹',
                'max_length' => 10,
            ],
            [
                'code' => 'ES',
                'name' => 'Spain',
                'dial_code' => '+34',
                'flag' => '🇪🇸',
                'max_length' => 9,
            ],
        ];
    }

    /**
     * Get country by dial code
     */
    public static function getCountryByDialCode(string $dialCode): ?array
    {
        $countries = self::getCountries();
        
        foreach ($countries as $country) {
            if ($country['dial_code'] === $dialCode) {
                return $country;
            }
        }
        
        return null;
    }

    /**
     * Get country by country code
     */
    public static function getCountryByCode(string $code): ?array
    {
        $countries = self::getCountries();
        
        foreach ($countries as $country) {
            if ($country['code'] === strtoupper($code)) {
                return $country;
            }
        }
        
        return null;
    }

    /**
     * Get flag emoji from country code
     */
    public static function getFlagEmoji(string $countryCode): string
    {
        if (!extension_loaded('intl')) {
            $country = self::getCountryByCode($countryCode);
            return $country ? $country['flag'] : '🌍';
        }
        
        $countryCode = strtoupper($countryCode);
        $flag = '';
        
        for ($i = 0; $i < strlen($countryCode); $i++) {
            $flag .= mb_chr(ord($countryCode[$i]) + 127397, 'UTF-8');
        }
        
        return $flag;
    }

    /**
     * Parse phone number and extract country info
     */
    public static function parsePhoneNumber(string $phone): array
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }
        
        $countries = self::getCountries();
        
        foreach ($countries as $country) {
            if (str_starts_with($phone, $country['dial_code'])) {
                return [
                    'country' => $country,
                    'dial_code' => $country['dial_code'],
                    'number' => substr($phone, strlen($country['dial_code'])),
                    'full' => $phone,
                    'flag' => $country['flag'],
                ];
            }
        }
        
        // Default fallback
        return [
            'country' => self::getCountryByCode('US'),
            'dial_code' => '+1',
            'number' => ltrim($phone, '+'),
            'full' => $phone,
            'flag' => '🇺🇸',
        ];
    }
}
