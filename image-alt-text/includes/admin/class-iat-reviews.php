<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class to handle WordPress.org reviews
 */
class Iat_Reviews
{
    /**
     * Plugin slug on WordPress.org
     */
    private $plugin_slug = 'image-alt-text';

    /**
     * Transient key for caching reviews
     */
    private $transient_key = 'iat_wordpress_reviews';
    /**
     * Cache duration (24 hours)
     */
    private $cache_duration = 86400; // 24 * HOUR_IN_SECONDS

    public function __construct()
    {
        add_action('iat_refresh_reviews_cache', array($this, 'iat_refresh_reviews_cache'));
    }

    /**
     * Get reviews from WordPress.org
     * 
     * @param int $limit Number of reviews to fetch (default: 5)
     * @return array Array of reviews
     */
    public function iat_get_reviews($limit = 5)
    {
        // Sanitize limit
        $limit = absint($limit);
        if ($limit < 1) {
            $limit = 3;
        }

        // Return cached reviews immediately if available
        $cached_reviews = get_transient($this->transient_key);
        if ($cached_reviews !== false && is_array($cached_reviews)) {
            return $this->limit_reviews($cached_reviews, $limit);
        }

        // No cache — schedule a background refresh and return fallback immediately
        $this->iat_schedule_cache_refresh();
        return $this->limit_reviews($this->get_fallback_reviews(), $limit);
    }

    /**
     * Get ALL reviews from WordPress.org (no limit)
     * Fetches all reviews regardless of star rating
     * 
     * @return array Array of all reviews
     */
    public function iat_get_all_reviews()
    {
        // Return cached reviews immediately if available
        $cached_reviews = get_transient($this->transient_key);
        if ($cached_reviews !== false && is_array($cached_reviews)) {
            return $cached_reviews;
        }

        // No cache — schedule a background refresh and return fallback immediately
        $this->iat_schedule_cache_refresh();
        return $this->get_fallback_reviews();
    }

    /**
     * Fetch reviews from WordPress.org API
     * 
     * @return array Array of reviews
     */
    private function fetch_from_api()
    {
        $reviews = array();

        // WordPress.org API endpoint for reviews
        // Note: The API returns only the first page of reviews (typically 10-15 reviews)
        // There's no official way to fetch all reviews via API pagination
        $api_url = sprintf(
            'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=%s&request[fields][reviews]=true',
            rawurlencode($this->plugin_slug)
        );

        $response = wp_remote_get($api_url, array(
            'timeout' => 15,
            'sslverify' => true,
            'user-agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url')
        ));

        if (is_wp_error($response)) {
            return array();
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            return array();
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Check if reviews section exists and contains HTML
        if (!is_array($data) || !isset($data['sections']['reviews']) || !is_string($data['sections']['reviews'])) {
            return array();
        }

        $reviews = $this->parse_reviews_html($data['sections']['reviews']);

        return $reviews;
    }

    /**
     * Parse reviews from HTML content
     * 
     * @param string $html HTML content containing reviews
     * @return array Parsed reviews
     */
    private function parse_reviews_html($html)
    {
        $reviews = array();

        if (empty($html)) {
            return $reviews;
        }

        // Load HTML into DOMDocument
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);

