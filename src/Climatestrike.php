<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Bueltge\Climatestrike;

use DateTime;
use Exception;

class Climatestrike
{

    private $pluginroot;

    private $pluginurl;

    private $strikedate;

    private $excludes;

    /**
     * @param string $root
     * @param string $date
     * @param array $excludes
     *
     * @return Climatestrike
     */
    public function set(string $root, string $date, array $excludes): self
    {
        $this->pluginroot = plugin_dir_path($root);
        $this->pluginurl = plugin_dir_url($root);
        $this->strikedate = $date;
        $this->excludes = $excludes;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        if (!$this->exclude() && $this->timestamp()) {
            wp_cache_flush();
            header('Location: '.$this->placeholderFile(), true, 302);
            exit();
        }
    }

    /**
     * @return bool
     */
    private function exclude(): bool
    {
        if (is_user_logged_in() || is_feed()) {
            return true;
        }
        if (in_array(esc_attr(wp_unslash($_SERVER['REQUEST_URI'])), $this->excludes, true)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function timestamp(): bool
    {
        return $this->strikedate === (new DateTime())->format('Y-m-d');
    }

    /**
     * @return string
     */
    private function placeholderFile(): string
    {
        if (file_exists($this->pluginroot.'assets/placeholder_'.$this->language().'.html')) {
            return $this->pluginurl.'assets/placeholder_'.$this->language().'.html';
        }

        return $this->pluginurl.'assets/placeholder_en.html';
    }

    /**
     * @return string
     */
    private function language(): string
    {
        return strtolower(substr(get_locale(), 0, 2));
    }
}
