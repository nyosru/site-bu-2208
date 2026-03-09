<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisement_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained()->cascadeOnDelete();
            $table->string('path', 2048);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['advertisement_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisement_photos');
    }
};
