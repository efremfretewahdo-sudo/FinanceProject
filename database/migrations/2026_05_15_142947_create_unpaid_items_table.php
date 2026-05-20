<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unpaid_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->string('debtor_name');
            $table->decimal('amount_due', 12, 2);
            $table->date('due_date');
            $table->text('description')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unpaid_items');
    }
};
