<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Location;
use App\Models\Project;
use App\Models\ProjectPlotType;
use App\Models\ProjectRERA;
use App\Models\ProjectNearbyPlace;
use App\Models\ProjectFAQ;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealEstateDefaultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Strategic Nagpur Corridors
        $locations = [
            [
                'name' => 'Wardha Road / Shankarpur',
                'slug' => 'wardha-road-shankarpur',
                'short_description' => 'Nagpur’s fastest growing commercial & residential IT corridor near airport and metro.',
                'detailed_description' => 'Wardha Road is the primary growth spine of Nagpur, home to the MIHAN SEZ, AIIMS, IIM, and international airport.',
                'why_invest_here' => 'Highest capital appreciation, corporate job growth with Infosys, TCS, and HCL.',
                'connectivity' => 'Direct access to Metro Rail, National Highway 44, and Ring Road.',
                'hero_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Samruddhi Expressway / Near AIIMS',
                'slug' => 'samruddhi-expressway',
                'short_description' => 'High-speed economic corridor connecting Nagpur to Mumbai with massive logistics hubs.',
                'detailed_description' => 'Prime land parcels located within 5 minutes of Samruddhi Mahamarg interchanges.',
                'why_invest_here' => 'Mega logistics hub development and rapid industrialization.',
                'connectivity' => 'Direct seamless expressway entry and outer ring road link.',
                'hero_image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?w=800&q=80',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Borekhedi / MIHAN SEZ',
                'slug' => 'borekhedi-mihan',
                'short_description' => 'Tranquil luxury township corridor home to mega-developments like Mauli Nivasa.',
                'detailed_description' => 'Sprawling master-planned living surrounded by landscaped hills and nature.',
                'why_invest_here' => 'Resort-grade lifestyle amenities with immense land value growth.',
                'connectivity' => '10 minutes from MIHAN SEZ and Khapri Metro Station.',
                'hero_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Kothewada / ISKCON Temple',
                'slug' => 'kothewada-iskcon',
                'short_description' => 'Spiritual & cultural destination corridor adjacent to upcoming ISKCON IVCC campus.',
                'detailed_description' => 'Serene residential plotted developments near prestigious educational and cultural centres.',
                'why_invest_here' => 'Rapid infrastructure development and spiritual serenity opposite upcoming ISKCON Temple.',
                'connectivity' => 'Well-connected to Outer Ring Road and Wardha Road.',
                'hero_image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Bothli / Jamtha Corridor',
                'slug' => 'bothli-jamtha',
                'short_description' => 'Established residential corridor near VCA Stadium with ready-for-registry plots.',
                'detailed_description' => 'Fully-developed gated townships with wide cement roads, underground drainage, and electricity.',
                'why_invest_here' => 'Immediate home construction readiness and high rental demand near Jamtha stadium.',
                'connectivity' => 'Direct access to National Highway 44 & Wardha Road.',
                'hero_image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Gawasi / Manapur',
                'slug' => 'gawasi-manapur',
                'short_description' => 'Scenic bird-eye plotted layout enclave with clear titles and bank finance.',
                'detailed_description' => 'Exclusive plotted community designed for peaceful villa construction.',
                'why_invest_here' => 'Affordable entry pricing with steady 20% annual appreciation.',
                'connectivity' => 'Connected via Manapur Road to Wardha Highway.',
                'hero_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80',
                'featured' => false,
                'is_active' => true,
            ],
        ];

        $locationModels = [];
        foreach ($locations as $loc) {
            $locationModels[$loc['slug']] = Location::updateOrCreate(
                ['slug' => $loc['slug']],
                $loc
            );
        }

        // 2. Seed Amenities
        $amenities = [
            ['name' => 'Grand Entrance Arch', 'icon' => 'bi-door-open', 'short_description' => 'Designer entrance gate with security cabin'],
            ['name' => '24/7 CCTV & Security', 'icon' => 'bi-shield-check', 'short_description' => 'Gated perimeter with round-the-clock guards'],
            ['name' => 'Wide Cement Concrete Roads', 'icon' => 'bi-signpost-2', 'short_description' => '30ft and 40ft wide internal concrete roads with curbs'],
            ['name' => 'Underground Sewage & Drainage', 'icon' => 'bi-water', 'short_description' => 'Modern underground drainage and stormwater lines'],
            ['name' => 'Transformer & LED Streetlights', 'icon' => 'bi-lightbulb', 'short_description' => 'Dedicated power infrastructure and LED illumination'],
            ['name' => 'Landscaped Botanical Gardens', 'icon' => 'bi-tree', 'short_description' => 'Green reserve parks with sitting gazebos and pergolas'],
            ['name' => 'Resort-Grade Swimming Pool', 'icon' => 'bi-water', 'short_description' => 'Landscaped pool deck with loungers and shaded pavilion'],
            ['name' => 'Open-Air Amphitheatre', 'icon' => 'bi-camera-reels', 'short_description' => 'Bonfire seating under trees for community gatherings'],
            ['name' => 'Private Mini-Theatre', 'icon' => 'bi-film', 'short_description' => 'Private screening lounge for match and movie nights'],
            ['name' => 'Glass-Walled Gymnasium', 'icon' => 'bi-heart-pulse', 'short_description' => 'Fully-equipped fitness centre opening to lush gardens'],
            ['name' => 'Sunrise Yoga & Meditation Deck', 'icon' => 'bi-sun', 'short_description' => 'Covered tensile canopy deck for peaceful mornings'],
            ['name' => 'Children’s Play Zone', 'icon' => 'bi-emoji-smile', 'short_description' => 'Safe equipment with rubberized turf flooring'],
        ];

        $amenityModels = [];
        foreach ($amenities as $am) {
            $amenityModels[] = Amenity::updateOrCreate(
                ['slug' => Str::slug($am['name'])],
                [
                    'name' => $am['name'],
                    'icon' => $am['icon'],
                    'short_description' => $am['short_description'],
                    'is_active' => true,
                ]
            );
        }

        $amenityIds = collect($amenityModels)->pluck('id')->toArray();

        // 3. Seed Exact Real Projects from mauliinfra.com & maulidevelopers.com
        $projectsData = [
            [
                'name' => 'Mauli Temple Town 40 At Kothewada Nagpur',
                'slug' => 'mauli-temple-town',
                'project_code' => 'MT-40',
                'location_slug' => 'kothewada-iskcon',
                'address' => 'Opposite Proposed ISKCON Temple, Kothewada, Wardha Road, Nagpur',
                'status' => 'active',
                'short_description' => 'RL Sanctioned & NMRDA Approved Residential Plots opposite Proposed ISKCON Temple at Kothewada, Nagpur.',
                'description' => 'Mauli Temple Town 40 is a prime residential township located in Kothewada, Nagpur, right opposite the proposed grand ISKCON Temple. Enjoy a serene, spiritually uplifting atmosphere alongside rapid infrastructure growth, wide cement roads, underground electrification, landscape garden, and unmatched connectivity to Wardha Road and MIHAN.',
                'starting_price' => 2250000,
                'display_price' => '₹22.50 Lakhs onwards',
                'total_project_area' => 25,
                'total_plots' => 120,
                'plot_size_range' => '1,100 - 3,000 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&q=80',
                'featured' => true,
                'sort_order' => 1,
                'is_published' => true,
                'rera_number' => 'P50500049764',
                'badge' => 'Prime Highlight',
            ],
            [
                'name' => 'Mauli Town 41 Bothali - Nagpur',
                'slug' => 'mauli-town-41-bothali',
                'project_code' => 'MT-41',
                'location_slug' => 'bothli-jamtha',
                'address' => 'Bothali, Near Jamtha & Wardha Road, Nagpur',
                'status' => 'active',
                'short_description' => 'NMRDA Sanctioned & RL-Approved Township in Bothali, Nagpur with Bank Finance Available.',
                'description' => 'Mauli Town 41 at Bothali is strategically located near Jamtha Sports Complex and Wardha Road. Designed for high returns on investment and tranquil living, this project features wide internal roads, open green spaces, 24/7 security, and modern township amenities.',
                'starting_price' => 1950000,
                'display_price' => '₹19.50 Lakhs onwards',
                'total_project_area' => 15,
                'total_plots' => 95,
                'plot_size_range' => '1,000 - 2,500 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&q=80',
                'featured' => true,
                'sort_order' => 2,
                'is_published' => true,
                'rera_number' => 'P50500052341',
                'badge' => 'New Launch',
            ],
            [
                'name' => 'Mauli Infinity 39',
                'slug' => 'mauli-infinity-39',
                'project_code' => 'MI-39',
                'location_slug' => 'wardha-road-shankarpur',
                'address' => 'Near MIHAN & AIIMS, Wardha Road, Nagpur',
                'status' => 'active',
                'short_description' => 'Premium Residential Plots close to MIHAN SEZ, Metro Station & AIIMS Nagpur.',
                'description' => 'Mauli Infinity 39 offers supreme luxury plotting along the flourishing Wardha Road corridor. Ideal for IT professionals and smart investors seeking proximity to leading educational hubs, hospitals, and MIHAN SEZ.',
                'starting_price' => 2500000,
                'display_price' => '₹25.00 Lakhs onwards',
                'total_project_area' => 18,
                'total_plots' => 80,
                'plot_size_range' => '1,200 - 3,500 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?w=1200&q=80',
                'featured' => true,
                'sort_order' => 3,
                'is_published' => true,
                'rera_number' => 'P50500048123',
                'badge' => 'Featured',
            ],
            [
                'name' => 'Mauli Upvan 38',
                'slug' => 'mauli-upvan-38',
                'project_code' => 'MU-38',
                'location_slug' => 'gawasi-manapur',
                'address' => 'Gawasi Manapur, Wardha Road, Nagpur',
                'status' => 'active',
                'short_description' => 'Scenic bird-eye plotted layout in Gawasi, Manapur designed for serene residential living.',
                'description' => 'Mauli Upvan 38 features expansive green surroundings with modern layout infrastructure including wide concrete roads, boundary wall, street illumination, and abundant groundwater. Approved by leading nationalized banks.',
                'starting_price' => 1950000,
                'display_price' => '₹19.50 Lakhs onwards',
                'total_project_area' => 9.5,
                'total_plots' => 110,
                'plot_size_range' => '1,200 - 2,500 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&q=80',
                'featured' => true,
                'sort_order' => 4,
                'is_published' => true,
                'rera_number' => 'P50500038221',
                'badge' => 'Ongoing',
            ],
            [
                'name' => 'Mauli Crystal 35',
                'slug' => 'mauli-crystal-35',
                'project_code' => 'MC-35',
                'location_slug' => 'wardha-road-shankarpur',
                'address' => 'Shankarpur, Wardha Road, Nagpur',
                'status' => 'completed',
                'short_description' => '11 acres premium plotted township in Shankarpur on Wardha Road with 151 clear-title plots.',
                'description' => 'Mauli Crystal 35 represents the pinnacle of urban plotted living in Nagpur. Spanning 11 acres with 151 vastu-compliant plots, this township is fully sanctioned by NMRDA and registered under MahaRERA. Features wide concrete roads, landscaped central gardens, underground utilities, and instant RL registry.',
                'starting_price' => 2800000,
                'display_price' => '₹28.00 Lakhs onwards',
                'total_project_area' => 11,
                'total_plots' => 151,
                'plot_size_range' => '1,200 - 3,500 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&q=80',
                'featured' => true,
                'sort_order' => 5,
                'is_published' => true,
                'rera_number' => 'P50500035123',
                'badge' => 'Ready Registry',
            ],
            [
                'name' => 'Mauli Nivasa 36 & 37',
                'slug' => 'mauli-nivasa',
                'project_code' => 'MN-36-37',
                'location_slug' => 'borekhedi-mihan',
                'address' => 'Borekhedi, Near Butibori & MIHAN, Nagpur',
                'status' => 'active',
                'short_description' => '78 acres mega plotted township at Borekhedi built around a grand clubhouse, pool & nature.',
                'description' => 'A life, well designed. Mauli Nivasa 36 & 37 spans 78 scenic acres at Borekhedi, Nagpur. Featuring a 25,000 sq.ft luxury clubhouse, resort-style swimming pool, amphitheatre, mini-theatre, and yoga decks, Mauli Nivasa combines tranquil resort living with high investment appreciation near MIHAN.',
                'starting_price' => 3500000,
                'display_price' => '₹35.00 Lakhs onwards',
                'total_project_area' => 78,
                'total_plots' => 450,
                'plot_size_range' => '1,500 - 4,000 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                'featured' => true,
                'sort_order' => 6,
                'is_published' => true,
                'rera_number' => 'P50500036789',
                'badge' => 'Mega Township',
            ],
            [
                'name' => 'Mauli Town 33 & 34',
                'slug' => 'mauli-town-33-34',
                'project_code' => 'MT-33-34',
                'location_slug' => 'bothli-jamtha',
                'address' => 'Bothli, Wardha Road Corridor, Nagpur',
                'status' => 'completed',
                'short_description' => '8 acres completed plotted township in Bothli with 2,150 to 2,200 sq.ft plots ready to move.',
                'description' => '100% completed plotted enclave with established green tree lines, children play parks, and immediate spot registry. Over 80% sold and several luxury bungalows currently under construction.',
                'starting_price' => 2600000,
                'display_price' => '₹26.00 Lakhs onwards',
                'total_project_area' => 8,
                'total_plots' => 95,
                'plot_size_range' => '2,150 - 2,200 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                'featured' => false,
                'sort_order' => 7,
                'is_published' => true,
                'rera_number' => 'P50500033998',
                'badge' => 'Completed',
            ],
            [
                'name' => 'Mauli Town 30, 31 & 32',
                'slug' => 'mauli-town-30-31-32',
                'project_code' => 'MT-30-32',
                'location_slug' => 'bothli-jamtha',
                'address' => 'Bothli, Near Wardha Road, Nagpur',
                'status' => 'completed',
                'short_description' => '10 acres ready-for-registry plotted layout at Bothli on Wardha Road with 1,750 to 2,200 sq.ft plots.',
                'description' => 'Mauli Town 30, 31 & 32 offers ready-to-construct plots in the thriving Bothli corridor near Wardha Road. Complete with electrification, underground drainage, and asphalted roads, it is ideal for immediate home construction with complete bank loan support.',
                'starting_price' => 2100000,
                'display_price' => '₹21.00 Lakhs onwards',
                'total_project_area' => 10,
                'total_plots' => 120,
                'plot_size_range' => '1,750 - 2,200 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&q=80',
                'featured' => false,
                'sort_order' => 8,
                'is_published' => true,
                'rera_number' => 'P50500030554',
                'badge' => 'Completed',
            ],
            [
                'name' => 'Mauli UNICORN 29',
                'slug' => 'mauli-unicorn-29',
                'project_code' => 'MU-29',
                'location_slug' => 'samruddhi-expressway',
                'address' => 'Near Samruddhi Expressway & AIIMS, Nagpur',
                'status' => 'active',
                'short_description' => 'Strategic plotted township near Samruddhi Highway close to AIIMS, IIM, Infosys & MIHAN.',
                'description' => 'Located right at the doorstep of the Samruddhi Mahamarg economic corridor and minutes from AIIMS, IIM Nagpur, and IT giants (Infosys, TCS), Mauli UNICORN 29 offers immense capital appreciation potential for forward-thinking land investors.',
                'starting_price' => 3200000,
                'display_price' => '₹32.00 Lakhs onwards',
                'total_project_area' => 14,
                'total_plots' => 160,
                'plot_size_range' => '1,400 - 3,200 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?w=1200&q=80',
                'featured' => false,
                'sort_order' => 9,
                'is_published' => true,
                'rera_number' => 'P50500029443',
                'badge' => 'Ongoing',
            ],
            [
                'name' => 'Mauli Pride',
                'slug' => 'mauli-pride',
                'project_code' => 'MP-PRIDE',
                'location_slug' => 'wardha-road-shankarpur',
                'address' => 'Wardha Road Corridor, Nagpur',
                'status' => 'completed',
                'short_description' => 'Established flagship residential layout on Wardha Road with clear titles and prime connectivity.',
                'description' => 'Mauli Pride is an established project delivering maximum returns and serene community living on Wardha Road with full legal clearances.',
                'starting_price' => 2000000,
                'display_price' => '₹20.00 Lakhs onwards',
                'total_project_area' => 6,
                'total_plots' => 50,
                'plot_size_range' => '1,000 - 2,000 Sq.Ft.',
                'featured_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&q=80',
                'featured' => false,
                'sort_order' => 10,
                'is_published' => true,
                'rera_number' => 'P50500026781',
                'badge' => 'Completed',
            ],
        ];

        foreach ($projectsData as $pData) {
            $locModel = $locationModels[$pData['location_slug']] ?? null;
            $project = Project::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'name' => $pData['name'],
                    'slug' => $pData['slug'],
                    'project_code' => $pData['project_code'],
                    'location_id' => $locModel ? $locModel->id : null,
                    'address' => $pData['address'] ?? null,
                    'project_type' => 'plotted',
                    'status' => $pData['status'],
                    'short_description' => $pData['short_description'],
                    'description' => $pData['description'],
                    'starting_price' => $pData['starting_price'],
                    'price_unit' => 'INR',
                    'display_price' => $pData['display_price'],
                    'show_price' => true,
                    'total_project_area' => $pData['total_project_area'],
                    'area_unit' => 'Acres',
                    'total_plots' => $pData['total_plots'],
                    'featured_image' => $pData['featured_image'],
                    'desktop_hero' => $pData['featured_image'],
                    'featured' => $pData['featured'],
                    'sort_order' => $pData['sort_order'],
                    'is_published' => $pData['is_published'],
                    'meta_title' => $pData['name'] . ' | RERA Approved Plots in Nagpur',
                    'meta_description' => $pData['short_description'],
                ]
            );

            // Sync amenities
            $project->amenities()->sync($amenityIds);

            // Plot Types
            ProjectPlotType::updateOrCreate(
                ['project_id' => $project->id, 'name' => 'Standard Residential Plot'],
                [
                    'size_from' => 1000,
                    'size_to' => 1800,
                    'unit' => 'sqft',
                    'price' => $pData['starting_price'],
                    'availability' => 'Available',
                    'sort_order' => 1,
                ]
            );

            ProjectPlotType::updateOrCreate(
                ['project_id' => $project->id, 'name' => 'Premium Corner Villa Plot'],
                [
                    'size_from' => 2000,
                    'size_to' => 3500,
                    'unit' => 'sqft',
                    'price' => $pData['starting_price'] * 1.5,
                    'availability' => 'Few Plots Left',
                    'sort_order' => 2,
                ]
            );

            // RERA Registration
            ProjectRERA::updateOrCreate(
                ['project_id' => $project->id, 'rera_number' => $pData['rera_number']],
                [
                    'phase' => 'Phase 1',
                    'rera_url' => 'https://maharera.mahaonline.gov.in',
                    'status' => 'registered',
                    'approval_authority' => 'NMRDA & MahaRERA',
                    'additional_legal_information' => '100% Clear Title, Non-Agricultural NA 44 Order, Release Letter (RL) Sanctioned.',
                    'sort_order' => 1,
                ]
            );

            // Nearby Places
            $places = [
                ['category' => 'Connectivity', 'place_name' => 'Metro Station & Highway', 'distance' => '2.5 km', 'travel_time' => '5 Mins'],
                ['category' => 'Employment', 'place_name' => 'MIHAN SEZ / Infosys / TCS', 'distance' => '4.5 km', 'travel_time' => '8 Mins'],
                ['category' => 'Healthcare', 'place_name' => 'AIIMS & National Cancer Institute', 'distance' => '5.0 km', 'travel_time' => '10 Mins'],
                ['category' => 'Education', 'place_name' => 'IIM Nagpur & DPS School', 'distance' => '3.5 km', 'travel_time' => '7 Mins'],
            ];

            foreach ($places as $idx => $pl) {
                ProjectNearbyPlace::updateOrCreate(
                    ['project_id' => $project->id, 'place_name' => $pl['place_name']],
                    [
                        'category' => $pl['category'],
                        'distance' => $pl['distance'],
                        'travel_time' => $pl['travel_time'],
                        'sort_order' => $idx + 1,
                    ]
                );
            }

            // FAQs
            $faqs = [
                ['question' => 'Is ' . $pData['name'] . ' sanctioned by NMRDA and MahaRERA?', 'answer' => 'Yes, ' . $pData['name'] . ' is 100% legally clear, NMRDA sanctioned, and registered under MahaRERA with registration number ' . $pData['rera_number'] . '.'],
                ['question' => 'Are nationalized bank loans available?', 'answer' => 'Yes, pre-approved bank loans up to 75% are available from SBI, HDFC, ICICI, Axis Bank, and Bank of Maharashtra.'],
                ['question' => 'What is the possession timeline and registry process?', 'answer' => 'Immediate spot registry and individual 7/12 extract transfer upon payment.'],
            ];

            foreach ($faqs as $idx => $faq) {
                ProjectFAQ::updateOrCreate(
                    ['project_id' => $project->id, 'question' => $faq['question']],
                    [
                        'answer' => $faq['answer'],
                        'sort_order' => $idx + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
