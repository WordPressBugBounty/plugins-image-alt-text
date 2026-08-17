=== Image Alt Text ===
Author URI: https://rswebstudios.com
Donate link: https://paypal.me/rswebstudios
Plugin URI: https://wordpress.org/plugins/image-alt-text
Contributors: rswebstudios, lakharadk, pruthak911
Tags: alt text, image alt text, ai alt text, bulk alt text, image seo
Requires at least: 5.3
Requires PHP: 7.4
Tested up to: 7.0.4
Stable tag: 4.1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add, edit, and bulk-generate alt text for every WordPress image, manually or automatically using AI. Fix image SEO and accessibility in minutes.

== Description ==

https://www.youtube.com/watch?v=-LJedDFilD0

Most WordPress sites are missing alt text on over half their images, every one a keyword Google can't read and a barrier for screen reader users. Image Alt Text fixes this directly from your WordPress dashboard. View every image with or without alt text, edit inline, copy filenames or titles in bulk, and keep your media library fully optimised without touching individual media files.

Whether you have 50 images or 50,000, Image Alt Text gives you a clear table view of your entire media library, shows exactly which images are missing alt text, and lets you fix them all in bulk, with no page refresh and no clicking through individual media items.

**[Image Alt Text Pro](https://imagealttext.in/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=get_pro&utm_content=readme_description)** adds AI-powered alt text generation using OpenAI, Google Gemini, Claude AI, and Hugging Face, automatically on every upload, or across your entire library in one click.

### Technical Support

We would always love to hear from you about plugin issues, queries, and enhancements. Please use the native support forum so other users with the same question can benefit too. Visit our support on the [Plugin's Forum](https://wordpress.org/support/plugin/image-alt-text/).


== Features ==

1. **Alt text coverage dashboard**: see at a glance what percentage of your images have alt text, with a colour-coded progress bar and with / missing / total counts at the top of every tab.

2. **View all images with alt text**: full table view with inline editing. No page refresh needed.

3. **See every image missing alt text**: dedicated tab showing only images that need attention.

4. **Smart copy filename to alt text**: one-click option to turn the image filename into clean, readable alt text. Automatically removes WordPress size suffixes (e.g. -1024x768, -scaled) and converts hyphens and underscores into words, in bulk or per image.

5. **Smart copy post title to alt text**: populate alt text from the associated post or page title, with separators and capitalisation tidied automatically (intentional brand casing like "iPhone" is preserved).

6. **Bulk actions**: select multiple images and apply alt text updates across all of them at once.

7. **Search and sort**: find any image instantly using table column search and sorting.

8. **Multisite compatible**: works across WordPress multisite networks.

9. **Translation ready**: every interface string (including the JavaScript messages and table UI) is translatable, with bundled German, Spanish, and Portuguese translations and a fresh translation template for the community.

== Pro Features ==

**Image Alt Text Pro gives you 4 leading AI vision providers on a simple yearly plan, with no per-image fees and no credit limits. Bring your own API key and pay the provider directly at their standard rate.**

**Buy Pro** **[Image Alt Text](https://imagealttext.in/pricing/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=get_pro&utm_content=readme_pro_features)**

1. **4 AI Vision Providers: Bring Your Own Key**
Generate highly accurate, SEO-friendly alt text using OpenAI GPT-4o, Google Gemini 2.0 Flash, Claude AI (Haiku), or Hugging Face (Qwen2.5-VL). Connect any provider with your own API key. Write a custom prompt tailored to your site's tone and SEO goals. You pay the AI provider directly, with no markups and no bundles.

2. **Auto-Generate Alt Text on Every Upload**
Set it and forget it. The moment an image is uploaded, alt text is generated automatically using your preferred method (OpenAI, Gemini, Claude, Hugging Face, Post Title, or Filename). No manual steps required.

3. **One-Shot Bulk Actions**
Process every image in your current view with a single click. No selection needed, just pick an action (Copy Title, Copy Filename, or any AI provider) and click Process. Configurable batch size controls how many images are sent to the AI API per request.

4. **Unlimited Bulk AI Selection**
Select any number of images in Bulk Action and run AI generation across all of them. Images are processed one at a time sequentially, with no timeouts or rate-limit errors regardless of selection size.

5. **Send Image Context to AI**
Pass the image filename and the title of the post it is attached to into the AI request, so the alt text reflects real page context (ideal when the post title holds the venue, product, or location). Context usage strength (Strict, Balanced, or SEO) controls how strongly those keywords are used, and an optional Guarantee Keywords setting appends them when the AI leaves them out. Off by default.

6. **AI Output Consistency Control**
Tune how consistent or varied the AI output is across runs, applied to every provider. Lower settings give steady, repeatable alt text; higher settings add variety.

7. **Alt Text Prefix / Postfix**
Wrap generated alt text with fixed text such as a brand name or product type, with independent toggles for Copy Title, Copy Filename, AI generation, and Auto-Add on Upload. Leave the fields empty to change nothing.

8. **Bulk Caption Actions**
Copy post titles or filenames to image captions in bulk. Also supports per-image caption update directly from the image list.

9. **Decorative Image Accessibility (WCAG 2.1)**
Mark any image as decorative in the media library. The plugin automatically injects `role="presentation"` and `aria-hidden="true"` on the frontend so screen readers skip the image entirely. Covers both Gutenberg image blocks and PHP-rendered images.

10. **SEO Quality Score for Every Image**
Every image in your library is rated Excellent, Good, or Needs Work based on alt text quality, so you can prioritise which images need attention first.

11. **Visually Spot Missing Alt Text on Your Live Site**
Enable a frontend highlight to outline images missing alt text directly on your published pages. Instantly see where the gaps are without digging through the media library.

12. **Advanced Date Range Filters**
Filter your image list by Today, Last 7 Days, Last 30 Days, Last 6 Months, Last 12 Months, Last 3 Years, or a custom range, ideal for large media libraries or audits.

13. **Central Settings Panel**
Sidebar-navigated settings page to configure AI providers, API keys, custom prompts, upload automation, batch size, and highlight preferences, all in one place.


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

= How is this plugin different from the default WordPress media library? =

The default WordPress media library requires you to open each image individually to edit its alt text. Image Alt Text shows all your images in a single table, with or without alt text, and lets you edit, copy, or bulk-update alt text without leaving the page or refreshing.

= Why does alt text matter for SEO? =

Alt text is one of the few direct on-page signals Google uses to understand image content. Images without alt text are invisible to search engines, so they cannot be indexed for image search and contribute nothing to your page's keyword relevance. Filling in missing alt text is one of the fastest, lowest-effort SEO improvements you can make to an existing site.

= Does this plugin work with WooCommerce product images? =

Yes. Image Alt Text works with all images in the WordPress media library, including WooCommerce product images, gallery images, and variation images. You can view and update alt text for every product image from the same table interface.

= Can I generate alt text automatically using AI? =

Yes, with [Image Alt Text Pro](https://imagealttext.in/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=get_pro&utm_content=faq_ai). Pro supports OpenAI GPT-4o, Google Gemini, Claude AI, and Hugging Face. Alt text is generated automatically on every image upload with no manual steps. You can also run AI generation across your entire existing library in one click.

= Can the AI use my page content to write more specific alt text? =

Yes, with [Image Alt Text Pro](https://imagealttext.in/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=get_pro&utm_content=faq_context). Its Send Image Context to AI option passes the image filename and the attached post or page title into the AI request, so the alt text can name the real venue, product, or location instead of only describing what is visible in the picture. A context strength setting (Strict, Balanced, or SEO) controls how strongly those keywords are used, and the feature is off by default so you choose when to share that context.

= Can I add my brand or product category to every image's alt text automatically? =

Yes, with [Image Alt Text Pro](https://imagealttext.in/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=get_pro&utm_content=faq_affix). The Alt Text Prefix / Postfix feature wraps a fixed piece of text, such as a brand name or product type, before and after your alt text across your whole library, with no manual editing. You can apply it to copied titles, copied filenames, AI-generated text, and auto-add on upload independently, and leaving the fields empty changes nothing.

= Why does the same image sometimes get slightly different alt text? =

AI models include a small amount of built-in randomness, so identical images can produce slightly different wording on different runs. [Image Alt Text Pro](https://imagealttext.in/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=get_pro&utm_content=faq_consistency) includes an output consistency control that lets you make the AI more consistent and repeatable, so your whole library reads in a uniform style.

= How do I bulk update alt text for existing images? =

Use the Bulk Actions dropdown to copy filenames or post titles to alt text across multiple selected images at once. Select all images on the current page or pick specific ones, choose your action, and apply. Pro users can process the entire library with a single click using One-Shot Bulk Processing.

= Does this plugin help with accessibility compliance? =

Yes. Proper alt text is required for WCAG 2.1 Level AA compliance. The plugin makes it easy to identify and fix images missing alt text across your site. Image Alt Text Pro adds decorative image support, automatically injecting `aria-hidden="true"` so screen readers skip non-informative images entirely.

= Is there any redo option for bulk actions? =

No redo option is available currently. We recommend testing bulk actions on a small selection first before processing your entire library.

= Where can I get help or report an issue? =

Please use the official WordPress.org support forum for this plugin. Posting there helps other users with the same question find the answer too. For Pro support, visit [imagealttext.in](https://imagealttext.in/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=get_pro&utm_content=faq_support).

== Screenshots ==

1. Version 4.1.0 - Images with Alt Text: redesigned smart inline editor with Copy Title to Alt, Copy Filename to Alt, Save/Reset buttons, Size column and Bulk Actions
2. Version 4.1.0 - Images without Alt Text: redesigned layout with bulk checkbox selection, inline alt text editor and improved action buttons


= Minimum Requirements =

* PHP 7.4 or greater is recommended
* MySQL 5.6 or greater is recommended

== Changelog ==

= 4.1.1 =
* Fix - Sorting the image list by Title or Date now works when you click the column heading.

= 4.1.0 =
* Feature - New alt text coverage dashboard at the top of both image tabs: see at a glance what percentage of your images have alt text, with a colour-coded progress bar and with / missing / total counts.
* Feature - Full translation support. Every interface string (including JavaScript messages and the table UI) is now translatable, with bundled German, Spanish, and Portuguese translations and a fresh translation template for the community.
* Improvement - "Copy Filename to Alt" now cleans the filename automatically, removing WordPress size suffixes (e.g. -1024x768, -scaled), converting hyphens and underscores into spaces, and tidying capitalisation. "IMG_0421-1024x768-scaled" becomes "IMG 0421".
* Improvement - "Copy Title to Alt" now tidies separators and capitalisation too, while preserving intentional punctuation and brand casing (e.g. "iPhone" stays "iPhone").
* Fix - Icons now display correctly across the plugin's screens.

= 4.0.2 =
* Improvement - Admin interface visual refresh.

= 4.0.0 =
* Improvement - Completely redesigned admin UI with a cleaner, more modern layout and updated plugin branding.
* Implementation - Smart inline alt text editor with integrated Save and Reset buttons, no page refresh needed.
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
