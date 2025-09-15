<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispatcher_manager', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dispatcher_id')->constrained()->cascadeOnDelete();
            $table->unique(['manager_id', 'dispatcher_id']);
            $table->timestamps();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatcher_manager');
    }
};

