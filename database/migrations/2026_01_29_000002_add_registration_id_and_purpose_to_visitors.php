<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('registration_id', 20)->unique()->nullable()->after('id');
            $table->text('purpose_of_visit')->nullable()->after('guest_type');
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['registration_id', 'purpose_of_visit']);
        });
    }
};
