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
            $table->string('hero_tagline')->nullable()->after('short_description');
            $table->string('overview_title')->nullable()->after('description');
            $table->string('overview_image')->nullable()->after('overview_title');
            $table->string('overview_punchline')->nullable()->after('overview_image');
            $table->string('bank_loan_text')->nullable()->after('price_unit');
            $table->string('legal_clearances_text')->nullable()->after('bank_loan_text');
            $table->string('cta_instant_callback_text')->nullable()->after('legal_clearances_text');
            $table->json('trust_strip')->nullable()->after('walkthrough_video_url');
            $table->json('specifications')->nullable()->after('trust_strip');
            $table->json('custom_amenities')->nullable()->after('specifications');
            $table->string('location_advantage_heading')->nullable()->after('custom_amenities');
            $table->text('location_advantage_subtext')->nullable()->after('location_advantage_heading');
        });

        Schema::table('project_plot_types', function (Blueprint $table) {
            $table->string('features')->nullable()->after('availability');
        });

        Schema::table('project_nearby_places', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('category');
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
            $table->dropColumn([
                'hero_tagline',
                'overview_title',
                'overview_image',
                'overview_punchline',
                'bank_loan_text',
                'legal_clearances_text',
                'cta_instant_callback_text',
                'trust_strip',
                'specifications',
                'custom_amenities',
                'location_advantage_heading',
                'location_advantage_subtext',
            ]);
        });

        Schema::table('project_plot_types', function (Blueprint $table) {
            $table->dropColumn('features');
        });

        Schema::table('project_nearby_places', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
