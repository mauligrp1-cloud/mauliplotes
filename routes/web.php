<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\SiteVisitController as AdminSiteVisitController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\RedirectController as AdminRedirectController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SeoController;

use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProjectController as FrontendProjectController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\LeadController as FrontendLeadController;
use App\Http\Controllers\Frontend\SitemapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Frontend Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/projects', [FrontendProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [FrontendProjectController::class, 'show'])->name('projects.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/why-nagpur', [PageController::class, 'whyNagpur'])->name('why-nagpur');
Route::get('/why-invest-in-nagpur', [PageController::class, 'whyNagpur']);
Route::get('/nri', [PageController::class, 'nri'])->name('nri');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/team', [PageController::class, 'team'])->name('team');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-conditions', [PageController::class, 'termsConditions'])->name('terms-conditions');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');

// Public Blog Routes
Route::get('/blog', [FrontendBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [FrontendBlogController::class, 'show'])->name('blog.show');

// Public Enquiry & Site Visit Submission (Rate-limited to 5 submissions per minute per IP)
Route::post('/enquiry', [FrontendLeadController::class, 'store'])->name('enquiry.store')->middleware('throttle:5,1');

// SEO XML Sitemap & Robots.txt
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Dedicated Administrator Access Point (Obfuscated & Hardened)
Route::get('/mauli-log', [AuthController::class, 'showLogin'])->name('login');
Route::post('/mauli-log', [AuthController::class, 'login'])->name('login.store');

// Disallow public exposure of standard login routes
Route::get('/login', function () { return redirect('/'); });
Route::get('/admin/login', function () { return redirect('/'); })->name('admin.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Portal Routes (protected by auth + admin middleware)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Website Management CMS Module Hub
    Route::get('/website', [WebsiteController::class, 'index'])->name('admin.website.index')->middleware('permission:website.view');

    // AJAX Section Toggle (on/off) — works for any PageSection
    Route::post('/website/section/{section}/toggle', [WebsiteController::class, 'toggleSection'])->name('admin.website.section.toggle')->middleware('permission:website.edit');

    // AJAX Section Quick-Update (title, subtitle, content only) — used by Live Preview
    Route::post('/website/section/{section}/quick-update', [WebsiteController::class, 'quickUpdateSection'])->name('admin.website.section.quick-update')->middleware('permission:website.edit');

    // Header CMS
    Route::get('/website/header', [WebsiteController::class, 'header'])->name('admin.website.header')->middleware('permission:website.view');
    Route::post('/website/header', [WebsiteController::class, 'updateHeader'])->name('admin.website.header.update')->middleware('permission:website.edit');

    // Homepage CMS
    Route::get('/website/homepage', [WebsiteController::class, 'homepage'])->name('admin.website.homepage')->middleware('permission:website.view');
    Route::post('/website/homepage/section/{section}', [WebsiteController::class, 'updateHomepageSection'])->name('admin.website.homepage.section.update')->middleware('permission:website.edit');

    // About CMS
    Route::get('/website/about', [WebsiteController::class, 'about'])->name('admin.website.about')->middleware('permission:website.view');
    Route::post('/website/about/section/{section}', [WebsiteController::class, 'updateAboutSection'])->name('admin.website.about.section.update')->middleware('permission:website.edit');

    // Why Nagpur CMS
    Route::get('/website/why-nagpur', [WebsiteController::class, 'whyNagpur'])->name('admin.website.why-nagpur')->middleware('permission:website.view');
    Route::post('/website/why-nagpur/section/{section}', [WebsiteController::class, 'updateWhyNagpurSection'])->name('admin.website.why-nagpur.section.update')->middleware('permission:website.edit');

    // NRI CMS
    Route::get('/website/nri', [WebsiteController::class, 'nri'])->name('admin.website.nri')->middleware('permission:website.view');
    Route::post('/website/nri/section/{section}', [WebsiteController::class, 'updateNriSection'])->name('admin.website.nri.section.update')->middleware('permission:website.edit');

    // Contact CMS
    Route::get('/website/contact', [WebsiteController::class, 'contact'])->name('admin.website.contact')->middleware('permission:website.view');
    Route::post('/website/contact/section/{section}', [WebsiteController::class, 'updateContactSection'])->name('admin.website.contact.section.update')->middleware('permission:website.edit');

    // Gallery CMS
    Route::get('/website/gallery', [WebsiteController::class, 'galleryCms'])->name('admin.website.gallery')->middleware('permission:website.view');
    Route::post('/website/gallery/section/{section}', [WebsiteController::class, 'updateGallerySection'])->name('admin.website.gallery.section.update')->middleware('permission:website.edit');

    // Investment redirect (legacy)
    Route::get('/website/investment', [WebsiteController::class, 'whyNagpur'])->name('admin.website.investment')->middleware('permission:website.view');

    // Footer CMS
    Route::get('/website/footer', [WebsiteController::class, 'footer'])->name('admin.website.footer')->middleware('permission:website.view');
    Route::post('/website/footer', [WebsiteController::class, 'updateFooter'])->name('admin.website.footer.update')->middleware('permission:website.edit');
    
    // Central Media Library
    Route::get('/media', [MediaController::class, 'index'])->name('admin.media.index')->middleware('permission:media.view');
    Route::post('/media', [MediaController::class, 'store'])->name('admin.media.store')->middleware('permission:media.upload');
    Route::put('/media/{media}', [MediaController::class, 'update'])->name('admin.media.update')->middleware('permission:media.upload');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('admin.media.destroy')->middleware('permission:media.delete');
    Route::get('/uploads', [MediaController::class, 'index'])->name('admin.uploads.index')->middleware('permission:media.view');

    // Projects CRUD, Sequence Reordering & 1-Click Duplicate
    Route::post('/projects/reorder', [ProjectController::class, 'reorder'])->name('admin.projects.reorder')->middleware('permission:projects.edit');
    Route::resource('projects', ProjectController::class)->names('admin.projects');
    Route::post('/projects/{project}/duplicate', [ProjectController::class, 'duplicate'])->name('admin.projects.duplicate')->middleware('permission:projects.create');

    // Project Images AJAX (amenity/gallery images via project_images table)
    Route::post('/projects/{project}/images', [ProjectController::class, 'storeImage'])->name('admin.projects.images.store');
    Route::match(['put', 'patch', 'post'], '/projects/{project}/images/{image}', [ProjectController::class, 'updateImage'])->name('admin.projects.images.update');
    Route::delete('/projects/{project}/images/{image}', [ProjectController::class, 'destroyImage'])->name('admin.projects.images.destroy');


    // Amenities CRUD
    Route::resource('amenities', AmenityController::class)->except(['show', 'create', 'edit'])->names('admin.amenities');

    // Strategic Locations CRUD
    Route::resource('locations', LocationController::class)->names('admin.locations');

    // Content: Testimonials, Team, Gallery, Blogs
    Route::resource('testimonials', TestimonialController::class)->names('admin.testimonials');
    Route::resource('team', TeamController::class)->names('admin.team');

    // Gallery — full CMS routes
    Route::get('/gallery', [GalleryController::class, 'index'])->name('admin.gallery.index');
    Route::post('/gallery/categories', [GalleryController::class, 'storeCategory'])->name('admin.gallery.categories.store');
    Route::delete('/gallery/categories/{category}', [GalleryController::class, 'destroyCategory'])->name('admin.gallery.categories.destroy');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('admin.gallery.store');
    Route::post('/gallery/bulk', [GalleryController::class, 'bulkStore'])->name('admin.gallery.bulk');
    Route::put('/gallery/{galleryItem}', [GalleryController::class, 'update'])->name('admin.gallery.update');
    Route::post('/gallery/{galleryItem}/toggle', [GalleryController::class, 'toggleActive'])->name('admin.gallery.toggle');
    Route::post('/gallery/reorder', [GalleryController::class, 'reorder'])->name('admin.gallery.reorder');
    Route::delete('/gallery/{galleryItem}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');

    Route::resource('blogs', AdminBlogController::class)->names('admin.blogs');

    // CRM Leads & Site Visits
    Route::get('/leads/export/csv', [AdminLeadController::class, 'exportCsv'])->name('admin.leads.export')->middleware('permission:leads.export');
    Route::post('/leads/{lead}/notes', [AdminLeadController::class, 'addNote'])->name('admin.leads.notes.store')->middleware('permission:leads.edit');
    Route::resource('leads', AdminLeadController::class)->names('admin.leads');
    Route::resource('site-visits', AdminSiteVisitController::class)->names('admin.site-visits');

    // Clear System Cache
    Route::post('/clear-cache', [WebsiteController::class, 'clearCache'])->name('admin.clear-cache');

    // Global Settings & SEO 301/302 Redirects
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index')->middleware('permission:settings.view');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update')->middleware('permission:settings.edit');
    Route::get('/pages', [WebsiteController::class, 'index'])->name('admin.pages.index')->middleware('permission:website.view');
    Route::post('/pages', [WebsiteController::class, 'storePage'])->name('admin.pages.store')->middleware('permission:website.edit');
    Route::delete('/pages/{page}', [WebsiteController::class, 'destroyPage'])->name('admin.pages.destroy')->middleware('permission:website.edit');
    Route::delete('/website/pages/{page}', [WebsiteController::class, 'destroyPage'])->name('admin.website.pages.destroy')->middleware('permission:website.edit');
    Route::get('/seo', [SeoController::class, 'index'])->name('admin.seo.index')->middleware('permission:seo.view');
    Route::post('/seo', [SeoController::class, 'update'])->name('admin.seo.update')->middleware('permission:seo.edit');
    Route::resource('users', AdminUserController::class)->names('admin.users');
    Route::resource('seo/redirects', AdminRedirectController::class)->names('admin.seo.redirects');
});
