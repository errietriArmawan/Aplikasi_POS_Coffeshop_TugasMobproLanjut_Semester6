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
        Schema::table('users', function (Blueprint $table) {
                $table->string('address')->nullable(); // Menambahkan kolom address
                $table->string('contact')->nullable(); // Menambahkan kolom contact
                $table->string('role')->default('kasir'); // Menambahkan kolom role
                $table->string('username')->unique(); // Menambahkan kolom username
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
