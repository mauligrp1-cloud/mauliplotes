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
            $table->string('address')->nullable()->after('location_id');
            $table->string('completion_year', 10)->nullable()->after('status');
            $table->json('section_visibility')->nullable()->after('is_published');
            $table->json('hero_badges')->nullable()->after('hero_tagline');
            $table->json('hero_cta')->nullable()->after('hero_badges');
            $table->json('hero_info_card')->nullable()->after('hero_cta');
            $table->string('overview_label')->nullable()->after('description');
            $table->string('overview_image_alt')->nullable()->after('overview_image');
            $table->string('overview_image_position')->nullable()->default('right')->after('overview_image_alt');
            $table->string('plot_configs_heading')->nullable()->after('overview_punchline');
            $table->text('plot_configs_description')->nullable()->after('plot_configs_heading');
            $table->string('plot_configs_cta_text')->nullable()->after('plot_configs_description');
            $table->string('amenities_heading')->nullable()->after('custom_amenities');
            $table->text('amenities_description')->nullable()->after('amenities_heading');
            $table->string('location_map_heading')->nullable()->after('location_map');
            $table->string('location_map_address')->nullable()->after('location_map_heading');
            $table->string('location_map_url')->nullable()->after('location_map_address');
            $table->string('specifications_heading')->nullable()->after('specifications');
            $table->string('faqs_heading')->nullable()->after('specifications_heading');
            $table->string('final_cta_heading')->nullable()->after('faqs_heading');
            $table->text('final_cta_description')->nullable()->after('final_cta_heading');
            $table->string('final_cta_primary_text')->nullable()->after('final_cta_description');
            $table->string('final_cta_secondary_text')->nullable()->after('final_cta_primary_text');
            $table->string('og_title')->nullable()->after('meta_description');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->string('robots')->nullable()->default('index, follow')->after('og_image');
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
                'address',
                'completion_year',
                'section_visibility',
                'hero_badges',
                'hero_cta',
                'hero_info_card',
                'overview_label',
                'overview_image_alt',
                'overview_image_position',
                'plot_configs_heading',
                'plot_configs_description',
                'plot_configs_cta_text',
                'amenities_heading',
                'amenities_description',
                'location_map_heading',
                'location_map_address',
                'location_map_url',
                'specifications_heading',
                'faqs_heading',
                'final_cta_heading',
                'final_cta_description',
                'final_cta_primary_text',
                'final_cta_secondary_text',
                'og_title',
                'og_description',
                'og_image',
                'robots',
            ]);
        });
    }
};
