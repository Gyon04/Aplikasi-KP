<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropForeign(['classification_code']); // Hapus FK
        });
    }

    public function down(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->foreign('classification_code')->references('code')->on('classifications');
        });
    }
};
