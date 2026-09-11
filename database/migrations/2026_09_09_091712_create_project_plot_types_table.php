<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_plot_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('size_from', 10, 2)->nullable();
            $table->decimal('size_to', 10, 2)->nullable();
            $table->string('unit')->default('sqft');
            $table->decimal('price', 15, 2)->nullable();
            $table->string('availability')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_plot_types');
    }
};
