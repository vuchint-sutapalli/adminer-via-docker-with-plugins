# Docker-based PostgreSQL + Adminer Setup

This project provides a simple, containerized setup for a PostgreSQL database and the Adminer database management tool using Docker Compose.

## Current Setup

The `docker-compose.yml` file defines two services:

1.  **`db` (PostgreSQL Database):**
    *   **Image:** `postgres:alpine`
    *   **Container Name:** `postgres`
    *   **Database:** The database credentials and name are configured via the `.env` file.
    *   **Port:** The database is accessible on your local machine at the port specified in the `DB_PORT` variable in the `.env` file.
    *   **Data Persistence:** A named volume `postgres_data` is used to persist database data.
    *   **Healthcheck:** A healthcheck is in place to ensure the `adminer` service only starts after the database is ready.

2.  **`adminer` (Database Management Tool):**
    *   **Image:** `adminer`
    *   **Port:** Accessible at `http://localhost:8080`.
    *   **Plugins:** This setup uses a number of Adminer plugins, which are loaded from the `adminer-plugins` directory. The currently installed plugins are:
        *   **CodeMirror:** for syntax highlighting in SQL commands.
        *   **Gemini SQL:** for interacting with Google's Gemini AI to get SQL suggestions. Your Gemini API key must be set in the `.env` file.

## How to Use

1.  **Prerequisites:** Make sure you have Docker and Docker Compose installed on your system.
2.  **Configuration:** Create a `.env` file in the `db` directory by copying the `.env.example` file (if it exists) or by creating it from scratch. It should contain the following variables:
    ```bash
    DB_NAME=your_db_name
    DB_USER=your_db_user
    DB_PASSWORD=your_db_password
    DB_PORT=5432
    GEMINI_API_KEY=your_gemini_api_key
    ```
3.  **Start the services:** Open a terminal in the `db` directory and run:
    ```bash
    docker-compose up -d
    ```
4.  **Access Adminer:** Open your web browser and navigate to `http://localhost:8080`.
5.  **Access the database:**
    *   From Adminer: Use the database credentials from your `.env` file. The server name is `db`.
    *   From your local machine: Connect using a PostgreSQL client to `localhost` on the port specified in `DB_PORT`.

## Adding a New Plugin

To add a new Adminer plugin, follow these steps:

1.  **Download the plugin:** Download the plugin's `.php` file and place it in the `db/adminer-plugins/plugins` directory.
2.  **Create a loader file:** Create a new file in the `db/adminer-plugins` directory (e.g., `new-plugin-loader.php`). This file should include the plugin from the `plugins` directory and return a new instance of the plugin's class. For example:

    ```php
    <?php
    require_once(__DIR__ . '/plugins/new-plugin.php');

    return new NameOfPluginClass();
    ?>
    ```

3.  **Mount the loader in Docker Compose:** Open `db/docker-compose.yml` and add a new entry to the `volumes` section for the `adminer` service to mount your new loader file. It should look like this:

    ```yaml
    services:
      # ... other services
      adminer:
        # ... other adminer config
        volumes:
          # ... other volumes
          - ./adminer-plugins/new-plugin-loader.php:/var/www/html/plugins-enabled/new-plugin-loader.php
    ```

4.  **Restart the services:** Run the following command in the `db` directory to apply the changes:
    ```bash
    docker-compose up -d --force-recreate
    ```
