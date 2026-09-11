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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('focus_keyword')->nullable()->after('meta_title');
            $table->string('og_title')->nullable()->after('og_image');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('author_name')->nullable()->after('created_by');
            $table->string('author_role')->nullable()->after('author_name');
            $table->string('author_avatar')->nullable()->after('author_role');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->unsignedInteger('reading_time')->nullable()->after('is_featured');
            $table->unsignedBigInteger('views_count')->default(0)->after('reading_time');
            $table->json('related_project_ids')->nullable()->after('views_count');
            $table->boolean('enable_cta_box')->default(true)->after('related_project_ids');
            $table->string('cta_heading')->nullable()->after('enable_cta_box');
            $table->text('cta_description')->nullable()->after('cta_heading');
            $table->string('cta_button_text')->nullable()->after('cta_description');
            $table->string('cta_button_url')->nullable()->after('cta_button_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'focus_keyword',
                'og_title',
                'og_description',
                'author_name',
                'author_role',
                'author_avatar',
                'is_featured',
                'reading_time',
                'views_count',
                'related_project_ids',
                'enable_cta_box',
                'cta_heading',
                'cta_description',
                'cta_button_text',
                'cta_button_url',
            ]);
        });
    }
};
