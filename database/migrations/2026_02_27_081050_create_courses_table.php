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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('instructors')->nullable();
            $table->string('duration');
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced'])->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('rating', 3, 1)->default(0);
            $table->json('students')->nullable();
            $table->text('description');
            $table->string('image')->nullable();
            $table->json('topics')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
