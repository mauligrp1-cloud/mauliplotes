<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display global system settings
     */
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'Mauli Infra'),
            'site_tagline' => Setting::get('site_tagline', 'Plotted Real Estate Developers in Nagpur'),
            'company_phone' => Setting::get('company_phone') ?: Setting::get('phone', '+91 87880 74549'),
            'company_email' => Setting::get('company_email') ?: Setting::get('email', 'sales@mauliinfra.com'),
            'whatsapp' => Setting::get('whatsapp', '+91 87880 74549'),
            'office_hours' => Setting::get('office_hours', 'Mon – Sat: 9:30 AM – 7:00 PM'),
            'office_address' => Setting::get('office_address', 'Prince Castle, Plot No. 105, Opp. Madhav Netralay, Gajanan Nagar, Wardha Road, Nagpur, Maharashtra - 440015'),
            'google_analytics_id' => Setting::get('google_analytics_id', ''),
            'facebook_pixel_id' => Setting::get('facebook_pixel_id', ''),
            'whatsapp_widget_enabled' => Setting::get('whatsapp_widget_enabled', '1'),
            'notification_email' => Setting::get('notification_email', 'leads@mauliinfra.com'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update global system settings
     */
    public function update(Request $request)
    {
        $phone = $request->input('company_phone', '');
        $whatsapp = $request->input('whatsapp', '');
        $email = $request->input('company_email', '');
        $address = $request->input('office_address', '');
        $officeHours = $request->input('office_hours', 'Mon – Sat: 9:30 AM – 7:00 PM');

        Setting::set('site_name', $request->input('site_name', 'Mauli Infra'), 'general');
        Setting::set('site_tagline', $request->input('site_tagline', ''), 'general');
        Setting::set('company_phone', $phone, 'contact');
        Setting::set('phone', $phone, 'contact');
        Setting::set('whatsapp', $whatsapp, 'contact');
        Setting::set('company_email', $email, 'contact');
        Setting::set('email', $email, 'contact');
        Setting::set('office_address', $address, 'contact');
        Setting::set('office_hours', $officeHours, 'contact');
        Setting::set('google_analytics_id', $request->input('google_analytics_id', ''), 'analytics');
        Setting::set('facebook_pixel_id', $request->input('facebook_pixel_id', ''), 'analytics');
        Setting::set('whatsapp_widget_enabled', $request->boolean('whatsapp_widget_enabled') ? '1' : '0', 'general');
        Setting::set('notification_email', $request->input('notification_email', ''), 'notifications');

        return redirect()->route('admin.settings.index')->with('success', 'Global system settings updated successfully.');
    }
}
