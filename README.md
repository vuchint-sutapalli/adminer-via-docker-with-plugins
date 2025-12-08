# 🚀 PostgreSQL + Adminer with Gemini AI Integration

This project provides a robust, containerized environment using **Docker Compose** for a PostgreSQL database paired with the **Adminer** database management tool. It features custom plugin integration for enhanced functionality, including **AI-powered SQL generation** using the Gemini API.

## Prerequisites

Before you begin, ensure you have the following installed on your system:
- **Docker Engine**: [Installation Guide](https://docs.docker.com/engine/install/)
- **Docker Compose**: [Installation Guide](https://docs.docker.com/compose/install/) (Included with Docker Desktop).

---

## ⚙️ Configuration

All dynamic settings (passwords, ports, API keys) are managed via a **`.env`** file.

### 1. Create the `.env` File

Create a file named `.env` by copying the provided sample:

```sh
# On Windows (Command Prompt)
copy .env.sample .env

# On macOS/Linux
cp .env.sample .env
```

Now, open the `.env` file and populate it with your desired credentials and Gemini API key:

```ini
# PostgreSQL Database Settings
DB_NAME=your_app_db
DB_USER=app_user
DB_PASSWORD=secret_password_123
DB_PORT=5432

# Gemini AI Key for Adminer Plugin
GEMINI_API_KEY=AIzaSy...your_gemini_key_here
```

### 2. Plugin Structure and Loading

This setup uses separate loader files for each plugin, mounted directly into the Adminer container. This is a highly reliable method for loading parameterized plugins.

| Local File | Container Path | Purpose |
| :--- | :--- | :--- |
| `./adminer-plugins/codemirror-loader.php` | `/var/www/html/plugins-enabled/codemirror-loader.php` | Loads the CodeMirror plugin for syntax highlighting. |
| `./adminer-plugins/gemini-loader.php` | `/var/www/html/plugins-enabled/gemini-loader.php` | Loads the Gemini SQL plugin, passing the `GEMINI_API_KEY`. |
| `./adminer-plugins/plugins/` | `/var/www/html/plugins-enabled/plugins/` | Contains the raw plugin `.php` files themselves. |

---

## 🚀 Usage

### 1. Start the Services

Open a terminal in the project's root directory and run Docker Compose:

```sh
docker compose up -d
```
The `-d` flag runs the containers in detached mode.

### 2. Stop the Services

To stop and remove the containers, networks, and volumes, run:

```sh
docker compose down
```

### 3. Access Adminer

Once the services are running, open your web browser and navigate to: **[http://localhost:8080](http://localhost:8080)**

Use the following credentials (from your `.env` file) to log in:

| Login Field | Value |
| :--- | :--- |
| **System** | `PostgreSQL` |
| **Server** | `db` |
| **Username** | `${DB_USER}` |
| **Password** | `${DB_PASSWORD}` |
| **Database** | `${DB_NAME}` |


### 4. Verify Plugin Functionality

Navigate to the **SQL Command** area in Adminer. You should see:
- **Syntax Highlighting**: The SQL editor will have color-coded syntax (from CodeMirror).
- **Gemini AI**: A dedicated "Ask Gemini" text area will appear below the editor for AI-powered SQL generation.

---

## ➕ Adding a New Plugin

To integrate any new Adminer plugin, follow these steps:

1.  **Download and Place**: Download the plugin's `.php` file and place it inside the `./adminer-plugins/plugins/` directory.

2.  **Create a Loader File**: Create a new PHP file in the `./adminer-plugins/` directory (e.g., `new-plugin-loader.php`). This file must include the plugin and return its new instance.

    ```php
    <?php
    // new-plugin-loader.php
    include_once(__DIR__ . '/plugins/new-plugin.php');
    
    return new NameOfPluginClass();
    ?>
    ```

3.  **Mount the Loader**: Update your **`compose.yml`** to mount this new loader file into the `adminer` service's volumes.

    ```yaml
    # compose.yml (partial)
    services:
      adminer:
        # ...
        volumes:
          # ... existing mounts
          - ./adminer-plugins/new-plugin-loader.php:/var/www/html/plugins-enabled/new-plugin-loader.php
    ```

4.  **Restart**: Run `docker compose up -d --force-recreate` to apply the changes.
