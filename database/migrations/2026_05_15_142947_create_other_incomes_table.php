<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('other_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('source');
            $table->decimal('amount', 12, 2);
            $table->date('income_date');
            $table->text('description')->nullable();
            $table->enum('category', ['grant', 'donation', 'investment', 'interest', 'rental', 'other'])->default('other');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('other_incomes');
    }
};
