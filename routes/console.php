<?php

use Illuminate\Support\Facades\Schedule;

/**
 * Schedule commands to run automatically
 * 
 * Auto-checkout: Runs daily at 12:01 AM Dubai time
 * This ensures visitors from previous day are automatically checked out
 */

Schedule::command('visitors:auto-checkout')
    ->dailyAt('00:01')
    ->timezone('Asia/Dubai')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Log::info('Auto-checkout completed successfully');
    })
    ->onFailure(function () {
        \Log::error('Auto-checkout failed');
    });
