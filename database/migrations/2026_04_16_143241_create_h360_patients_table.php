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
        Schema::create('h360_data', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->string('full_name');
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('encounter_id')->unique();
            $table->datetime('encounter_date');
            $table->integer('systole')->nullable();
            $table->integer('diastole')->nullable();
            $table->decimal('sugar_level', 8, 2)->nullable();
            $table->string('icd_code')->nullable();
            $table->string('diagnosis_name')->nullable();
            $table->datetime('next_appointment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h360_patients');
    }
};
