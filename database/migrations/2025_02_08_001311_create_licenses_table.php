<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->uuid('manager_id');
            $table->integer('max_companies')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('licenses');
    }
};

