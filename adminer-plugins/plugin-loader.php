<?php
/**
 * Adminer Plugins Loader
 * 
 * This file is loaded by the official Adminer image and should return an array
 * of plugin instances to be loaded.
 */

// The 'plugins' directory is mounted at /var/www/html/plugins-enabled/plugins inside the container.
require_once(__DIR__ . '/plugins/sql-gemini.php');
require_once(__DIR__ . '/plugins/highlight-codemirror.php');

return [
    new AdminerSqlGemini(getenv('GEMINI_API_KEY')),
    new AdminerHighlightCodemirror(),
];
