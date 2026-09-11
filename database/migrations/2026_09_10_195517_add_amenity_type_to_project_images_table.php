<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN for enums.
        // We recreate the check constraint by rebuilding the table.
        // For MySQL, this alters the ENUM column directly.
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: drop and recreate with new type list
            DB::statement("PRAGMA foreign_keys=off");
            DB::statement("
                CREATE TABLE project_images_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    project_id INTEGER NOT NULL,
                    type VARCHAR CHECK(type IN ('featured','hero_desktop','hero_mobile','gallery','master_plan','layout_map','location_map','amenity','other')) DEFAULT 'gallery',
                    image_path VARCHAR NOT NULL,
                    alt_text VARCHAR,
                    caption VARCHAR,
                    sort_order INTEGER DEFAULT 0,
                    created_at DATETIME,
                    updated_at DATETIME,
                    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
                )
            ");
            DB::statement("INSERT INTO project_images_new SELECT * FROM project_images");
            DB::statement("DROP TABLE project_images");
            DB::statement("ALTER TABLE project_images_new RENAME TO project_images");
            DB::statement("PRAGMA foreign_keys=on");
        } else {
            // MySQL / MariaDB
            DB::statement("ALTER TABLE project_images MODIFY COLUMN type ENUM('featured','hero_desktop','hero_mobile','gallery','master_plan','layout_map','location_map','amenity','other') DEFAULT 'gallery'");
        }
    }

    public function down(): void
    {
        // Revert: remove 'amenity' — not critical, skip for safety
    }
};
