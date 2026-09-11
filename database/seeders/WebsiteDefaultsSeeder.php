<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class WebsiteDefaultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // =====================================================================
        // 1. Global Website Settings
        // =====================================================================
        $settings = [
            'company_name'           => ['value' => 'Mauli Infra', 'group' => 'branding', 'desc' => 'Company Legal & Brand Name'],
            'tagline'                => ['value' => 'Your Land — A Brighter Tomorrow', 'group' => 'branding', 'desc' => 'Brand Tagline'],
            'main_logo'              => ['value' => '', 'group' => 'branding', 'desc' => 'Primary Brand Logo URL'],
            'light_logo'             => ['value' => '', 'group' => 'branding', 'desc' => 'Light Logo for Dark Backgrounds'],
            'favicon'                => ['value' => '', 'group' => 'branding', 'desc' => 'Website Favicon Icon URL'],

            'phone'                  => ['value' => '+91 70303 05555', 'group' => 'contact', 'desc' => 'Primary Phone Number'],
            'whatsapp'               => ['value' => '+91 70303 05555', 'group' => 'contact', 'desc' => 'WhatsApp Number for Inquiries'],
            'email'                  => ['value' => 'sales@mauliinfra.com', 'group' => 'contact', 'desc' => 'Official Inquiries Email Address'],
            'office_address'         => ['value' => 'Prince Castle, Plot No. 105, Opp. Madhav Netralay, Gajanan Nagar, Wardha Road, Nagpur, Maharashtra - 440015', 'group' => 'contact', 'desc' => 'Corporate Head Office Address'],
            'google_maps_embed'      => ['value' => '', 'group' => 'contact', 'desc' => 'Google Maps Embed Iframe URL'],

            'social_facebook'        => ['value' => 'https://facebook.com/mauliinfra', 'group' => 'social', 'desc' => 'Facebook Page URL'],
            'social_instagram'       => ['value' => 'https://instagram.com/mauliinfra', 'group' => 'social', 'desc' => 'Instagram Profile URL'],
            'social_youtube'         => ['value' => 'https://youtube.com/@mauliinfra', 'group' => 'social', 'desc' => 'YouTube Channel URL'],
            'social_linkedin'        => ['value' => 'https://linkedin.com/company/mauliinfra', 'group' => 'social', 'desc' => 'LinkedIn Profile URL'],

            'header_cta_text'        => ['value' => 'Book Site Visit', 'group' => 'general', 'desc' => 'Header CTA Button Text'],
            'header_cta_url'         => ['value' => '/contact', 'group' => 'general', 'desc' => 'Header CTA Destination URL'],
            'header_cta_show'        => ['value' => '1', 'group' => 'general', 'desc' => 'Show Header CTA Button (1 or 0)'],
            'header_sticky'          => ['value' => '1', 'group' => 'general', 'desc' => 'Sticky Header Enabled (1 or 0)'],
            'header_transparent'     => ['value' => '1', 'group' => 'general', 'desc' => 'Transparent Header over Hero'],
            'header_nav_menu'        => ['value' => json_encode([
                ['label' => 'Home',     'url' => '/',         'is_active' => true],
                ['label' => 'Projects', 'url' => '/projects', 'is_active' => true],
                ['label' => 'About Us', 'url' => '/about',    'is_active' => true],
                ['label' => 'Blog',     'url' => '/blog',     'is_active' => true],
                ['label' => 'Contact',  'url' => '/contact',  'is_active' => true],
            ]), 'group' => 'general', 'desc' => 'Header Navigation Menu (JSON array)'],

            'footer_about'           => ['value' => 'Nagpur\'s most trusted land developer. Premium plotted developments across Nagpur. 5,000+ happy families, 500+ acres delivered, MahaRERA & NMRDA certified projects on Wardha Road and MIHAN corridor.', 'group' => 'general', 'desc' => 'Footer About Text'],
            'footer_rera_disclaimer' => ['value' => 'The information, imagery, project layouts, specifications, dimensions, amenities, and floor plans depicted on this website are indicative and subject to approvals from competent authorities (MahaRERA, NMRDA, NIT, Town Planning). Real estate plotted layouts are registered under Maharashtra Real Estate Regulatory Authority (MahaRERA). Detailed project RERA registration certificates, sanctioned release letters (RL), and title documents can be verified on the official MahaRERA website: https://maharera.mahaonline.gov.in.', 'group' => 'general', 'desc' => 'Footer Regulatory & RERA Disclaimer Text'],
            'footer_copyright'       => ['value' => '© ' . date('Y') . ' Mauli Infra. All rights reserved. Est. 2019 · Nagpur.', 'group' => 'general', 'desc' => 'Footer Copyright Text'],

            'default_meta_title'       => ['value' => 'Mauli Infra | Nagpur\'s Most Trusted Land Developer', 'group' => 'seo', 'desc' => 'Default Meta Title'],
            'default_meta_description' => ['value' => 'Your Land — A Brighter Tomorrow. Thoughtfully planned plotted communities in prime Nagpur locations. 5000+ happy families, 500+ acres delivered.', 'group' => 'seo', 'desc' => 'Default Meta Description'],
        ];

        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value'       => $data['value'],
                    'group'       => $data['group'],
                    'description' => $data['desc'],
                ]
            );
        }

        // =====================================================================
        // 2. HOMEPAGE (slug: home)
        // =====================================================================
        $this->seedPage('home', 'Home', 'Mauli Infra | Nagpur\'s Most Trusted Land Developer', 'Your Land — A Brighter Tomorrow. Thoughtfully planned plotted communities in prime Nagpur locations.', [
            [
                'section_key'  => 'hero_slider',
                'section_name' => '1. Hero Lead Section',
                'title'        => 'Your Land — A Brighter Tomorrow',
                'subtitle'     => 'NAGPUR\'S MOST TRUSTED LAND DEVELOPER',
                'content'      => 'Thoughtfully planned plotted communities in prime Nagpur locations, designed for your family, your future and lasting value.',
                'image'        => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1920&q=80',
                'button_text'  => 'Explore Projects',
                'button_url'   => '#featured-projects',
                'secondary_button_text' => 'Book Site Visit',
                'secondary_button_url'  => '#lead-form-card',
                'options'      => [
                    'eyebrow'   => 'NAGPUR\'S MOST TRUSTED LAND DEVELOPER',
                    'heading_1' => 'Your Land',
                    'heading_2' => 'A Brighter Tomorrow',
                    'badges'    => [
                        ['icon' => 'bi-check-circle-fill', 'text' => 'Site Approved'],
                        ['icon' => 'bi-file-earmark-check-fill', 'text' => 'Clear Title'],
                        ['icon' => 'bi-bank2', 'text' => 'Home Loans'],
                        ['icon' => 'bi-building-check', 'text' => 'Developed Infra'],
                    ],
                ],
                'sort_order'   => 1,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'trust_stats',
                'section_name' => '2. Live Trust Statistics',
                'title'        => 'Numbers That Speak',
                'subtitle'     => 'THE MAULI LEGACY',
                'content'      => 'Over 7 years of excellence in developing prime NMRDA and MahaRERA approved plots in Nagpur.',
                'options'      => [
                    'stat_1_number' => '7+',
                    'stat_1_suffix' => '',
                    'stat_1_label'  => 'Years of Trust',
                    'stat_2_number' => '10+',
                    'stat_2_suffix' => '',
                    'stat_2_label'  => 'Projects Delivered',
                    'stat_3_number' => '5,000+',
                    'stat_3_suffix' => '',
                    'stat_3_label'  => 'Happy Families',
                    'stat_4_number' => '500+',
                    'stat_4_suffix' => '',
                    'stat_4_label'  => 'Acres Delivered',
                    'stat_5_number' => 'RERA',
                    'stat_5_suffix' => '',
                    'stat_5_label'  => 'Approved Developments',
                ],
                'sort_order'   => 2,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'featured_projects',
                'section_name' => '3. Featured Projects',
                'title'        => 'Featured Projects',
                'subtitle'     => 'PRIME PLOTTED DEVELOPMENTS',
                'content'      => 'Explore our premium MahaRERA registered and NMRDA sanctioned plotted communities across Nagpur.',
                'button_text'  => 'View All Projects',
                'button_url'   => '/projects',
                'sort_order'   => 3,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'about_intro',
                'section_name' => '4. Brand Story (About)',
                'title'        => 'Building Landmarks for Generations',
                'subtitle'     => 'ABOUT MAULI INFRA',
                'content'      => 'Mauli Infra is Nagpur\'s premier plotted land developer. We deliver 100% legally clear, NMRDA & MahaRERA approved residential and commercial plots on high-growth corridors like Wardha Road and MIHAN.',
                'image'        => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?w=1200&q=80',
                'button_text'  => 'Know More',
                'button_url'   => '/about',
                'sort_order'   => 4,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'vision_motion',
                'section_name' => '5. Our Vision in Motion',
                'title'        => 'Our Vision',
                'subtitle'     => 'THE VISION',
                'content'      => 'To become Central India\'s most trusted real estate brand — delivering affordable yet premium plotted developments that offer security, long-term value, and lasting pride of ownership.',
                'image'        => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?w=1200&q=80',
                'button_text'  => 'Explore Projects',
                'button_url'   => '/projects',
                'sort_order'   => 5,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'why_choose_us',
                'section_name' => '6. Why Choose Mauli Infra',
                'title'        => 'Why Choose Mauli Infra',
                'subtitle'     => 'UNCOMPROMISING LEGAL TRUST',
                'content'      => '100% NMRDA/NIT Sanctioned, MahaRERA Registered, Spot Registry & RL Transfer, Bank Approved by SBI/HDFC/ICICI.',
                'options'      => [
                    'points' => [
                        ['title' => 'Clear Title Guarantee', 'desc' => '100% clear title with immediate registry & sanction clearance. Zero litigation risk.'],
                        ['title' => 'RERA & RL Approved', 'desc' => 'All projects registered under MahaRERA and fully sanctioned by NMRDA / NIT.'],
                        ['title' => 'Bank Loan Assistance', 'desc' => 'Pre-approved by SBI, HDFC, ICICI, Axis Bank & leading financial institutions.'],
                        ['title' => 'Strategic Locations', 'desc' => 'Situated on prime high-growth belts: Wardha Road, MIHAN SEZ & Samruddhi Corridor.'],
                        ['title' => 'Developed Infrastructure', 'desc' => 'Wide cement concrete roads, underground drainage, water pipelines, electricity & gardens.'],
                        ['title' => 'Dedicated Customer Support', 'desc' => 'Transparent documentation, on-site visit assistance, and end-to-end guidance.'],
                    ],
                ],
                'sort_order'   => 6,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'prime_locations',
                'section_name' => '7. Prime Locations & Nagpur Growth',
                'title'        => 'Prime Locations Across Nagpur',
                'subtitle'     => 'STRATEGIC CORRIDORS',
                'content'      => 'Plots on Wardha Road, MIHAN, Samruddhi Corridor, and Besa — Nagpur\'s highest appreciating belts.',
                'button_text'  => 'Explore Locations',
                'button_url'   => '/projects',
                'sort_order'   => 7,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'why_nagpur_growth',
                'section_name' => '8. Why Invest in Nagpur Story',
                'title'        => 'Why Invest in Nagpur?',
                'subtitle'     => 'INDIA\'S FASTEST GROWING LOGISTICS HUB',
                'content'      => 'Nagpur is experiencing unprecedented infrastructure growth with the ₹55,000 Cr Samruddhi Mahamarg, MIHAN SEZ aerospace & IT hub, Metro Phase 2, and premier educational institutes.',
                'button_text'  => 'Explore Projects',
                'button_url'   => '/projects',
                'sort_order'   => 8,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'nivasa_bento',
                'section_name' => '9. Inside Mauli Nivasa',
                'title'        => 'A life, well designed.',
                'subtitle'     => 'INSIDE MAULI NIVASA',
                'content'      => '78 acres at Borekhedi, built around a grand clubhouse, a resort-grade pool, and green pockets for every chapter of family life.',
                'button_text'  => 'Explore Mauli Nivasa',
                'button_url'   => '/projects',
                'sort_order'   => 9,
                'is_active'    => false,
            ],
            [
                'section_key'  => 'lead_cta_banner',
                'section_name' => '10. VIP Site Visit Banner',
                'title'        => 'Book Your Free Chauffeur Site Visit Today',
                'subtitle'     => 'COMPLIMENTARY SERVICE',
                'content'      => 'We provide sanitized AC cab pickup & drop from your doorstep anywhere in Nagpur.',
                'button_text'  => 'Schedule Free Pickup',
                'button_url'   => '/contact',
                'secondary_button_text' => 'Call Now',
                'secondary_button_url'  => 'tel:+917030305555',
                'sort_order'   => 10,
                'is_active'    => false,
            ],
            [
                'section_key'  => 'final_cta',
                'section_name' => '11. Ready to Own Your Plot (CTA)',
                'title'        => 'Ready to Own Your Plot?',
                'subtitle'     => 'START YOUR LAND OWNERSHIP JOURNEY',
                'content'      => 'Join 5,000+ happy families who trust Mauli Infra for safe, appreciating land investments in Nagpur.',
                'button_text'  => 'Call +91 70303 05555',
                'button_url'   => 'tel:+917030305555',
                'secondary_button_text' => 'Chat on WhatsApp',
                'secondary_button_url'  => 'https://wa.me/917030305555',
                'sort_order'   => 11,
                'is_active'    => true,
            ],
        ]);

        // =====================================================================
        // 3. ABOUT PAGE (slug: about)
        // =====================================================================
        $this->seedPage('about', 'About Us', 'About Mauli Infra | Trusted Land Developers Since 2019 | Nagpur', 'Learn about Mauli Infra — Nagpur\'s most trusted plotted land developer since 2019.', [
            [
                'section_key'  => 'about_hero',
                'section_name' => '1. Hero Banner',
                'title'        => 'Building Trust, One Plot at a Time',
                'subtitle'     => 'OUR STORY · EST. 2019',
                'content'      => 'Mauli Infra was founded with a single mission — to give every Nagpur family the gift of clear-title, legally-secure land ownership on prime growth corridors.',
                'image'        => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80',
                'sort_order'   => 1,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'our_story',
                'section_name' => '2. Our Story & Legacy',
                'title'        => 'The Mauli Legacy',
                'subtitle'     => 'FOUNDED 2019',
                'content'      => 'Starting with a single 10-acre layout on Wardha Road in 2019, Mauli Infra has grown into Nagpur\'s most recognized plotted township brand — delivering 15+ projects, 8,000+ families, and 75 lakh+ sq. ft. of land ownership. Every project is a promise kept.',
                'image'        => 'https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=1200&q=80',
                'button_text'  => 'View Our Projects',
                'button_url'   => '/projects',
                'sort_order'   => 2,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'our_vision',
                'section_name' => '3. Vision & Mission',
                'title'        => 'Our Vision & Mission',
                'subtitle'     => 'WHAT DRIVES US',
                'content'      => 'Vision: To become India\'s most trusted real estate brand — delivering affordable yet premium plotted developments that offer security, long-term value, and a better quality of living.' . "\n\n" . 'Mission: Communities where families live closer to nature, and growth happens sustainably.',
                'options'      => [
                    'values' => [
                        ['icon' => 'bi-shield-check', 'title' => 'Legal Integrity', 'desc' => '100% NMRDA & MahaRERA compliant — no shortcuts, ever.'],
                        ['icon' => 'bi-people-fill', 'title' => 'Family First', 'desc' => 'Every decision is made with the homebuyer\'s security in mind.'],
                        ['icon' => 'bi-tree', 'title' => 'Sustainable Growth', 'desc' => 'Green spaces, underground utilities, and planned infrastructure.'],
                        ['icon' => 'bi-award', 'title' => 'Quality Delivery', 'desc' => 'CC-road, drainage, electricity — complete before possession.'],
                    ],
                ],
                'sort_order'   => 3,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'team_section',
                'section_name' => '4. Leadership Team',
                'title'        => 'The People Behind Your Trust',
                'subtitle'     => 'LEADERSHIP',
                'content'      => 'Our leadership team brings decades of combined experience in real estate development, legal compliance, and customer service.',
                'sort_order'   => 4,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'testimonials_section',
                'section_name' => '5. Testimonials',
                'title'        => 'What Our Families Say',
                'subtitle'     => 'TRUSTED BY 8,000+ FAMILIES',
                'content'      => 'Real stories from real homeowners across Nagpur.',
                'sort_order'   => 5,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'about_cta',
                'section_name' => '6. Bottom CTA',
                'title'        => 'Start Your Land Investment Journey Today',
                'subtitle'     => 'GET IN TOUCH',
                'content'      => 'Speak with our senior land consultants and explore plots tailored to your budget and investment goals.',
                'button_text'  => 'Book a Free Consultation',
                'button_url'   => '/contact',
                'sort_order'   => 6,
                'is_active'    => true,
            ],
        ]);

        // =====================================================================
        // 4. WHY NAGPUR PAGE (slug: why-nagpur)
        // =====================================================================
        $this->seedPage('why-nagpur', 'Why Invest in Nagpur', 'Why Invest in Nagpur Real Estate? | Growth Drivers & Plotted ROI | Mauli Infra', 'Discover why Nagpur is Central India\'s fastest growing real estate investment hub.', [
            [
                'section_key'  => 'growth_hero',
                'section_name' => '1. Hero Banner',
                'title'        => 'Why Invest in Nagpur Real Estate?',
                'subtitle'     => 'INDIA\'S LOGISTICS & INFRASTRUCTURE CAPITAL',
                'content'      => 'Positioned at the geographical heart of India, Nagpur is undergoing an unprecedented economic boom driven by the ₹55,000 Cr Samruddhi Expressway, MIHAN IT SEZ, and rapid infrastructure transformation.',
                'image'        => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80',
                'sort_order'   => 1,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'growth_drivers',
                'section_name' => '2. Growth Drivers (6 Catalysts)',
                'title'        => '6 Mega Growth Engines Transforming Nagpur',
                'subtitle'     => 'MACROECONOMIC CATALYSTS',
                'content'      => 'Six powerful infrastructure and economic drivers are making Nagpur India\'s fastest-appreciating real estate market.',
                'options'      => [
                    'drivers' => [
                        ['icon' => 'bi-signpost-split', 'title' => 'Samruddhi Mahamarg', 'desc' => 'The 701-km super expressway reduces travel time from Nagpur to Mumbai to just 7 hours, turning Wardha Road and Jamtha corridor into the premier industrial and plotted residential belt.'],
                        ['icon' => 'bi-laptop', 'title' => 'MIHAN IT Park & SEZ', 'desc' => 'Global tech giants (TCS, Infosys, Tech Mahindra, HCL) and aerospace hubs employ over 50,000+ professionals, generating massive residential land demand.'],
                        ['icon' => 'bi-train-front', 'title' => 'Metro Phase 2', 'desc' => 'Nagpur Metro Phase 2 expansion connects MIHAN, Airport, and Wardha Road corridors, boosting land values by 30-40% in 3-km radius zones.'],
                        ['icon' => 'bi-hospital', 'title' => 'AIIMS Nagpur', 'desc' => 'India\'s premier medical institute campus on Wardha Road is attracting 10,000+ medical professionals and students — fuelling residential demand.'],
                        ['icon' => 'bi-building', 'title' => 'Smart City Mission', 'desc' => 'Nagpur is India\'s top-ranked Smart City — with integrated command centres, smart utilities, and greenfield townships.'],
                        ['icon' => 'bi-geo-alt', 'title' => 'Zero Mile City', 'desc' => 'India\'s geographical centre makes Nagpur the ultimate logistics hub — connecting 8 National Highways and 3 major rail corridors.'],
                    ],
                ],
                'sort_order'   => 2,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'investment_stats',
                'section_name' => '3. Investment Statistics',
                'title'        => 'Nagpur by the Numbers',
                'subtitle'     => 'THE ROI STORY',
                'content'      => 'Hard data proving Nagpur\'s investment potential.',
                'options'      => [
                    'stats' => [
                        ['number' => '18-25%', 'label' => 'Annual Land Appreciation on Wardha Road'],
                        ['number' => '₹55,000 Cr', 'label' => 'Samruddhi Mahamarg Investment'],
                        ['number' => '50,000+', 'label' => 'IT & Aerospace Jobs at MIHAN'],
                        ['number' => '3X', 'label' => 'Expected Growth by 2030'],
                    ],
                ],
                'sort_order'   => 3,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'why_invest_cta',
                'section_name' => '4. Investment CTA',
                'title'        => 'Invest in Nagpur\'s Growth Story Today',
                'subtitle'     => 'LIMITED PLOTS AVAILABLE',
                'content'      => 'Don\'t miss out on Nagpur\'s once-in-a-generation growth opportunity. Speak to our senior land consultant today.',
                'button_text'  => 'Get Free Investment Advice',
                'button_url'   => '/contact',
                'secondary_button_text' => 'View Available Projects',
                'secondary_button_url'  => '/projects',
                'sort_order'   => 4,
                'is_active'    => true,
            ],
        ]);

        // =====================================================================
        // 5. NRI INVESTOR DESK PAGE (slug: nri)
        // =====================================================================
        $this->seedPage('nri', 'NRI Investment Desk', 'NRI Property Investment in Nagpur | FEMA Compliant Plotted Plots | Mauli Infra', 'NRI investment guide for Nagpur real estate — FEMA rules, RERA plots, repatriation, POA.', [
            [
                'section_key'  => 'nri_hero',
                'section_name' => '1. NRI Hero Banner',
                'title'        => 'NRI Property Investment Made Simple',
                'subtitle'     => 'NRI INVESTMENT DESK',
                'content'      => 'Invest in 100% FEMA-compliant, MahaRERA-registered plotted land in Nagpur — India\'s fastest-growing real estate market — from anywhere in the world.',
                'image'        => 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=1920&q=80',
                'button_text'  => 'Connect with NRI Advisor',
                'button_url'   => '/contact',
                'sort_order'   => 1,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'nri_why',
                'section_name' => '2. Why NRIs Choose Nagpur',
                'title'        => 'Why Smart NRIs Invest in Nagpur',
                'subtitle'     => 'THE NRI ADVANTAGE',
                'content'      => 'Nagpur offers NRIs a unique combination of high appreciation, low entry price, and 100% legal security — all in India\'s geographical and economic centre.',
                'options'      => [
                    'points' => [
                        ['icon' => 'bi-currency-rupee', 'title' => 'Lower Entry Price', 'desc' => 'Nagpur plots cost 60-70% less than Mumbai or Pune with 2-3X higher appreciation potential.'],
                        ['icon' => 'bi-shield-lock', 'title' => 'FEMA Compliant', 'desc' => 'All our plots are 100% FEMA & RBI compliant for NRI purchase.'],
                        ['icon' => 'bi-bank', 'title' => 'NRE/NRO Accounts', 'desc' => 'Purchase via NRE/NRO account with full repatriation facility.'],
                        ['icon' => 'bi-file-earmark-check', 'title' => 'POA Service', 'desc' => 'We handle everything remotely — property registration via Power of Attorney.'],
                    ],
                ],
                'sort_order'   => 2,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'nri_process',
                'section_name' => '3. NRI Buying Process',
                'title'        => 'Simple 5-Step NRI Buying Process',
                'subtitle'     => 'HOW IT WORKS',
                'content'      => 'Buying a plot from abroad is simple with Mauli Infra. Our dedicated NRI desk handles everything.',
                'options'      => [
                    'steps' => [
                        ['step' => '01', 'title' => 'Virtual Site Tour', 'desc' => 'Video call with our NRI advisor for a live site walkthrough.'],
                        ['step' => '02', 'title' => 'Documentation', 'desc' => 'Submit passport, PAN, OCI card, and NRE/NRO account details.'],
                        ['step' => '03', 'title' => 'Agreement', 'desc' => 'Receive and review sale agreement via email. POA arrangement if needed.'],
                        ['step' => '04', 'title' => 'Payment', 'desc' => 'Pay via NRE/NRO account or wire transfer — fully RBI compliant.'],
                        ['step' => '05', 'title' => 'Registration', 'desc' => 'Sub-registrar stamp and handover. We courier all documents to you.'],
                    ],
                ],
                'sort_order'   => 3,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'nri_cta',
                'section_name' => '4. NRI CTA',
                'title'        => 'Connect with Your Dedicated NRI Advisor',
                'subtitle'     => 'WE SPEAK YOUR LANGUAGE',
                'content'      => 'Our NRI desk is available 7 days a week across time zones. Hindi, English, and Marathi support.',
                'button_text'  => 'Schedule NRI Consultation',
                'button_url'   => '/contact',
                'secondary_button_text' => 'WhatsApp Us Now',
                'secondary_button_url'  => 'https://wa.me/919876543210',
                'sort_order'   => 4,
                'is_active'    => true,
            ],
        ]);

        // =====================================================================
        // 6. CONTACT PAGE (slug: contact)
        // =====================================================================
        $this->seedPage('contact', 'Contact & Site Visit', 'Contact Mauli Infra | Book Free Site Visit | Nagpur Real Estate', 'Book your free chauffeur site visit or speak with our senior land consultants in Nagpur.', [
            [
                'section_key'  => 'contact_hero',
                'section_name' => '1. Contact Hero',
                'title'        => 'Book Your Free Chauffeur Site Visit',
                'subtitle'     => 'WE COME TO YOU',
                'content'      => 'Our senior land consultants provide free AC cab pickup & drop from your home. Visit the site, ask every question, and decide at your own pace — zero pressure.',
                'sort_order'   => 1,
                'is_active'    => true,
            ],
            [
                'section_key'  => 'contact_info',
                'section_name' => '2. Contact Information',
                'title'        => 'Get in Touch',
                'subtitle'     => 'ALWAYS AVAILABLE',
                'content'      => 'Reach us on call, WhatsApp, or visit our office. Monday–Sunday, 9 AM–8 PM.',
                'sort_order'   => 2,
                'is_active'    => true,
            ],
        ]);

        // =====================================================================
        // 7. GALLERY PAGE (slug: gallery)
        // =====================================================================
        $this->seedPage('gallery', 'Gallery', 'Gallery | Site Progress & Amenities | Mauli Infra', 'Visual tour of Mauli Infra\'s plotted township projects — site progress, clubhouses, and green amenities.', [
            [
                'section_key'  => 'gallery_hero',
                'section_name' => '1. Gallery Hero',
                'title'        => 'See Our Projects Come to Life',
                'subtitle'     => 'SITE PROGRESS & AMENITIES',
                'content'      => 'Browse construction updates, clubhouse interiors, green parks, and plot handovers across all our projects.',
                'sort_order'   => 1,
                'is_active'    => true,
            ],
        ]);
    }

    /**
     * Helper — upsert a Page and its sections.
     */
    private function seedPage(string $slug, string $title, string $metaTitle, string $metaDesc, array $sections): void
    {
        $page = Page::updateOrCreate(
            ['slug' => $slug],
            [
                'title'            => $title,
                'slug'             => $slug,
                'meta_title'       => $metaTitle,
                'meta_description' => $metaDesc,
                'is_published'     => true,
            ]
        );

        foreach ($sections as $sec) {
            PageSection::updateOrCreate(
                ['page_id' => $page->id, 'section_key' => $sec['section_key']],
                [
                    'section_name'          => $sec['section_name'],
                    'title'                 => $sec['title'] ?? null,
                    'subtitle'              => $sec['subtitle'] ?? null,
                    'content'               => $sec['content'] ?? null,
                    'image'                 => $sec['image'] ?? null,
                    'secondary_image'       => $sec['secondary_image'] ?? null,
                    'button_text'           => $sec['button_text'] ?? null,
                    'button_url'            => $sec['button_url'] ?? null,
                    'secondary_button_text' => $sec['secondary_button_text'] ?? null,
                    'secondary_button_url'  => $sec['secondary_button_url'] ?? null,
                    'options'               => isset($sec['options']) ? $sec['options'] : null,
                    'sort_order'            => $sec['sort_order'],
                    'is_active'             => $sec['is_active'] ?? true,
                ]
            );
        }
    }
}
