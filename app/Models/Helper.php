<?php

namespace Models;


class Helper
{
    // Go back to the previous page
    public function back()
    {
        echo "<script> history.back(); </script>";
    }

    // Redirect to a specific route
    public function redirect($url, $toViews = true)
    {
        if ($toViews) {
            echo "<script> window.location.href = '?route=" . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . "'; </script>";
        } else {
            echo "<script> window.location.href =  htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); </script>";
        }
    }

    // Reload the current page
    public function reload()
    {
        echo "<script> location.reload(); </script>";
    }

    // Get the public path
    public function public_path($fileName = null)
    {
        if ($fileName) {
            return ROOT . '/public/' . $fileName;
        }

        return ROOT . '/public/';
    }

    // Get the public URL
    public function public_url($fileName = null)
    {
        $baseUrl = SITE_URL . "/public/";
        return $fileName ? rtrim($baseUrl, '/') . '/' . ltrim($fileName, '/') : $baseUrl;
    }

    // Get the storage path
    public function storage_path($fileName = null)
    {
        if ($fileName) {
            return ROOT . '/api/storage/' . $fileName;
        }

        return ROOT . '/api/storage/';
    }

    // Get the storage URL
    public function storage_url($fileName = null)
    {
        if ($fileName) {
            return SITE_URL . '/api/storage/' . $fileName;
        }
        return SITE_URL . '/api/storage/';
    }
    public function detectURLString(string $text): string
    {
        // The regex pattern to find URLs.
        // It looks for:
        // (https?|ftp)://  - Optional protocol (http, https, ftp)
        // ([^\s\b\n\r]+)   - One or more characters that are not whitespace or word boundary,
        //                     capturing the URL itself.
        $pattern = '/(https?|ftp):\/\/([^\s\b\n\r]+)/i';

        // Use preg_replace_callback to process each found URL
        $cleanedText = preg_replace_callback($pattern, function ($matches) {
            $url = $matches[0]; // The entire matched URL (e.g., "https://example.com/path")
            $displayLink = $url; // Default display text is the full URL

            // Optional: Shorten the display text if the URL is very long
            // You might want to customize this logic.
            // For example, if it's a SharePoint link, you might want to show "Document Link"
            if (strlen($url) > 50) {
                $displayLink = substr($url, 0, 10) . '...' . substr($url, -15);
            }

            // Return the HTML anchor tag
            return "<a href='{$url}' target='_blank' rel='noopener noreferrer'>{$displayLink}</a>";
        }, $text);

        // Additionally, consider converting newlines to <br> tags for display in HTML
        // if your input string might contain line breaks.
        $cleanedText = nl2br($cleanedText);

        return $cleanedText;
    }
}
