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
}
