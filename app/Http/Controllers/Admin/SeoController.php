<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:seo.view')->only(['index']);
        $this->middleware('permission:seo.edit')->only(['update']);
    }

    /**
     * Display SEO and Webmaster Management dashboard
     */
    public function index()
    {
        $settings = [
            'default_meta_title' => Setting::get('default_meta_title', 'Mauli Infra | Premium Plotted Developments in Nagpur'),
            'default_meta_description' => Setting::get('default_meta_description', 'MahaRERA registered NMRDA/NIT approved residential & commercial plots in prime Nagpur growth corridors.'),
            'default_meta_keywords' => Setting::get('default_meta_keywords', 'plots in nagpur, buy plot nagpur, residential plots wardha road, commercial land nagpur, mauli infra, plots near mihan'),
            'google_site_verification' => Setting::get('google_site_verification', ''),
            'bing_site_verification' => Setting::get('bing_site_verification', ''),
            'google_analytics_id' => Setting::get('google_analytics_id', ''),
            'google_tag_manager_id' => Setting::get('google_tag_manager_id', ''),
            'facebook_pixel_id' => Setting::get('facebook_pixel_id', ''),
            'custom_header_scripts' => Setting::get('custom_header_scripts', ''),
            'custom_body_scripts' => Setting::get('custom_body_scripts', ''),
            'sitemap_url' => url('/sitemap.xml'),
            'robots_url' => url('/robots.txt'),
        ];

        return view('admin.seo.index', compact('settings'));
    }

    /**
     * Update SEO and Webmaster settings
     */
    public function update(Request $request)
    {
        // Sanitize and save meta settings
        Setting::set('default_meta_title', $request->input('default_meta_title', ''), 'seo');
        Setting::set('default_meta_description', $request->input('default_meta_description', ''), 'seo');
        Setting::set('default_meta_keywords', $request->input('default_meta_keywords', ''), 'seo');

        // Webmaster & Verification tags
        // If user pastes full <meta name="google-site-verification" content="XYZ" />, extract content or save raw
        $gsc = trim($request->input('google_site_verification', ''));
        if (preg_match('/content=[\'"]([^\'"]+)[\'"]/i', $gsc, $matches)) {
            $gsc = $matches[1];
        }
        Setting::set('google_site_verification', $gsc, 'seo');

        $bing = trim($request->input('bing_site_verification', ''));
        if (preg_match('/content=[\'"]([^\'"]+)[\'"]/i', $bing, $matches)) {
            $bing = $matches[1];
        }
        Setting::set('bing_site_verification', $bing, 'seo');

        // Analytics & Tracking IDs
        Setting::set('google_analytics_id', trim($request->input('google_analytics_id', '')), 'analytics');
        Setting::set('google_tag_manager_id', trim($request->input('google_tag_manager_id', '')), 'analytics');
        Setting::set('facebook_pixel_id', trim($request->input('facebook_pixel_id', '')), 'analytics');

        // Custom Raw Scripts (Header and Body)
        Setting::set('custom_header_scripts', $request->input('custom_header_scripts', ''), 'seo');
        Setting::set('custom_body_scripts', $request->input('custom_body_scripts', ''), 'seo');

        return redirect()->route('admin.seo.index')->with('success', 'SEO tags and analytics settings updated successfully.');
    }
}
