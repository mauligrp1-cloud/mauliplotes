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
        Schema::create('project_rera_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('phase')->nullable();
            $table->string('rera_number');
            $table->string('rera_url')->nullable();
            $table->enum('status', ['approved', 'registered', 'under-review', 'rejected'])->nullable();
            $table->string('approval_authority')->nullable();
            $table->longText('additional_legal_information')->nullable();
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
        Schema::dropIfExists('project_rera_registrations');
    }
};
