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
        Schema::table('projects', function (Blueprint $table) {
            $table->json('overview_facts')->nullable()->after('overview_image_position');
            $table->json('related_project_ids')->nullable()->after('final_cta_secondary_text');
            $table->string('final_cta_image')->nullable()->after('related_project_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['overview_facts', 'related_project_ids', 'final_cta_image']);
        });
    }
};
