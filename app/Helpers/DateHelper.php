<?php

if (!function_exists('format_date_dubai')) {
    /**
     * Format a date in Dubai timezone with format: 29-Jan-2026 05:00 PM
     * 
     * @param mixed $date Carbon instance, datetime string, or null
     * @param string $format Custom format (default: d-M-Y h:i A)
     * @return string
     */
    function format_date_dubai($date, $format = 'd-M-Y h:i A')
    {
        if (is_null($date)) {
            return '';
        }

        try {
            $carbonDate = $date instanceof \Carbon\Carbon 
                ? $date 
                : \Carbon\Carbon::parse($date);

            return $carbonDate->timezone('Asia/Dubai')->format($format);
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('dubai_now')) {
    /**
     * Get current date/time in Dubai timezone
     * 
     * @return \Carbon\Carbon
     */
    function dubai_now()
    {
        return \Carbon\Carbon::now('Asia/Dubai');
    }
}

if (!function_exists('dubai_today')) {
    /**
     * Get today's date in Dubai timezone
     * 
     * @return \Carbon\Carbon
     */
    function dubai_today()
    {
        return \Carbon\Carbon::today('Asia/Dubai');
    }
}

if (!function_exists('is_previous_day')) {
    /**
     * Check if a date is from a previous day (Dubai timezone)
     * 
     * @param mixed $date
     * @return bool
     */
    function is_previous_day($date)
    {
        if (is_null($date)) {
            return false;
        }

        $carbonDate = $date instanceof \Carbon\Carbon 
            ? $date 
            : \Carbon\Carbon::parse($date);

        $dubaiDate = $carbonDate->timezone('Asia/Dubai');
        $today = dubai_today();

        return $dubaiDate->lt($today);
    }
}
