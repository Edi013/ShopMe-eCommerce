# Setup Guide


1. **Install PHP 8.4.6**
   - Download PHP from the [official website](https://www.php.net/downloads).
   - Unzip the archive.
   - Add the PHP executable path to your system's PATH environment variable.


2. **Install Composer**
   - Follow the installation instructions on the [Composer website](https://getcomposer.org/download/).


3. **Install Symfony CLI**
   - Visit the [Symfony CLI installation page](https://symfony.com/download) and follow the guide for your operating system.


4. **Install PostgreSQL 14-alpine or Install Docker**
   - Secure the PostgreSQL environment either by installing it on your machine or use Docker to run the PostgreSQL prebuilt container
   - Example docker compose file included in ShopMe/ShopMeDb


5. **Enjoy**
   - Run `symfony serve`
     
   
# 🎨Color Palette
<pre>:root {
    --color-bg: #31363F;           /* Main background */
    --color-bg-contrast: #1a2025;  /* Background contrast */
    --color-accent: #6aacaf;       /* Accent buttons, highlights */
    --color-accent-light: #91cacd;
    --color-text: #EEEEEE;         /* Primary readable text */
    --color-black: black;
    --color-muted: #B0B0B0;        /* Muted/secondary text */
    --color-border: #3E444D;       /* Borders */
    --color-hover: #3b888c;        /* Hover state for accents */
    --color-green: green;
    --color-red: red;
}
</pre>
