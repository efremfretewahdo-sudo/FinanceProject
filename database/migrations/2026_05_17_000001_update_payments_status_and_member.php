<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Step 1: Widen column to string so all values are accepted
        Schema::table('payments', function (Blueprint $table) {
            $table->string('status')->default('unpaid')->change();
        });

        // Step 2: Remap legacy values to paid / unpaid
        DB::table('payments')->whereIn('status', ['received', 'completed'])->update(['status' => 'paid']);
        DB::table('payments')->whereIn('status', ['pending', 'failed'])->update(['status' => 'unpaid']);
    }

    public function down(): void {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('status')->default('completed')->change();
        });
    }
};
