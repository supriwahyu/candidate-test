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
        Schema::create('clt_layers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('layup_id');
            $table->foreign('layup_id')
                ->references('id')
                ->on('clt_layups')
                ->onDelete('cascade');
            $table->integer('layer_order');
            $table->decimal('thickness');
            $table->decimal('width');
            $table->decimal('angle');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clt_layers');
    }
};
