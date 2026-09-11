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
            if (!Schema::hasColumn('projects', 'video_heading')) {
                $table->string('video_heading')->nullable()->after('walkthrough_video_url');
            }
            if (!Schema::hasColumn('projects', 'video_subtitle')) {
                $table->string('video_subtitle')->nullable()->after('video_heading');
            }
            if (!Schema::hasColumn('projects', 'video_description')) {
                $table->text('video_description')->nullable()->after('video_subtitle');
            }
            if (!Schema::hasColumn('projects', 'video_thumbnail')) {
                $table->string('video_thumbnail', 500)->nullable()->after('video_description');
            }
            if (!Schema::hasColumn('projects', 'video_features')) {
                $table->json('video_features')->nullable()->after('video_thumbnail');
            }
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
            $table->dropColumn(['video_heading', 'video_subtitle', 'video_description', 'video_thumbnail', 'video_features']);
        });
    }
};
