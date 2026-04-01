=== Image Alt Text ===
Author URI: https://rswebstudios.com
Donate link: https://paypal.me/rswebstudios
Plugin URI: https://wordpress.org/plugins/image-alt-text
Contributors: rswebstudios, lakharadk, pruthak911
Tags: image alt text, image alternative text, image alternative, image alt, alt text
Requires at least: 5.3
Requires PHP: 7.4
Tested up to: 6.9
Stable tag: 4.0.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Image Alt Text plugin allows to edit image alternative text of uploaded media. It allows to update alternative text in existing media and image without alt text.

== Description ==

https://www.youtube.com/watch?v=-LJedDFilD0

The "Image Alt Text" plugin is a simple yet powerful tool that allows website owners and content creators to easily edit the alt tags for images on their website. Alt tags, or alternative text descriptions, are important for both accessibility and search engine optimization (SEO) purposes, as they provide a textual description of an image for users who are visually impaired or for search engines that cannot interpret images.

With this plugin, website owner and admins can easily edit alt tags for all images on their website directly from the Plugin Menu. The plugin provides a user-friendly interface that allows users to quickly update alt tags for individual images or copy alt text for all missing images. Additionally, the plugin includes a feature to generate alt tags based on the image file name, saving users time and effort.

### Technical Support
 
We would always love to hear from you about plugin issue, queries and enhancements. Please use native support forum to benefit other users also if they have the same issue. Visit our support on the [Plugin's Forum](https://wordpress.org/support/plugin/image-alt-text/).


== Features == 

1. Provides a list existing media alt text details in table format so user can edit alternative text.

2. Provides a list of media file for missing alternative text to update.

3. Provides a option in both existing and missing alternative list to copy file name as alternative media file.
	
4. Provides a dropdown to update alternative text to missing alternative media file in bulk.

5. Search images easily with available table column header.

6. Sort image table data in ascending and descending order to easily find the images.

7. **Plugin is available for multisite also.**

== Pro Features ==

**Unlike other AI alt text plugins that charge per image or lock you into expensive monthly plans, Image Alt Text Pro runs on a yearly subscription with no per-image fees, no credit limits, and no third-party API account required. Built-in Smart Auto-Fill generates unlimited alt text included in your plan. For users who want GPT-4o quality, simply connect your own OpenAI key and pay OpenAI directly at their standard rate.**

**Buy Pro** **[Image Alt Text](https://imagealttext.in/)**

1. **Smart Auto-Fill — Unlimited Alt Text, No API Key, No Per-Image Fees**
Generate alt text for your entire media library using built-in Smart Auto-Fill — unlimited, private, and fully included in your Pro plan. No third-party API account needed, no external service, no per-image charges. Perfect for sites with hundreds or thousands of images.

2. **OpenAI / ChatGPT Integration — Use Your Own API Key**
Already have an OpenAI account? Connect your own API key and generate highly descriptive, context-aware alt text using GPT-4o Vision. Write a custom prompt tailored to your site's tone and SEO goals. You pay OpenAI directly at their standard rate — no markups, no bundles.

3. **Auto-Generate Alt Text on Every Upload**
Set it and forget it. The moment an image is uploaded, alt text is generated automatically using your preferred method (Smart Auto-Fill, OpenAI, Post Title, or Filename). No manual steps required.

4. **SEO Quality Score for Every Image**
Every image in your library is rated Excellent, Good, or Poor based on alt text quality — so you can prioritise which images need attention first.

5. **Visually Spot Missing Alt Text on Your Live Site**
Enable a frontend highlight to outline images missing alt text directly on your published pages. Instantly see where the gaps are without digging through the media library.

6. **Advanced Date Range Filters**
Filter your image list by Today, Last 7 Days, Last 30 Days, Last 6 Months, Last 12 Months, Last 3 Years, or a custom range — ideal for large media libraries or audits.

7. **Central Settings Panel**
One place to configure AI method, OpenAI key + custom prompt, upload automation, and highlight preferences.


== Installation ==

**From Your WordPress Dashboard**
 
1. Go to Plugins >**Add New**
2. Search for **Image Alt Text**
3. Click on **Install Now** Button
4. Click on **Activate Now** Button to use plugin in your site.
 
**From WordPress.org**
 
1. Download **[Image Alt Text](https://wordpress.org/plugins/image-alt-text)**
2. Upload the **image-alt-text** folder to the /wp-content/plugins/ directory
3. Activate **Image Alt Text** plugin from your plugins page.

== Frequently Asked Questions ==

= How is this plugin different from default WordPress media? = 
 
This plugin allow you to easily edit the alternative text of images on the same page. It is easy for images with different parameters without page refresh.

= Why Use Bulk Alt Text? =

* Improve **Image SEO** by ensuring images have descriptive alt text.
* Boost **accessibility** for screen readers and assistive technologies.
* Save time by updating alt text for many images at once.
* Optimize existing media libraries without editing images individually.

This feature is especially useful for websites with large media libraries that need quick alt text optimization for better search engine visibility and accessibility compliance.

= Is there any redo button for Bulk Alt Text button? = 
 
No, there is not redo button for now.

= Where can I ask for help? = 
 
Please reach out via the official support forum on WordPress.org.

== Screenshots ==

1. Version 4.0.0 - Images with Alt Text — redesigned smart inline editor with Copy Title to Alt, Copy Filename to Alt, Save/Reset buttons, Size column and Bulk Actions
2. Version 4.0.0 - Images without Alt Text — redesigned layout with bulk checkbox selection, inline alt text editor and improved action buttons


= Minimum Requirements =

* PHP 7.4 or greater is recommended
* MySQL 5.6 or greater is recommended

== Changelog ==

= 4.0.0 =
* Improvement - Completely redesigned admin UI with a cleaner, more modern layout and updated plugin branding.
* Implementation - Smart inline alt text editor with integrated Save and Reset buttons — no page refresh needed.
* Implementation - "Copy Title to Alt" row action button added directly inside the Title column for one-click alt text population.
* Implementation - "Copy Filename to Alt" row action button added inside the URL column to use the image filename as alt text.
* Implementation - Bulk Actions dropdown allowing Copy Title or Copy Filename to alt text across multiple selected images at once.
* Implementation - Bulk checkbox selection column added for selecting individual or all images on the current page.
* Implementation - File size column added to the image list for quick reference.
* Improvement - Date column now displays a human-readable relative time (e.g. "5 months ago") alongside the formatted date.
* Improvement - Action buttons redesigned as icon buttons (Edit and View) replacing the previous text-based Update button.
* Improvement - "Attached To" column removed for a more focused and less cluttered table layout.
* Improvement - Tabs renamed from "With Alt" / "Without Alt" to "With Alt Text" / "Without Alt Text" for clarity.
* Security - SQL queries in uninstall routine updated to use prepared statements.
* Security - Removed server filesystem path exposure from JavaScript localization data.
* Security - Replaced deprecated extract() with explicit variable assignment in view rendering.
* Security - Consistent input sanitization and output escaping applied throughout all AJAX handlers and HTML output.

= 3.0.0 =
* Improvement - Layout to provide a more intuitive and user-friendly interface.
* Implementation - Introduced functionality for easily adding alt text by associated page/post + Image title through both row actions and bulk actions.
* Implementation - Performance enhancements to reduce load times and improve overall system responsiveness.

= 2.0.0 =
* Implementation - Available for multisite installed
* Improvement - Data loading with fast speed

= 1.0.0 =

Release Date: February 28th, 2023
* Initial release on WordPress.org.