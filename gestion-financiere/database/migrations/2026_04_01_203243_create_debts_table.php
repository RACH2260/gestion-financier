<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->string('creditor');
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->decimal('remaining', 12, 2);
            $table->date('due_date');
            $table->date('start_date');
            $table->enum('type', ['supplier', 'loan', 'tax', 'other']);
            $table->decimal('interest', 5, 2)->nullable();
            $table->enum('status', ['active', 'paid', 'overdue'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
