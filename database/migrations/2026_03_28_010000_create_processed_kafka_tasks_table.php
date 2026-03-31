<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processed_kafka_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_id', 255)->unique();
            $table->foreignId('advertisement_id')->nullable()->constrained('advertisements')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processed_kafka_tasks');
    }
};
