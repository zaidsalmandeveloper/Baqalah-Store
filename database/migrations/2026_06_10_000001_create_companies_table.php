<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_code')->unique();
            $table->string('website')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
