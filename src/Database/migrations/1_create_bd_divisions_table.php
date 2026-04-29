<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bd_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_bn');
            $table->string('slug')->unique();
            $table->string('code')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bd_divisions');
    }
};
