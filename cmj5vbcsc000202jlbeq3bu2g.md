---
title: ""The Missing Guide: Adminer Plugins in Docker for Enhanced Database Management""
datePublished: Sun Dec 14 2025 15:16:43 GMT+0000 (Coordinated Universal Time)
cuid: cmj5vbcsc000202jlbeq3bu2g
slug: the-missing-guide-adminer-plugins-in-docker-for-enhanced-database-management
tags: docker, database, plugins, adminer

---

## Introduction

Setting up Adminer in Docker is super easy, but wait until you dive into the world of plugins, especially custom ones! It can get tricky and challenging, but that's where the fun begins! Finding reliable solutions can feel like a treasure hunt, but don't worry, we've got you covered!

This guide is here to enthusiastically lead you through creating an amazing, containerized database management environment using Docker Compose, Adminer, and custom plugins. We're going to focus on integrating an AI-powered SQL generation plugin (using Gemini or OpenAI) to take your database interactions to a whole new level of awesomeness!

## Why Adminer Plugins in Docker Are Tricky

* Adminer is a single-file PHP app, which isn't set up for easy plugin discovery by default.
    
* Docker adds a layer of isolation with its file system and environment variables.
    
* Common solutions, like mounting a single `plugins-enabled` folder, can be inflexible for custom or parameterized plugins.
    
* There is a lack of clear documentation for setting up complex plugins.
    

## Prerequisites

* essential tools: Docker Engine, Docker Compose. (Link to installation guides tbd).
    
* basic familiarity with Docker/Docker Compose
    

## The Solution Architecture: Elegant Plugin Loading with Docker Compose

Rather than just mapping `plugins-enabled`, we'll use individual loader files. This approach gives better control, especially for plugins that need constructor parameters (like API keys) or specific includes.

## Step-by-Step Implementation

### Project Setup

* **Create Project Directory:** `mkdir adminer-ai && cd adminer-ai`
    
* `docker-compose.yml`: Provide the full `docker-compose.yml` file.
    
    * Explain each service (`db`, `adminer`).
        
    * **Crucially, highlight the** `volumes` section for Adminer and explain the loader file concept.
        
* `.env` File: Explain its purpose and provide the content.
    
    * Emphasize `GEMINI_API_KEY` or `OPENAI_API_KEY`.
        
* **Plugin Directory Structure:**
    
    * `mkdir -p adminer-plugins/plugins`
        
    * `touch adminer-plugins/codemirror-loader.php`
        
    * `touch adminer-plugins/sql-ai-loader.php` (or `gemini-loader.php` if you stick to that name)
        
    * `touch adminer-plugins/plugins/codemirror.php`
        
    * `touch adminer-plugins/plugins/sql-ai-wizard.php` (or `sql-gemini.php`)
        

### Plugin Code

* `codemirror-loader.php`:
    
    PHP
    
    ```php
    <?php
    include_once(__DIR__ . '/plugins/codemirror.php');
    return new Adminer\Plugin\CodeMirror();
    ?>
    ```
    
* `sql-ai-loader.php` (for your `AdminerSqlWizard` class):
    
    PHP
    
    ```php
    <?php
    // sql-ai-loader.php
    include_once(__DIR__ . '/plugins/sql-ai-wizard.php'); // or sql-gpt.php, sql-gemini.php
    
    // Get API key from environment variable
    $apiKey = getenv('GEMINI_API_KEY') ?: getenv('OPENAI_API_KEY'); 
    if (!$apiKey) {
        error_log("AI_API_KEY is not set for AdminerSqlWizard plugin!");
        return null; // Don't load the plugin if API key is missing
    }
    
    return new AdminerSqlWizard($apiKey, 'gpt-4o'); // or 'gemini-1.5-flash', etc.
    ?>
    ```
    
    * **Crucially mention the** `getenv()` for API key and the model name.
        
* `plugins/sql-ai-wizard.php` (The full, corrected plugin code for `AdminerSqlWizard`):
    
    * **Include the final, working version of your** `AdminerSqlWizard` class with all the fixes we discussed:
        
        * `select($database)` hook instead of `head()`.
            
        * `setEditorValue()` and `getEditor()` helpers.
            
        * The robust `processStream()` with the `try...catch` for JSON parsing.
            
    

### Usage and Verification

* **Start Services:** Run `docker compose up -d` to get everything going.
    
* **Access Adminer:** Head over to [`http://localhost:8080`](http://localhost:8080) and log in with your details.
    
* **Verify Functionality:**
    
    * Check out the syntax highlighting (CodeMirror).
        
    * Look for your new "Generate SQL" button or textarea.
        
    * Try out a simple query, like "List all users."
        
    * If you can, share a screenshot of the working interface.
        

### Troubleshooting Common Issues (Based on your experience!)

* **"Call to a member function query() on null"**: This happens when trying to access the database before establishing a connection. Use the `select($database)` hook to fix this issue.
    
* **"Unterminated string in JSON" / "Cannot read properties of undefined"**: These errors occur during streaming or JSON parsing. Use `try...catch` with buffer logic in `processStream()` to handle them.
    
* **Quota Exceeded**: Mention the limits of the free tier and the need to upgrade for production use.
    
* **CORS/CSP Errors**: Mention the `csp()` hook and the `connect-src` header briefly.
    

### Conclusion

* **Recap:** "You now have a fully working, AI-powered Adminer setup in Docker!"
    
* **Future Enhancements:** Here are some ideas for future improvements:
    
    * Better error handling.
        
    * Customizing the AI prompt for specific tasks.
        
    * Adding more Adminer plugins.
        
    * Connecting with other AI models.