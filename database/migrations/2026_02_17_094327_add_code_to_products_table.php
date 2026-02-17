<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'code')) {
            // Hapus index unik jika ada sebelum drop kolom
            \DB::statement('DROP INDEX IF EXISTS products_code_unique');
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }
    }
};
