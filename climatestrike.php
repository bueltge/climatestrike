<?php # -*- coding: utf-8 -*-
declare(strict_types=1);
/**
 * Plugin Name: Climatestrike
 * Plugin URI:  https://github.com/bueltge/climatestrike
 * Description: A plugin to temporarily disable the website for the Global Climate Strike on 20 September 2019.
 * Version:     1.0.2
 * Author:      Frank Bültge
 * Author URI:  https://bueltge.de
 * License:     GPLv2+
 */

namespace Bueltge\Climatestrike;

use Throwable;

!defined('ABSPATH') && exit;

require_once plugin_dir_path(__FILE__).'./src/Climatestrike.php';

add_action(
    'plugins_loaded',
    static function (): bool {
        try {
            // Array with slugs of pages for an exclude of the replacement page.
            $excludes = apply_filters('climatestrike.excludes', ['/impressum/', '/thueringen-erfahren/']);
            (new Climatestrike())
                ->set(__FILE__, '2019-09-19', $excludes)
                ->run();
        } catch (Throwable $error) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                throw $error;
            }

            return false;
        }

        return true;
    }
);
