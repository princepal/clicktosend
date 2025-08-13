<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->integer('dispatchers')->default(1)->after('plan_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('dispatchers')->default(1)->after('plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn('dispatchers');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('dispatchers');
        });
    }
};
