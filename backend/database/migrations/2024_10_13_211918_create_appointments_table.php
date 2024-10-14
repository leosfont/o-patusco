<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('animal_name');
            $table->foreignId('animal_type_id')->constrained('animal_types')->onDelete('cascade');
            $table->integer('animal_age');
            $table->text('symptoms');
            $table->date('appointment_date');
            $table->enum('appointment_time', ['morning', 'afternoon']);
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

