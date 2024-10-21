=== CF7 to Neotel Add-on ===
Contributors: devacid
Tags: Neotel, Neotel Forms, Neotel Forms API, Neotel Forms API Integration, Contact Form 7, Contact Form 7 Add-on, cf7, cf7 Add-on, Contact Form, Form
Donate link: https://devacid.xyz
Requires at least: 3.0.1
Tested up to: 6.1.1 
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin integrates HubSpot forms with Contact Form 7 forms.

== Description ==
This is a plugin which seamlessly integrates HubSpot forms with Contact Form 7 forms.

Once installed and activated, each CF7 form will have a separate settings section where you can add HubSpot form credentials. i.e, Portal ID, Form ID, Form Fields, etc.

Once the plugin is activated you will have the option to enable/disable the form integration. In enabled mode, the status code and response from HubSpot will be stored for the last form submission so you can debug if there are any issues.

Note: This plugin uses the PHP library cURL. Please make sure that the cURL library is enabled on your server.

== Frequently Asked Questions ==

= What details do I need to start using CF7 form with HubSpot Forms? =

The mandatory fields for the integration to work are **Portal ID**, **Form ID** and the **Field Names**.

= Where can I find those details? =

Once you are in your HubSpot dashboard, navigate to Contacts > Forms. Once there, either select an existing form or create a new one and navigate to the Embed section where you can find the portalid and formid. There is a screenshot in the CF7 \"HubSpot Form Integration\" section which displays a sample form settings.

== Changelog ==
= 1.0.0 =
* Initial Release