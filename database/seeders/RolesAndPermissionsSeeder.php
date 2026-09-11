<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Define all granular permissions
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'description' => 'Access admin dashboard metrics'],

            // Website CMS
            ['name' => 'View Website CMS', 'slug' => 'website.view', 'description' => 'View header, footer, and page sections'],
            ['name' => 'Edit Website CMS', 'slug' => 'website.edit', 'description' => 'Modify header, footer, homepage, and page sections'],

            // Projects
            ['name' => 'View Projects', 'slug' => 'projects.view', 'description' => 'View real-estate projects listing and details'],
            ['name' => 'Create Projects', 'slug' => 'projects.create', 'description' => 'Add new plotted projects'],
            ['name' => 'Edit Projects', 'slug' => 'projects.edit', 'description' => 'Modify existing projects and plot configurations'],
            ['name' => 'Delete Projects', 'slug' => 'projects.delete', 'description' => 'Delete or archive projects'],

            // Locations
            ['name' => 'View Locations', 'slug' => 'locations.view', 'description' => 'View project locations'],
            ['name' => 'Create Locations', 'slug' => 'locations.create', 'description' => 'Add new location hubs'],
            ['name' => 'Edit Locations', 'slug' => 'locations.edit', 'description' => 'Edit location details and connectivity landmarks'],
            ['name' => 'Delete Locations', 'slug' => 'locations.delete', 'description' => 'Remove locations'],

            // Content (Testimonials, Team, Gallery)
            ['name' => 'View Content', 'slug' => 'content.view', 'description' => 'View testimonials, team, and galleries'],
            ['name' => 'Edit Content', 'slug' => 'content.edit', 'description' => 'Manage testimonials, team members, and gallery photos'],

            // Blog
            ['name' => 'View Blog', 'slug' => 'blog.view', 'description' => 'View blog articles and categories'],
            ['name' => 'Create Blog', 'slug' => 'blog.create', 'description' => 'Write new blog posts'],
            ['name' => 'Edit Blog', 'slug' => 'blog.edit', 'description' => 'Edit published and draft blog posts'],
            ['name' => 'Delete Blog', 'slug' => 'blog.delete', 'description' => 'Delete blog posts'],

            // Leads CRM
            ['name' => 'View Leads', 'slug' => 'leads.view', 'description' => 'View incoming buyer leads'],
            ['name' => 'Edit Leads', 'slug' => 'leads.edit', 'description' => 'Update lead status and add follow-up notes'],
            ['name' => 'Assign Leads', 'slug' => 'leads.assign', 'description' => 'Assign leads to sales executives'],
            ['name' => 'Delete Leads', 'slug' => 'leads.delete', 'description' => 'Remove or purge lead records'],

            // Site Visits
            ['name' => 'View Site Visits', 'slug' => 'site_visits.view', 'description' => 'View booked site visits schedule'],
            ['name' => 'Edit Site Visits', 'slug' => 'site_visits.edit', 'description' => 'Update site visit status, reschedule, and assign'],

            // SEO Management
            ['name' => 'View SEO', 'slug' => 'seo.view', 'description' => 'View SEO metadata, sitemap status, and redirects'],
            ['name' => 'Edit SEO', 'slug' => 'seo.edit', 'description' => 'Modify meta tags, OG tags, and 301 redirects'],

            // Media Library
            ['name' => 'View Media', 'slug' => 'media.view', 'description' => 'Browse central media library'],
            ['name' => 'Upload Media', 'slug' => 'media.upload', 'description' => 'Upload property photos, maps, and brochures'],
            ['name' => 'Delete Media', 'slug' => 'media.delete', 'description' => 'Delete files from media storage'],

            // Users & RBAC
            ['name' => 'View Users', 'slug' => 'users.view', 'description' => 'View admin user accounts'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'description' => 'Create new staff and admin accounts'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'description' => 'Edit user profiles, roles, and permissions'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'description' => 'Deactivate or delete user accounts'],

            // Settings
            ['name' => 'View Settings', 'slug' => 'settings.view', 'description' => 'View global website settings'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'description' => 'Modify branding, contact info, and analytics tags'],
        ];

        $permissionModels = [];
        foreach ($permissions as $perm) {
            $permissionModels[$perm['slug']] = Permission::updateOrCreate(
                ['slug' => $perm['slug']],
                ['name' => $perm['name'], 'description' => $perm['description']]
            );
        }

        // 2. Define Roles
        $roles = [
            'super-admin' => [
                'name' => 'Super Admin',
                'description' => 'Full unconstrained administrative access across all modules.',
                'permissions' => array_keys($permissionModels),
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'Comprehensive administration of website, projects, CRM, and content.',
                'permissions' => [
                    'dashboard.view',
                    'website.view', 'website.edit',
                    'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
                    'locations.view', 'locations.create', 'locations.edit', 'locations.delete',
                    'content.view', 'content.edit',
                    'blog.view', 'blog.create', 'blog.edit', 'blog.delete',
                    'leads.view', 'leads.edit', 'leads.assign', 'leads.delete',
                    'site_visits.view', 'site_visits.edit',
                    'seo.view', 'seo.edit',
                    'media.view', 'media.upload', 'media.delete',
                    'users.view', 'users.create', 'users.edit',
                    'settings.view', 'settings.edit',
                ],
            ],
            'content-editor' => [
                'name' => 'Content Editor',
                'description' => 'Manages website content, pages, projects, blogs, and media assets.',
                'permissions' => [
                    'dashboard.view',
                    'website.view', 'website.edit',
                    'projects.view', 'projects.create', 'projects.edit',
                    'locations.view', 'locations.edit',
                    'content.view', 'content.edit',
                    'blog.view', 'blog.create', 'blog.edit', 'blog.delete',
                    'media.view', 'media.upload',
                ],
            ],
            'seo-manager' => [
                'name' => 'SEO Manager',
                'description' => 'Manages metadata, redirects, sitemaps, and blog optimization.',
                'permissions' => [
                    'dashboard.view',
                    'seo.view', 'seo.edit',
                    'blog.view', 'blog.edit',
                    'projects.view',
                    'locations.view',
                    'website.view',
                ],
            ],
            'sales-manager' => [
                'name' => 'Sales Manager',
                'description' => 'Manages leads assignment, customer follow-ups, and site visits.',
                'permissions' => [
                    'dashboard.view',
                    'leads.view', 'leads.edit', 'leads.assign',
                    'site_visits.view', 'site_visits.edit',
                    'projects.view',
                    'locations.view',
                ],
            ],
            'sales-executive' => [
                'name' => 'Sales Executive',
                'description' => 'Handles assigned customer enquiries and site visits.',
                'permissions' => [
                    'dashboard.view',
                    'leads.view', 'leads.edit',
                    'site_visits.view', 'site_visits.edit',
                    'projects.view',
                ],
            ],
        ];

        $roleModels = [];
        foreach ($roles as $slug => $data) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                ['name' => $data['name'], 'description' => $data['description']]
            );
            $roleModels[$slug] = $role;

            $permIds = [];
            foreach ($data['permissions'] as $permSlug) {
                if (isset($permissionModels[$permSlug])) {
                    $permIds[] = $permissionModels[$permSlug]->id;
                }
            }
            $role->permissions()->sync($permIds);
        }

        // 3. Create or update Default Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@mauliproperties.test'],
            [
                'name' => 'Mauli Super Admin',
                'password' => Hash::make('Mauli@12345'),
            ]
        );

        $superAdmin->roles()->sync([
            $roleModels['super-admin']->id,
            $roleModels['admin']->id,
        ]);
    }
}
