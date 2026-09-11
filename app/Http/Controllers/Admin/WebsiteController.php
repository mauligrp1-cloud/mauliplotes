<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Setting;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    // =========================================================================
    // SHARED SECTION UPDATE VALIDATION RULES
    // =========================================================================
    private function sectionValidationRules(): array
    {
        return [
            'title'                 => 'nullable|string|max:255',
            'subtitle'              => 'nullable|string|max:255',
            'content'               => 'nullable|string|max:10000',
            'image'                 => 'nullable|string|max:500',
            'secondary_image'       => 'nullable|string|max:500',
            'button_text'           => 'nullable|string|max:100',
            'button_url'            => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url'  => 'nullable|string|max:255',
            'sort_order'            => 'nullable|integer',
            'is_active'             => 'nullable',
            'options'               => 'nullable|array',
        ];
    }

    private function updateSectionFromRequest(Request $request, PageSection $section): void
    {
        $image = $request->input('image');
        if (is_string($image) && preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/(.+)$#', $image, $m)) {
            $image = '/storage/' . $m[3];
        }
        $secondaryImage = $request->input('secondary_image');
        if (is_string($secondaryImage) && preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/(.+)$#', $secondaryImage, $m)) {
            $secondaryImage = '/storage/' . $m[3];
        }

        $data = [
            'title'                 => $request->input('title'),
            'subtitle'              => $request->input('subtitle'),
            'content'               => $request->input('content'),
            'image'                 => $image,
            'secondary_image'       => $secondaryImage,
            'button_text'           => $request->input('button_text'),
            'button_url'            => $request->input('button_url'),
            'secondary_button_text' => $request->input('secondary_button_text'),
            'secondary_button_url'  => $request->input('secondary_button_url'),
            'sort_order'            => (int) ($request->input('sort_order', $section->sort_order)),
            'is_active'             => $request->boolean('is_active'),
        ];

        if ($request->has('options')) {
            $data['options'] = $request->input('options');
        }

        $section->update($data);
    }

    // =========================================================================
    // WEBSITE HUB INDEX
    // =========================================================================
    public function index()
    {
        $homePage    = Page::with('sections')->where('slug', 'home')->first();
        $aboutPage   = Page::with('sections')->where('slug', 'about')->first();
        $whyPage     = Page::with('sections')->where('slug', 'why-nagpur')->first();
        $nriPage     = Page::with('sections')->where('slug', 'nri')->first();
        $contactPage = Page::with('sections')->where('slug', 'contact')->first();
        $galleryPage = Page::with('sections')->where('slug', 'gallery')->first();

        $phone           = Setting::get('phone');
        $footerDisclaimer = Setting::get('footer_rera_disclaimer');

        $modules = [
            [
                'key'         => 'header',
                'name'        => 'Header & Navigation',
                'description' => 'Brand logos, contact shortcuts (Phone/WhatsApp), navigation menu links, and primary CTA button.',
                'status'      => !empty($phone) ? 'Configured' : 'Needs Review',
                'status_class'=> 'bg-success bg-opacity-10 text-success border-success',
                'route'       => route('admin.website.header'),
                'icon'        => 'bi-layout-text-window',
                'badge'       => 'Branding & Nav',
                'preview_url' => url('/'),
            ],
            [
                'key'         => 'homepage',
                'name'        => 'Homepage Sections',
                'description' => 'Hero slider, trust stats, featured projects, brand story, why choose us, locations, testimonials & lead CTA.',
                'status'      => ($homePage && $homePage->sections->count() > 0) ? ($homePage->sections->count() . ' Sections Active') : 'Not Seeded',
                'status_class'=> 'bg-primary bg-opacity-10 text-primary border-primary',
                'route'       => route('admin.website.homepage'),
                'icon'        => 'bi-house-door',
                'badge'       => 'Core Landing',
                'preview_url' => url('/'),
            ],
            [
                'key'         => 'about',
                'name'        => 'About Us Page',
                'description' => 'Company legacy, founding journey, vision, mission, core values, leadership team, and milestones.',
                'status'      => ($aboutPage && $aboutPage->is_published) ? 'Published' : 'Draft',
                'status_class'=> 'bg-success bg-opacity-10 text-success border-success',
                'route'       => route('admin.website.about'),
                'icon'        => 'bi-info-circle',
                'badge'       => 'Company Story',
                'preview_url' => url('/about'),
            ],
            [
                'key'         => 'contact',
                'name'        => 'Contact & Visit Booking',
                'description' => 'Corporate office address, interactive map, sales phone numbers, emails, and enquiry form.',
                'status'      => ($contactPage && $contactPage->is_published) ? 'Published' : 'Draft',
                'status_class'=> 'bg-success bg-opacity-10 text-success border-success',
                'route'       => route('admin.website.contact'),
                'icon'        => 'bi-envelope-at',
                'badge'       => 'Lead Gen',
                'preview_url' => url('/contact'),
            ],
            [
                'key'         => 'gallery',
                'name'        => 'Gallery Page',
                'description' => 'Gallery hero banner and gallery categories display settings.',
                'status'      => ($galleryPage && $galleryPage->is_published) ? 'Published' : 'Draft',
                'status_class'=> 'bg-secondary bg-opacity-10 text-secondary border-secondary',
                'route'       => route('admin.website.gallery'),
                'icon'        => 'bi-images',
                'badge'       => 'Media',
                'preview_url' => url('/gallery'),
            ],
            [
                'key'         => 'footer',
                'name'        => 'Footer & Compliance',
                'description' => 'Office addresses, social channels, quick links, MahaRERA compliance statements, and copyright notices.',
                'status'      => !empty($footerDisclaimer) ? 'Configured' : 'Needs Review',
                'status_class'=> 'bg-secondary bg-opacity-10 text-secondary border-secondary',
                'route'       => route('admin.website.footer'),
                'icon'        => 'bi-layout-sidebar-inset-reverse',
                'badge'       => 'Compliance',
                'preview_url' => url('/'),
            ],
        ];

        $pages = Page::with('sections')->get();
        $homePage = $pages->firstWhere('slug', 'home');

        // Structured section mapping for Home Page drawer (matching user specification)
        $homeSections = [
            ['name' => 'Hero Slider', 'key' => 'hero_slider', 'desc' => 'Main headline banners, CTA buttons & imagery', 'route' => route('admin.website.homepage') . '#sec-hero_slider'],
            ['name' => 'Trust Statistics', 'key' => 'trust_stats', 'desc' => 'Numbers: acres developed, plots delivered, happy families', 'route' => route('admin.website.homepage') . '#sec-trust_stats'],
            ['name' => 'Featured Projects', 'key' => 'featured_projects', 'desc' => 'High-growth plotted townships grid', 'route' => route('admin.website.homepage') . '#sec-featured_projects'],
            ['name' => 'About Section', 'key' => 'about_intro', 'desc' => 'Brand legacy & developer vision', 'route' => route('admin.website.homepage') . '#sec-about_intro'],
            ['name' => 'Why Choose Us', 'key' => 'why_choose_us', 'desc' => '6 pillars of developer trust', 'route' => route('admin.website.homepage') . '#sec-why_choose_us'],
            ['name' => 'Prime Locations', 'key' => 'prime_locations', 'desc' => 'Wardha Road, Jamtha, MIHAN growth corridors', 'route' => route('admin.website.homepage') . '#sec-prime_locations'],
            ['name' => 'Testimonials', 'key' => 'testimonials', 'desc' => 'Customer success stories & reviews', 'route' => route('admin.website.homepage') . '#sec-testimonials'],
            ['name' => 'Site Photography / Gallery', 'key' => 'gallery', 'desc' => 'On-site plotted township photos & lifestyle', 'route' => route('admin.website.homepage') . '#sec-gallery'],
            ['name' => 'Blog & Insights', 'key' => 'blogs', 'desc' => 'Nagpur real-estate market guides & news', 'route' => route('admin.website.homepage') . '#sec-blogs'],
            ['name' => 'Final CTA Banner', 'key' => 'final_cta', 'desc' => 'Free site visit consultation callout', 'route' => route('admin.website.homepage') . '#sec-final_cta'],
        ];

        return view('admin.website.index', compact('modules', 'pages', 'homePage', 'homeSections'));
    }

    // =========================================================================
    // AJAX — CLEAR SYSTEM CACHE
    // =========================================================================
    public function clearCache(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            return response()->json([
                'success' => true,
                'message' => 'System & view cache cleared successfully!'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================================================================
    // STORE NEW PAGE
    // =========================================================================
    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|max:255|unique:pages,slug',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published'     => 'nullable',
        ]);

        $slug = \Illuminate\Support\Str::slug($validated['slug']);

        $page = Page::create([
            'title'            => $validated['title'],
            'slug'             => $slug,
            'meta_title'       => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? null,
            'is_published'     => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.website.index')->with('success', 'Page "' . $page->title . '" created successfully!');
    }

    // =========================================================================
    // DESTROY PAGE
    // =========================================================================
    public function destroyPage(Request $request, $page)
    {
        if (!$page instanceof Page) {
            $page = Page::where('id', $page)->orWhere('slug', $page)->firstOrFail();
        }

        if ($page->slug === 'home') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The Home page is a core system landing page and cannot be deleted.'
                ], 403);
            }
            return redirect()->back()->with('error', 'The Home page is a core system landing page and cannot be deleted.');
        }

        $title = $page->title;

        // Delete associated sections
        $page->sections()->delete();

        // Delete page
        $page->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Page "' . $title . '" has been deleted successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Page "' . $title . '" has been deleted successfully!');
    }

    // =========================================================================
    // AJAX — SECTION TOGGLE (on / off)
    // =========================================================================
    public function toggleSection(Request $request, PageSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $section->is_active,
            'message'   => $section->section_name . ' has been ' . ($section->is_active ? 'enabled' : 'disabled'),
        ]);
    }

    // =========================================================================
    // AJAX — QUICK UPDATE (for live preview inline edits)
    // =========================================================================
    public function quickUpdateSection(Request $request, PageSection $section)
    {
        $request->validate([
            'field' => 'required|in:title,subtitle,content,image,button_text,button_url,secondary_button_text,secondary_button_url',
            'value' => 'nullable|string|max:5000',
        ]);

        $section->update([$request->field => $request->value]);

        return response()->json(['success' => true]);
    }

    // =========================================================================
    // HEADER CMS
    // =========================================================================
    public function header()
    {
        $settings = [
            'main_logo'          => Setting::get('main_logo', ''),
            'light_logo'         => Setting::get('light_logo', ''),
            'mobile_logo'        => Setting::get('mobile_logo', ''),
            'phone'              => Setting::get('phone', '+91 98765 43210'),
            'whatsapp'           => Setting::get('whatsapp', '+91 98765 43210'),
            'email'              => Setting::get('email', 'sales@mauliinfra.com'),
            'header_cta_text'    => Setting::get('header_cta_text', 'Book Site Visit'),
            'header_cta_url'     => Setting::get('header_cta_url', '/contact'),
            'header_cta_show'    => Setting::get('header_cta_show', '1'),
            'header_sticky'      => Setting::get('header_sticky', '1'),
            'header_transparent' => Setting::get('header_transparent', '1'),
        ];

        $navMenuJson = Setting::get('header_nav_menu');
        $navMenu = $navMenuJson ? json_decode($navMenuJson, true) : [
            ['label' => 'Home',     'url' => '/',         'order' => 1, 'target' => '_self', 'is_active' => true],
            ['label' => 'Projects', 'url' => '/projects', 'order' => 2, 'target' => '_self', 'is_active' => true],
            ['label' => 'About Us', 'url' => '/about',    'order' => 3, 'target' => '_self', 'is_active' => true],
            ['label' => 'Blog',     'url' => '/blog',     'order' => 4, 'target' => '_self', 'is_active' => true],
            ['label' => 'Contact',  'url' => '/contact',  'order' => 5, 'target' => '_self', 'is_active' => true],
        ];

        return view('admin.website.header', compact('settings', 'navMenu'));
    }

    public function updateHeader(Request $request)
    {
        $request->validate([
            'main_logo'          => 'nullable|string|max:500',
            'light_logo'         => 'nullable|string|max:500',
            'mobile_logo'        => 'nullable|string|max:500',
            'header_logo_height' => 'nullable|integer|min:20|max:160',
            'phone'              => 'nullable|string|max:50',
            'whatsapp'           => 'nullable|string|max:50',
            'email'              => 'nullable|email|max:100',
            'header_cta_text'    => 'nullable|string|max:100',
            'header_cta_url'     => 'nullable|string|max:255',
            'header_cta_show'    => 'nullable|boolean',
            'header_sticky'      => 'nullable|boolean',
            'header_transparent' => 'nullable|boolean',
            'menu'               => 'nullable|array',
            'menu.*.label'       => 'required|string|max:100',
            'menu.*.url'         => 'required|string|max:255',
            'menu.*.order'       => 'nullable|integer',
            'menu.*.target'      => 'nullable|string|in:_self,_blank',
            'menu.*.is_active'   => 'nullable',
        ]);

        Setting::set('main_logo',          $request->input('main_logo', ''),           'branding');
        Setting::set('light_logo',         $request->input('light_logo', ''),          'branding');
        Setting::set('mobile_logo',        $request->input('mobile_logo', ''),         'branding');
        Setting::set('header_logo_height', (string) $request->input('header_logo_height', '55'), 'branding');
        Setting::set('phone',              $request->input('phone', ''),               'contact');
        Setting::set('whatsapp',           $request->input('whatsapp', ''),            'contact');
        Setting::set('email',              $request->input('email', ''),               'contact');
        Setting::set('header_cta_text',    $request->input('header_cta_text', 'Book Site Visit'), 'general');
        Setting::set('header_cta_url',     $request->input('header_cta_url', '/contact'),         'general');
        Setting::set('header_cta_show',    $request->boolean('header_cta_show') ? '1' : '0',      'general');
        Setting::set('header_sticky',      $request->boolean('header_sticky') ? '1' : '0',        'general');
        Setting::set('header_transparent', $request->boolean('header_transparent') ? '1' : '0',   'general');

        if ($request->has('menu') && is_array($request->input('menu'))) {
            $menuItems = [];
            foreach ($request->input('menu') as $item) {
                $menuItems[] = [
                    'label'     => $item['label'] ?? '',
                    'url'       => $item['url'] ?? '',
                    'order'     => (int) ($item['order'] ?? 0),
                    'target'    => ($item['target'] ?? '_self') === '_blank' ? '_blank' : '_self',
                    'is_active' => isset($item['is_active']) && $item['is_active'] ? true : false,
                ];
            }
            usort($menuItems, fn($a, $b) => $a['order'] <=> $b['order']);
            Setting::set('header_nav_menu', json_encode($menuItems), 'general');
        }

        return redirect()->route('admin.website.header')->with('success', 'Header and navigation settings updated successfully.');
    }

    // =========================================================================
    // HOMEPAGE CMS
    // =========================================================================
    public function homepage()
    {
        $page     = Page::with('sections')->where('slug', 'home')->firstOrFail();
        $sections = $page->sections->keyBy('section_key');

        return view('admin.website.homepage', compact('page', 'sections'));
    }

    public function updateHomepageSection(Request $request, PageSection $section)
    {
        $request->validate($this->sectionValidationRules());
        $this->updateSectionFromRequest($request, $section);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "'{$section->section_name}' updated."]);
        }

        return redirect()->route('admin.website.homepage')
            ->with('success', "Section '{$section->section_name}' updated successfully.");
    }

    // =========================================================================
    // ABOUT PAGE CMS
    // =========================================================================
    public function about()
    {
        $page     = Page::with('sections')->where('slug', 'about')->firstOrFail();
        $sections = $page->sections->sortBy('sort_order')->keyBy('section_key');

        return view('admin.website.about', compact('page', 'sections'));
    }

    public function updateAboutSection(Request $request, PageSection $section)
    {
        $request->validate($this->sectionValidationRules());
        $this->updateSectionFromRequest($request, $section);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.website.about')
            ->with('success', "Section '{$section->section_name}' updated successfully.");
    }

    // =========================================================================
    // WHY NAGPUR CMS
    // =========================================================================
    public function whyNagpur()
    {
        $page     = Page::with('sections')->where('slug', 'why-nagpur')->firstOrFail();
        $sections = $page->sections->sortBy('sort_order')->keyBy('section_key');

        return view('admin.website.why-nagpur', compact('page', 'sections'));
    }

    public function updateWhyNagpurSection(Request $request, PageSection $section)
    {
        $request->validate($this->sectionValidationRules());
        $this->updateSectionFromRequest($request, $section);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.website.why-nagpur')
            ->with('success', "Section '{$section->section_name}' updated successfully.");
    }

    // =========================================================================
    // NRI PAGE CMS
    // =========================================================================
    public function nri()
    {
        $page     = Page::with('sections')->where('slug', 'nri')->firstOrFail();
        $sections = $page->sections->sortBy('sort_order')->keyBy('section_key');

        return view('admin.website.nri', compact('page', 'sections'));
    }

    public function updateNriSection(Request $request, PageSection $section)
    {
        $request->validate($this->sectionValidationRules());
        $this->updateSectionFromRequest($request, $section);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.website.nri')
            ->with('success', "Section '{$section->section_name}' updated successfully.");
    }

    // =========================================================================
    // CONTACT PAGE CMS
    // =========================================================================
    public function contact()
    {
        $page     = Page::with('sections')->where('slug', 'contact')->firstOrFail();
        $sections = $page->sections->sortBy('sort_order')->keyBy('section_key');

        return view('admin.website.contact', compact('page', 'sections'));
    }

    public function updateContactSection(Request $request, PageSection $section)
    {
        $request->validate($this->sectionValidationRules());
        $this->updateSectionFromRequest($request, $section);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.website.contact')
            ->with('success', "Section '{$section->section_name}' updated successfully.");
    }

    // =========================================================================
    // GALLERY PAGE CMS
    // =========================================================================
    public function galleryCms()
    {
        $page     = Page::with('sections')->where('slug', 'gallery')->firstOrFail();
        $sections = $page->sections->sortBy('sort_order')->keyBy('section_key');

        return view('admin.website.gallery', compact('page', 'sections'));
    }

    public function updateGallerySection(Request $request, PageSection $section)
    {
        $request->validate($this->sectionValidationRules());
        $this->updateSectionFromRequest($request, $section);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.website.gallery')
            ->with('success', "Section '{$section->section_name}' updated successfully.");
    }

    // =========================================================================
    // FOOTER CMS
    // =========================================================================
    public function footer()
    {
        $settings = [
            'footer_about'           => Setting::get('footer_about', ''),
            'footer_copyright'       => Setting::get('footer_copyright', '© ' . date('Y') . ' Mauli Infra. All Rights Reserved.'),
            'footer_rera_disclaimer' => Setting::get('footer_rera_disclaimer', ''),
            'social_facebook'        => Setting::get('social_facebook', ''),
            'social_instagram'       => Setting::get('social_instagram', ''),
            'social_youtube'         => Setting::get('social_youtube', ''),
            'social_linkedin'        => Setting::get('social_linkedin', ''),
        ];

        return view('admin.website.footer', compact('settings'));
    }

    public function updateFooter(Request $request)
    {
        Setting::set('footer_about',           $request->input('footer_about', ''),           'general');
        Setting::set('footer_copyright',       $request->input('footer_copyright', ''),       'general');
        Setting::set('footer_rera_disclaimer', $request->input('footer_rera_disclaimer', ''), 'general');
        Setting::set('social_facebook',        $request->input('social_facebook', ''),        'social');
        Setting::set('social_instagram',       $request->input('social_instagram', ''),       'social');
        Setting::set('social_youtube',         $request->input('social_youtube', ''),         'social');
        Setting::set('social_linkedin',        $request->input('social_linkedin', ''),        'social');

        return redirect()->route('admin.website.footer')
            ->with('success', 'Footer and compliance settings updated successfully.');
    }
}
