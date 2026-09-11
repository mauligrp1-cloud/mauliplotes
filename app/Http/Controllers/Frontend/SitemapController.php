<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap with all projects, locations, blog posts, and pages
     */
    public function sitemap()
    {
        $projects = Project::where('is_published', true)->latest('updated_at')->get();
        $locations = Location::where('is_active', true)->latest('updated_at')->get();
        $posts = BlogPost::where('status', 'published')->latest('updated_at')->get();

        $staticPages = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['loc' => url('/projects'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['loc' => url('/about'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
            ['loc' => url('/why-nagpur'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
            ['loc' => url('/nri'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
            ['loc' => url('/gallery'), 'priority' => '0.7', 'changefreq' => 'weekly', 'lastmod' => now()->toAtomString()],
            ['loc' => url('/blog'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->toAtomString()],
            ['loc' => url('/contact'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static Pages
        foreach ($staticPages as $page) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$page['loc']}</loc>\n";
            $xml .= "    <lastmod>{$page['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$page['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$page['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        // Projects
        foreach ($projects as $proj) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . url('/projects/' . $proj->slug) . "</loc>\n";
            $xml .= "    <lastmod>" . $proj->updated_at->toAtomString() . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.9</priority>\n";
            $xml .= "  </url>\n";
        }

        // Locations
        foreach ($locations as $loc) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . url('/projects?location=' . $loc->slug) . "</loc>\n";
            $xml .= "    <lastmod>" . $loc->updated_at->toAtomString() . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        // Blog Posts
        foreach ($posts as $post) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . url('/blog/' . $post->slug) . "</loc>\n";
            $xml .= "    <lastmod>" . $post->updated_at->toAtomString() . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Generate robots.txt
     */
    public function robots()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /login\n\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($robots, 200, ['Content-Type' => 'text/plain']);
    }
}
