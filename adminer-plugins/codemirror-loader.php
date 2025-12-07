<?php
// The 'plugins' directory is now inside 'plugins-enabled' from the container's perspective.
require_once(__DIR__ . '/plugins/highlight-codemirror.php');
return new AdminerHighlightCodemirror(); 
?>
