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
        Schema::create('stockins', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('note')->nullable();
            $table->string('date');
            $table->string('qty')->default('0');
            $table->string('cost')->default('0');
            $table->bigInteger('created_by')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockins');
    }
};
