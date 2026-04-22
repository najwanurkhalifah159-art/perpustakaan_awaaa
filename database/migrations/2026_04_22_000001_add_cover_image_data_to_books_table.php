<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->longText('cover_image_data')->nullable()->after('cover_image');
            $table->string('cover_image_mime')->nullable()->after('cover_image_data');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['cover_image_data', 'cover_image_mime']);
        });
    }
};
