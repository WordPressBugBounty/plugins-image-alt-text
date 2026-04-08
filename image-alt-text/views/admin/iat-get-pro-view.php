<?php
// Security check
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="iat-get-pro-wrapper">
    <!-- Hero Section -->
    <div class="iat-pro-hero">
        <div class="iat-pro-hero-content">
            <div class="iat-pro-badge"><?php esc_html_e('⭐ Premium Version', 'image-alt-text'); ?></div>
            <h1><?php esc_html_e('Upgrade to Image Alt Text Pro', 'image-alt-text'); ?></h1>
            <p class="iat-pro-tagline"><?php esc_html_e('Supercharge Your SEO with AI-Powered Alt Text Generation', 'image-alt-text'); ?></p>
            <div class="iat-pro-hero-buttons">
                <a href="<?php echo esc_url('https://imagealttext.in/pricing/?utm_source=image_alt_text_plugin&utm_medium=plugin&utm_campaign=get_pro&utm_content=hero_button'); ?>" target="_blank" class="iat-btn-primary iat-btn-large">
                    <span class="dashicons dashicons-cart"></span>
                    <?php esc_html_e('Get Pro — Generate Alt Text with AI in Seconds', 'image-alt-text'); ?>
                </a>
            </div>
            <p class="iat-hero-guarantee">
                <span class="dashicons dashicons-shield-alt"></span>
                <?php esc_html_e('30-Day Money-Back Guarantee — No questions asked', 'image-alt-text'); ?>
            </p>
        </div>
        <div class="iat-pro-hero-image">
            <div class="iat-floating-card iat-card-1">
                <span class="dashicons dashicons-yes-alt"></span>
                <span><?php esc_html_e('AI Powered', 'image-alt-text'); ?></span>
            </div>
            <div class="iat-floating-card iat-card-2">
                <span class="dashicons dashicons-chart-line"></span>
                <span><?php esc_html_e('SEO Optimized', 'image-alt-text'); ?></span>
            </div>
            <div class="iat-floating-card iat-card-3">
                <span class="dashicons dashicons-shield"></span>
                <span><?php esc_html_e('WCAG Compliant', 'image-alt-text'); ?></span>
            </div>
            <div class="iat-floating-card iat-card-4">
                <span class="dashicons dashicons-admin-users"></span>
                <span><?php esc_html_e('8,000+ Active Sites', 'image-alt-text'); ?></span>
            </div>
            <div class="iat-floating-card iat-card-5">
                <span class="dashicons dashicons-cloud"></span>
                <span><?php esc_html_e('4 AI Providers', 'image-alt-text'); ?></span>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="iat-features-section">
        <h2 class="iat-section-title"><?php esc_html_e('Powerful Pro Features', 'image-alt-text'); ?></h2>
        <p class="iat-section-subtitle"><?php esc_html_e('Everything you need to optimize your images for search engines and accessibility', 'image-alt-text'); ?></p>

        <div class="iat-features-grid">
            <!-- Feature 1: AI Generation -->
            <div class="iat-feature-card">
                <div class="iat-feature-icon iat-icon-ai">
                    <span class="dashicons dashicons-superhero-alt"></span>
                </div>
                <h3><?php esc_html_e('4 AI Vision Providers', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Generate accurate, SEO-friendly alt text using your choice of leading AI vision APIs. Bring your own key and pay only for what you use.', 'image-alt-text'); ?></p>
                <ul class="iat-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('OpenAI GPT-4o', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Google Gemini 2.0 Flash', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Claude AI (Haiku) & Hugging Face', 'image-alt-text'); ?></li>
                </ul>
            </div>

            <!-- Feature 2: SEO Validation -->
            <div class="iat-feature-card">
                <div class="iat-feature-icon iat-icon-seo">
                    <span class="dashicons dashicons-chart-line"></span>
                </div>
                <h3><?php esc_html_e('SEO Validation System', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Comprehensive SEO checker with 100-point scoring system. Get real-time validation and actionable suggestions.', 'image-alt-text'); ?></p>
                <ul class="iat-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('100-Point Scoring', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Instant Feedback', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Actionable Tips', 'image-alt-text'); ?></li>
                </ul>
            </div>

            <!-- Feature 3: Auto-Add -->
            <div class="iat-feature-card">
                <div class="iat-feature-icon iat-icon-auto">
                    <span class="dashicons dashicons-update"></span>
                </div>
                <h3><?php esc_html_e('Auto-Add on Upload', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Automatically generate alt text the moment an image is uploaded. Choose from 6 methods including all AI providers.', 'image-alt-text'); ?></p>
                <ul class="iat-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('6 Generation Methods', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Automatic Processing', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Skips Images With Existing Alt Text', 'image-alt-text'); ?></li>
                </ul>
            </div>

            <!-- Feature 4: One-Shot Actions -->
            <div class="iat-feature-card">
                <div class="iat-feature-icon iat-icon-bulk">
                    <span class="dashicons dashicons-performance"></span>
                </div>
                <h3><?php esc_html_e('One-Shot Bulk Actions', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Process ALL images with a single click. No selection needed - just choose an action and go!', 'image-alt-text'); ?></p>
                <ul class="iat-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Process All at Once', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Progress Tracking', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Detailed Results', 'image-alt-text'); ?></li>
                </ul>
            </div>

            <!-- Feature 5: Advanced Filters -->
            <div class="iat-feature-card">
                <div class="iat-feature-icon iat-icon-filter">
                    <span class="dashicons dashicons-filter"></span>
                </div>
                <h3><?php esc_html_e('Advanced Date Filters', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Filter images by custom date ranges with quick presets. Find exactly what you need, fast.', 'image-alt-text'); ?></p>
                <ul class="iat-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Custom Date Range', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Quick Presets', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Smart Search', 'image-alt-text'); ?></li>
                </ul>
            </div>

            <!-- Feature 6: Frontend Highlighter -->
            <div class="iat-feature-card">
                <div class="iat-feature-icon iat-icon-highlight">
                    <span class="dashicons dashicons-visibility"></span>
                </div>
                <h3><?php esc_html_e('Missing Alt Highlighter', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Visually identify images missing alt text on your frontend. Only visible to admins when logged in.', 'image-alt-text'); ?></p>
                <ul class="iat-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Visual Indicators', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Custom Color', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Admin Only', 'image-alt-text'); ?></li>
                </ul>
            </div>

            <!-- Feature 7: Decorative Images -->
            <div class="iat-feature-card">
                <div class="iat-feature-icon iat-icon-seo">
                    <span class="dashicons dashicons-universal-access-alt"></span>
                </div>
                <h3><?php esc_html_e('Decorative Image Support', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Mark images as decorative and the plugin automatically injects the correct WCAG attributes on the frontend so screen readers skip them entirely.', 'image-alt-text'); ?></p>
                <ul class="iat-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('role="presentation" + aria-hidden="true"', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Covers Gutenberg & PHP-rendered images', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Full WCAG 2.1 Compliance', 'image-alt-text'); ?></li>
                </ul>
            </div>

            <!-- Feature 8: Caption Actions -->
            <div class="iat-feature-card">
                <div class="iat-feature-icon iat-icon-bulk">
                    <span class="dashicons dashicons-editor-quote"></span>
                </div>
                <h3><?php esc_html_e('Bulk Caption Actions', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Copy post titles or filenames to image captions in bulk — a quick way to enrich your media library without editing each image individually.', 'image-alt-text'); ?></p>
                <ul class="iat-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Copy Title to Caption', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Copy Filename to Caption', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Bulk or Per-Image', 'image-alt-text'); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="iat-how-it-works-section">
        <h2 class="iat-section-title"><?php esc_html_e('Up and Running in Minutes', 'image-alt-text'); ?></h2>
        <p class="iat-section-subtitle"><?php esc_html_e('No complicated setup. No technical knowledge required.', 'image-alt-text'); ?></p>
        <div class="iat-steps-grid">
            <div class="iat-step">
                <div class="iat-step-number">1</div>
                <div class="iat-step-icon"><span class="dashicons dashicons-cart"></span></div>
                <h3><?php esc_html_e('Purchase Pro', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Choose your plan and complete checkout. You\'ll receive your license key instantly by email.', 'image-alt-text'); ?></p>
            </div>
            <div class="iat-step-arrow"><span class="dashicons dashicons-arrow-right-alt"></span></div>
            <div class="iat-step">
                <div class="iat-step-number">2</div>
                <div class="iat-step-icon"><span class="dashicons dashicons-admin-plugins"></span></div>
                <h3><?php esc_html_e('Install & Activate', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Upload the Pro plugin zip and activate it. Your existing settings and images carry over automatically.', 'image-alt-text'); ?></p>
            </div>
            <div class="iat-step-arrow"><span class="dashicons dashicons-arrow-right-alt"></span></div>
            <div class="iat-step">
                <div class="iat-step-number">3</div>
                <div class="iat-step-icon"><span class="dashicons dashicons-superhero-alt"></span></div>
                <h3><?php esc_html_e('Connect & Generate', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Add your AI API key in Settings and start generating accurate alt text for your entire media library.', 'image-alt-text'); ?></p>
            </div>
        </div>
    </div>

    <!-- No Per-Image Fees Banner -->
    <div class="iat-no-fee-section">
        <div class="iat-no-fee-banner">
            <div class="iat-no-fee-icon">
                <span class="dashicons dashicons-money-alt"></span>
            </div>
            <div class="iat-no-fee-content">
                <h3><?php esc_html_e('No Per-Image Fees. Ever.', 'image-alt-text'); ?></h3>
                <p><?php esc_html_e('Bring your own API key and pay the AI provider directly at their standard rate — no markups, no credits, no surprise bills. OpenAI GPT-4o costs roughly $0.001 per image. Process 1,000 images for about $1.', 'image-alt-text'); ?></p>
            </div>
            <a href="<?php echo esc_url('https://imagealttext.in/pricing/?utm_source=image_alt_text_plugin&utm_medium=plugin&utm_campaign=get_pro&utm_content=no_fee_banner'); ?>" target="_blank" class="iat-btn-no-fee">
                <?php esc_html_e('Get Pro Now →', 'image-alt-text'); ?>
            </a>
        </div>
    </div>

    <!-- Comparison Table -->
    <div class="iat-comparison-section">
        <h2 class="iat-section-title"><?php esc_html_e('Free vs Pro Comparison', 'image-alt-text'); ?></h2>
        <div class="iat-comparison-table">
            <table>
                <thead>
                    <tr>
                        <th><?php esc_html_e('Feature', 'image-alt-text'); ?></th>
                        <th><?php esc_html_e('Free', 'image-alt-text'); ?></th>
                        <th class="iat-pro-col"><?php esc_html_e('Pro', 'image-alt-text'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php esc_html_e('View Images With/Without Alt Text', 'image-alt-text'); ?></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Copy Title to Alt Text', 'image-alt-text'); ?></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Copy Filename to Alt Text', 'image-alt-text'); ?></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Bulk Actions', 'image-alt-text'); ?></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Search in Table', 'image-alt-text'); ?></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                        <td><span class="dashicons dashicons-yes"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('OpenAI GPT-4o AI Generation', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Google Gemini AI Generation', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Claude AI Generation', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Hugging Face AI Generation', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('SEO Validation & Scoring', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Auto-Add on Upload (6 Methods)', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('One-Shot Bulk Actions', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Copy Title / Filename to Caption', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Decorative Image Accessibility (WCAG)', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Date Range Filters', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Missing Alt Highlighter', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                    <tr class="iat-highlight-row">
                        <td><strong><?php esc_html_e('Priority Support', 'image-alt-text'); ?></strong></td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes iat-green"></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="iat-testimonials-section">
        <h2 class="iat-section-title"><?php esc_html_e('Loved by WordPress Users', 'image-alt-text'); ?></h2>
        <p class="iat-section-subtitle">
            <?php
            $review_count = !empty($reviews) && is_array($reviews) ? count($reviews) : 0;
            /* translators: %d: number of reviews shown */
            printf(esc_html__('Showing %d most recent reviews from WordPress.org', 'image-alt-text'), $review_count);
            ?>
        </p>

        <div class="iat-slider-container">
            <!-- Navigation Arrows -->
            <button class="iat-slider-btn iat-slider-prev" onclick="iatMoveSlide(-1)" aria-label="<?php esc_attr_e('Previous', 'image-alt-text'); ?>">
                <span class="dashicons dashicons-arrow-left-alt2"></span>
            </button>

            <div class="iat-testimonials-slider-wrapper">
                <div class="iat-testimonials-slider" id="iatReviewsSlider">
                    <?php
                    if (!empty($reviews) && is_array($reviews)) {
                        foreach ($reviews as $review) {
                            $rating = isset($review['rating']) ? intval($review['rating']) : 5;
                            $stars = str_repeat('⭐', $rating);
                            $author = isset($review['author']) ? esc_html($review['author']) : 'WordPress User';
                            $title = isset($review['title']) && !empty($review['title']) ? esc_html($review['title']) : '';
                            $content = isset($review['content']) ? esc_html($review['content']) : '';
                            $date = isset($review['date']) && !empty($review['date']) ? esc_html($review['date']) : '';
                            $avatar = isset($review['avatar']) && !empty($review['avatar']) ? esc_url($review['avatar']) : '';
                    ?>
                            <div class="iat-testimonial">
                                <div class="iat-testimonial-header">
                                    <div class="iat-testimonial-stars"><?php echo esc_html($stars); ?></div>
                                    <div class="iat-testimonial-rating-text"><?php echo esc_html($rating); ?>/5</div>
                                </div>
                                <?php if (!empty($title)) : ?>
                                    <h4 class="iat-testimonial-title"><?php echo esc_html($title); ?></h4>
                                <?php endif; ?>
                                <p class="iat-testimonial-content">"<?php echo esc_html($content); ?>"</p>
                                <div class="iat-testimonial-footer">
                                    <div class="iat-testimonial-author">
                                        <div class="iat-author-avatar">
                                            <?php if (!empty($avatar)) : ?>
                                                <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author); ?>" class="iat-avatar-img" />
                                            <?php else : ?>
                                                <span class="dashicons dashicons-admin-users"></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="iat-author-info">
                                            <strong class="iat-author-name"><?php echo esc_html($author); ?></strong>
                                            <?php if (!empty($date)) : ?>
                                                <span class="iat-review-date"><?php echo esc_html($date); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                    ?>
                        <div class="iat-testimonial">
                            <div class="iat-testimonial-header">
                                <div class="iat-testimonial-stars"><?php echo esc_html('⭐⭐⭐⭐⭐'); ?></div>
                                <div class="iat-testimonial-rating-text"><?php echo esc_html('5'); ?>/<?php echo esc_html('5'); ?></div>
                            </div>
                            <h4 class="iat-testimonial-title"><?php esc_html_e('Excellent Plugin', 'image-alt-text'); ?></h4>
                            <p class="iat-testimonial-content"><?php esc_html_e('"Great plugin for managing alt text! Very helpful for SEO and accessibility."', 'image-alt-text'); ?></p>
                            <div class="iat-testimonial-footer">
                                <div class="iat-testimonial-author">
                                    <div class="iat-author-avatar"><span class="dashicons dashicons-admin-users"></span></div>
                                    <div class="iat-author-info"><strong class="iat-author-name"><?php esc_html_e('WordPress User', 'image-alt-text'); ?></strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="iat-testimonial">
                            <div class="iat-testimonial-header">
                                <div class="iat-testimonial-stars"><?php echo esc_html('⭐⭐⭐⭐⭐'); ?></div>
                                <div class="iat-testimonial-rating-text"><?php echo esc_html('5'); ?>/<?php echo esc_html('5'); ?></div>
                            </div>
                            <h4 class="iat-testimonial-title"><?php esc_html_e('Time Saver', 'image-alt-text'); ?></h4>
                            <p class="iat-testimonial-content"><?php esc_html_e('"Easy to use and saves time. The bulk actions feature is very useful."', 'image-alt-text'); ?></p>
                            <div class="iat-testimonial-footer">
                                <div class="iat-testimonial-author">
                                    <div class="iat-author-avatar"><span class="dashicons dashicons-admin-users"></span></div>
                                    <div class="iat-author-info"><strong class="iat-author-name"><?php esc_html_e('Site Owner', 'image-alt-text'); ?></strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="iat-testimonial">
                            <div class="iat-testimonial-header">
                                <div class="iat-testimonial-stars"><?php echo esc_html('⭐⭐⭐⭐⭐'); ?></div>
                                <div class="iat-testimonial-rating-text"><?php echo esc_html('5'); ?>/<?php echo esc_html('5'); ?></div>
                            </div>
                            <h4 class="iat-testimonial-title"><?php esc_html_e('Works Perfectly', 'image-alt-text'); ?></h4>
                            <p class="iat-testimonial-content"><?php esc_html_e('"Simple and effective. Does exactly what it says."', 'image-alt-text'); ?></p>
                            <div class="iat-testimonial-footer">
                                <div class="iat-testimonial-author">
                                    <div class="iat-author-avatar"><span class="dashicons dashicons-admin-users"></span></div>
                                    <div class="iat-author-info"><strong class="iat-author-name"><?php esc_html_e('Developer', 'image-alt-text'); ?></strong></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <button class="iat-slider-btn iat-slider-next" onclick="iatMoveSlide(1)" aria-label="<?php esc_attr_e('Next', 'image-alt-text'); ?>">
                <span class="dashicons dashicons-arrow-right-alt2"></span>
            </button>
        </div>

        <div class="iat-slider-dots" id="iatSliderDots"></div>

        <div class="iat-view-all-reviews">
            <a href="<?php echo esc_url('https://wordpress.org/support/plugin/image-alt-text/reviews/'); ?>" target="_blank" class="iat-btn-view-reviews">
                <span class="dashicons dashicons-external"></span>
                <?php esc_html_e('View All Reviews on WordPress.org', 'image-alt-text'); ?>
            </a>
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="iat-pricing-section">
        <h2 class="iat-section-title"><?php esc_html_e('Simple, Transparent Pricing', 'image-alt-text'); ?></h2>
        <p class="iat-section-subtitle"><?php esc_html_e('Choose the plan that fits your needs', 'image-alt-text'); ?></p>

        <div class="iat-pricing-grid">
            <!-- Starter -->
            <div class="iat-pricing-card">
                <div class="iat-pricing-header">
                    <h3><?php esc_html_e('Starter', 'image-alt-text'); ?></h3>
                    <div class="iat-price">
                        <span class="iat-currency"><?php echo esc_html('$'); ?></span>
                        <span class="iat-amount"><?php echo esc_html('14.99'); ?></span>
                    </div>
                </div>
                <ul class="iat-pricing-features">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('1 Site License', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('4 AI Providers — Bring Your Own Key', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Auto-Generate Alt Text on Upload', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('One-Shot Bulk Processing', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('SEO Quality Score', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('WCAG 2.1 Decorative Images', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Priority Support', 'image-alt-text'); ?></li>
                </ul>
                <a href="<?php echo esc_url('https://imagealttext.in/product/starter/?utm_source=image_alt_text_plugin&utm_medium=plugin&utm_campaign=get_pro&utm_content=starter'); ?>" target="_blank" class="iat-btn-pricing"><?php esc_html_e('Get Started', 'image-alt-text'); ?></a>
                <p class="iat-pricing-guarantee"><span class="dashicons dashicons-shield-alt"></span> <?php esc_html_e('30-Day Money-Back Guarantee', 'image-alt-text'); ?></p>
            </div>

            <!-- Studio (Most Popular) -->
            <div class="iat-pricing-card iat-popular">
                <div class="iat-popular-badge"><?php esc_html_e('Most Popular!', 'image-alt-text'); ?></div>
                <div class="iat-pricing-header">
                    <h3><?php esc_html_e('Studio', 'image-alt-text'); ?></h3>
                    <div class="iat-price">
                        <span class="iat-currency"><?php echo esc_html('$'); ?></span>
                        <span class="iat-amount"><?php echo esc_html('29.99'); ?></span>
                    </div>
                </div>
                <ul class="iat-pricing-features">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('5 Sites License', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('4 AI Providers — Bring Your Own Key', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Auto-Generate Alt Text on Upload', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('One-Shot Bulk Processing', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('SEO Quality Score', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('WCAG 2.1 Decorative Images', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Priority Support', 'image-alt-text'); ?></li>
                </ul>
                <a href="<?php echo esc_url('https://imagealttext.in/product/studio/?utm_source=image_alt_text_plugin&utm_medium=plugin&utm_campaign=get_pro&utm_content=studio'); ?>" target="_blank" class="iat-btn-pricing iat-btn-popular"><?php esc_html_e('Get Started', 'image-alt-text'); ?></a>
                <p class="iat-pricing-guarantee"><span class="dashicons dashicons-shield-alt"></span> <?php esc_html_e('30-Day Money-Back Guarantee', 'image-alt-text'); ?></p>
            </div>

            <!-- Agency (Best Value) -->
            <div class="iat-pricing-card iat-best-value">
                <div class="iat-popular-badge iat-best-value-badge"><?php esc_html_e('Best Value!', 'image-alt-text'); ?></div>
                <div class="iat-pricing-header">
                    <h3><?php esc_html_e('Agency', 'image-alt-text'); ?></h3>
                    <div class="iat-price">
                        <span class="iat-currency"><?php echo esc_html('$'); ?></span>
                        <span class="iat-amount"><?php echo esc_html('49.99'); ?></span>
                    </div>
                </div>
                <ul class="iat-pricing-features">
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('15 Sites License', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('4 AI Providers — Bring Your Own Key', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Auto-Generate Alt Text on Upload', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('One-Shot Bulk Processing', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('SEO Quality Score', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('WCAG 2.1 Decorative Images', 'image-alt-text'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e('Priority Support', 'image-alt-text'); ?></li>
                </ul>
                <a href="<?php echo esc_url('https://imagealttext.in/product/agency/?utm_source=image_alt_text_plugin&utm_medium=plugin&utm_campaign=get_pro&utm_content=agency'); ?>" target="_blank" class="iat-btn-pricing"><?php esc_html_e('Get Started', 'image-alt-text'); ?></a>
                <p class="iat-pricing-guarantee"><span class="dashicons dashicons-shield-alt"></span> <?php esc_html_e('30-Day Money-Back Guarantee', 'image-alt-text'); ?></p>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="iat-faq-section">
        <h2 class="iat-section-title"><?php esc_html_e('Frequently Asked Questions', 'image-alt-text'); ?></h2>
        <div class="iat-faq-grid">
            <div class="iat-faq-item">
                <h4><?php esc_html_e('Do I need an API key to use Pro?', 'image-alt-text'); ?></h4>
                <p><?php esc_html_e('An API key is only required if you want AI-powered generation. All other Pro features (bulk actions, auto-add, date filters, decorative images, captions) work without one.', 'image-alt-text'); ?></p>
            </div>
            <div class="iat-faq-item">
                <h4><?php esc_html_e('How much does AI generation actually cost?', 'image-alt-text'); ?></h4>
                <p><?php esc_html_e('You pay the AI provider directly at their standard rate. OpenAI GPT-4o costs roughly $0.001 per image — about $1 for 1,000 images. There are no markups or hidden fees from us.', 'image-alt-text'); ?></p>
            </div>
            <div class="iat-faq-item">
                <h4><?php esc_html_e('Is my API key stored securely?', 'image-alt-text'); ?></h4>
                <p><?php esc_html_e('Yes. Your API key is stored in the WordPress database using standard wp_options, the same way WordPress stores all sensitive settings. It is never logged or exposed in the frontend.', 'image-alt-text'); ?></p>
            </div>
            <div class="iat-faq-item">
                <h4><?php esc_html_e('Which AI provider gives the best results?', 'image-alt-text'); ?></h4>
                <p><?php esc_html_e('OpenAI GPT-4o and Google Gemini 2.0 Flash both deliver excellent SEO-friendly alt text. We recommend starting with Gemini — it has a generous free tier so you can test before spending anything.', 'image-alt-text'); ?></p>
            </div>
            <div class="iat-faq-item">
                <h4><?php esc_html_e('Can I use Pro on multiple sites?', 'image-alt-text'); ?></h4>
                <p><?php esc_html_e('Yes. The Starter plan covers 1 site, Studio covers 5 sites, and Agency covers 15 sites. All plans include the full feature set — only the number of site activations differs.', 'image-alt-text'); ?></p>
            </div>
            <div class="iat-faq-item">
                <h4><?php esc_html_e('What is your refund policy?', 'image-alt-text'); ?></h4>
                <p><?php esc_html_e('We offer a 30-day money-back guarantee, no questions asked. If Pro does not work for your site for any reason, contact us within 30 days of purchase for a full refund.', 'image-alt-text'); ?></p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="iat-cta-section">
        <h2><?php esc_html_e('Stop Losing SEO Traffic to Missing Alt Text', 'image-alt-text'); ?></h2>
        <p><?php esc_html_e('Trusted by 8,000+ WordPress sites — Pro unlocks the full power of AI', 'image-alt-text'); ?></p>
        <div class="iat-cta-buttons">
            <a href="<?php echo esc_url('https://imagealttext.in/pricing/?utm_source=image_alt_text_plugin&utm_medium=plugin&utm_campaign=get_pro&utm_content=pricing_page'); ?>" target="_blank" class="iat-btn-cta-primary">
                <span class="dashicons dashicons-cart"></span>
                <?php esc_html_e('Get Pro Now - Starting at $14.99', 'image-alt-text'); ?>
            </a>
        </div>
    </div>
</div>