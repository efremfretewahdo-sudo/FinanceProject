<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('source_type')->nullable()->after('description'); // 'payment' | 'other_income' | null (manual)
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
            $table->index(['source_type', 'source_id'], 'tx_source_idx');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('tx_source_idx');
            $table->dropColumn(['source_type', 'source_id']);
        });
    }
};
