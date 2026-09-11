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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('project_code')->unique()->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->enum('project_type', ['plotted', 'villa', 'commercial', 'mixed'])->default('plotted');
            $table->enum('status', ['upcoming', 'active', 'completed', 'sold-out'])->default('upcoming');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('starting_price', 15, 2)->nullable();
            $table->string('price_unit')->default('INR');
            $table->string('display_price')->nullable();
            $table->boolean('price_on_request')->default(false);
            $table->boolean('show_price')->default(true);
            $table->decimal('total_project_area', 12, 2)->nullable();
            $table->string('area_unit')->default('sqft');
            $table->integer('total_plots')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('desktop_hero')->nullable();
            $table->string('mobile_hero')->nullable();
            $table->string('master_plan')->nullable();
            $table->string('layout_map')->nullable();
            $table->string('location_map')->nullable();
            $table->string('brochure')->nullable();
            $table->string('walkthrough_video_url')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('slug');
            $table->index('location_id');
            $table->index('status');
            $table->index('is_published');
            $table->index('featured');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