        // Add proper HTML encoding
        // mb_convert_encoding handling of HTML entities is deprecated in newer PHP versions.
        // Use mb_encode_numericentity when available, falling back to htmlentities.
        $convmap = array(0x80, 0x10FFFF, 0, 0xFFFF);
        if (function_exists('mb_encode_numericentity')) {
            $html = mb_encode_numericentity($html, $convmap, 'UTF-8');
        } else {
            $html = htmlentities($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // Find all review divs
        $review_divs = $xpath->query("//div[@class='review']");

        if ($review_divs->length === 0) {
            // Try alternate query
            $review_divs = $xpath->query("//div[contains(@class, 'review')]");
        }

        foreach ($review_divs as $review_div) {
            // Extract title
            $title_nodes = $xpath->query(".//h4[@class='review-title']", $review_div);
            $title = $title_nodes->length > 0 ? trim((string)$title_nodes->item(0)->textContent) : '';

            // Extract rating
            $rating_nodes = $xpath->query(".//div[@class='wporg-ratings']/@data-rating", $review_div);
            $rating = 5;
            if ($rating_nodes->length > 0) {
                $rating = intval((string)$rating_nodes->item(0)->nodeValue);
            }

            // Extract author name
            $author_nodes = $xpath->query(".//a[@class='reviewer-name']", $review_div);
            $author = 'Anonymous';
            if ($author_nodes->length > 0) {
                $author_text = trim((string)$author_nodes->item(0)->textContent);
                // Remove text in parentheses like (username)
                $author = preg_replace('/\s*\(.*?\)\s*/', '', $author_text);
                $author = trim($author);
                if (empty($author)) {
                    $author = 'Anonymous';
                }
            }

            // Extract avatar from reviewer section
            $avatar = '';
            $avatar_nodes = $xpath->query(".//p[@class='reviewer']//img/@src", $review_div);
            if ($avatar_nodes->length > 0) {
                $avatar = (string)$avatar_nodes->item(0)->nodeValue;
            } else {
                $avatar_nodes = $xpath->query(".//div[@class='reviewer-info']//img/@src", $review_div);
                if ($avatar_nodes->length > 0) {
                    $avatar = (string)$avatar_nodes->item(0)->nodeValue;
                }
            }

            // Validate and handle avatar
            if (!empty($avatar) && !filter_var($avatar, FILTER_VALIDATE_URL)) {
                $avatar = '';
            }

            // Resize Gravatar URL for better quality, or generate fallback
            if (!empty($avatar)) {
                $avatar = $this->resize_gravatar_url($avatar, 200);
            } else {
                $avatar = $this->generate_gravatar_url($author);
            }

            // Extract date
            $date_nodes = $xpath->query(".//span[@class='review-date']", $review_div);
            $date = $date_nodes->length > 0 ? trim((string)$date_nodes->item(0)->textContent) : '';

            // Extract review content
            $content_nodes = $xpath->query(".//div[@class='review-body']//p", $review_div);
            $content = '';
            if ($content_nodes->length > 0) {
                foreach ($content_nodes as $p_node) {
                    $text = trim((string)$p_node->textContent);
                    if (!empty($text)) {
                        $content .= $text . ' ';
                    }
                }
                $content = trim($content);
            }

            // Only add if we have at least a title or content
            if (!empty($title) || !empty($content)) {
                $reviews[] = array(
                    'title' => sanitize_text_field((string)$title),
                    'rating' => absint($rating),
                    'author' => sanitize_text_field((string)$author),
                    'avatar' => esc_url_raw((string)$avatar),
                    'date' => sanitize_text_field((string)$date),
                    'content' => $this->sanitize_review_content((string)$content, 35),
                );
            }
        }

        return $reviews;
    }
    /**
     * Sanitize and trim review content
     * 
     * @param string $content Review content
     * @param int $words Number of words to trim (default: 30)
     * @return string Sanitized content
     */
    private function sanitize_review_content($content, $words = 30)
    {
        // Sanitize words parameter
        $words = absint($words);
        if ($words < 1) {
            $words = 30;
        }

        // Ensure content is a string
        $content = (string)$content;

        // Strip HTML tags
        $content = wp_strip_all_tags($content);

        // Trim to specific word count
        $content = wp_trim_words($content, $words, '...');

        // Sanitize
        return sanitize_text_field($content);
    }

    /**
     * Limit reviews array to specific count
     * 
     * @param array $reviews Reviews array
     * @param int $limit Limit count
     * @return array Limited reviews
     */
    private function limit_reviews($reviews, $limit)
    {
        if (count($reviews) > $limit) {
            return array_slice($reviews, 0, $limit);
        }
        return $reviews;
    }

    /**
     * Fallback reviews in case API fails
     * 
     * @return array Fallback reviews
     */
    private function get_fallback_reviews()
    {
        return array(
            array(
                'author' => 'WordPress User',
                'rating' => 5,
                'content' => 'Great plugin for managing alt text! Very helpful for SEO and accessibility.',
                'date' => '',
                'title' => 'Excellent Plugin',
                'avatar' => $this->generate_gravatar_url('WordPress User')
            ),
            array(
                'author' => 'Site Owner',
                'rating' => 5,
                'content' => 'Easy to use and saves time. The bulk actions feature is very useful.',
                'date' => '',
                'title' => 'Time Saver',
                'avatar' => $this->generate_gravatar_url('Site Owner')
            ),
            array(
                'author' => 'Developer',
                'rating' => 5,
                'content' => 'Simple and effective. Does exactly what it says.',
                'date' => '',
                'title' => 'Works Perfectly',
                'avatar' => $this->generate_gravatar_url('Developer')
            ),
            array(
                'author' => 'Blogger',
                'rating' => 5,
                'content' => 'Helped improve my site accessibility and SEO rankings significantly.',
                'date' => '',
                'title' => 'SEO Boost',
                'avatar' => $this->generate_gravatar_url('Blogger')
            ),
            array(
                'author' => 'Agency Owner',
                'rating' => 5,
                'content' => 'Perfect for managing multiple client sites. Highly recommended!',
                'date' => '',
                'title' => 'Great for Agencies',
                'avatar' => $this->generate_gravatar_url('Agency Owner')
            )
        );
    }

    /**
     * Resize Gravatar URL to specified size
     * Only modifies Gravatar URLs to avoid breaking other image sources
     * 
     * @param string $url Gravatar URL
     * @param int $size Desired size (default: 200)
     * @return string Resized Gravatar URL
     */
    private function resize_gravatar_url($url, $size = 200)
    {
        if (empty($url)) {
            return $url;
        }

        // Only modify Gravatar URLs for safety
        if (strpos($url, 'gravatar.com') === false) {
            return $url;
        }

        $size = intval($size);

        // Check if s= parameter exists
        if (preg_match('/([?&])s=\d+/', $url)) {
            // Replace existing s= parameter
            $url = preg_replace('/([?&])s=\d+/', '$1s=' . $size, $url);
        } elseif (strpos($url, '?') !== false) {
            // URL has query params but no s= parameter, add it
            $url .= '&s=' . $size;
        } else {
            // URL has no query params, add s= as first parameter
            $url .= '?s=' . $size;
        }

        return $url;
    }

    /**
     * Generate Gravatar URL for author
     * 
     * @param string $author Author name
     * @return string Gravatar URL
     */
    private function generate_gravatar_url($author)
    {
        $fake_email = sanitize_title($author) . '@wordpress.org';
        $hash = md5(strtolower(trim($fake_email)));
        return "https://secure.gravatar.com/avatar/{$hash}?s=200&d=identicon&r=g";
    }

    /**
     * Schedule a single background cron event to refresh the cache.
     * Does nothing if one is already scheduled.
     */
    private function iat_schedule_cache_refresh()
    {
        if (!wp_next_scheduled('iat_refresh_reviews_cache')) {
            wp_schedule_single_event(time(), 'iat_refresh_reviews_cache');
        }
    }

    /**
     * Cron callback: fetch reviews from API and update the cache.
     * Runs in the background — never blocks a page request.
     */
    public function iat_refresh_reviews_cache()
    {
        $reviews = $this->fetch_from_api();
        if (!empty($reviews)) {
            set_transient($this->transient_key, $reviews, $this->cache_duration);
        }
    }

    /**
     * Clear cached reviews
     *
     * @return bool True on success
     */
    public function clear_cache()
    {
        return delete_transient($this->transient_key);
    }

    /**
     * Format star rating as HTML
     * 
     * @param int $rating Rating value (1-5)
     * @return string Star HTML
     */
    public function get_star_html($rating)
    {
        $rating = intval($rating);
        if ($rating < 1) $rating = 1;
        if ($rating > 5) $rating = 5;

        return str_repeat('⭐', $rating);
    }

    /**
     * Format date for display
     * 
     * @param string $date Date string
     * @return string Formatted date
     */
    public function format_date($date)
    {
        if (empty($date)) {
            return '';
        }

        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return '';
        }

        return date_i18n('F Y', $timestamp);
    }
}
