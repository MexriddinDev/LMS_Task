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
        Schema::create('group_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')
                ->constrained('groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
            $table->unique(['group_id', 'student_id']);



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_students');
    }
};
