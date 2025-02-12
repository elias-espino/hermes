<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('branches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('nombre', 100);
            $table->string('direccion', 250);
            $table->string('telefono', 20)->nullable();
            $table->string('iva', 50)->nullable();
            $table->string('email', 50)->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('branches');
    }
};
