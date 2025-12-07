<?php
// The 'plugins' directory is now inside 'plugins-enabled' from the container's perspective.
require_once(__DIR__ . '/plugins/sql-gemini.php');

// Use the environment variable for the API key.
return new AdminerSqlGemini(getenv('GEMINI_API_KEY'));
