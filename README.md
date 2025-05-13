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


# Demo


**Authentication pages**

![2025-05-13 19_54_41-Window](https://github.com/user-attachments/assets/55392c72-0b5b-4dac-8925-bcc87c540678)
![2025-05-13 19_55_41-Window](https://github.com/user-attachments/assets/0036db59-54f6-4009-93cc-2bc27710cf85)


**Landing page**
   ![2025-05-13 19_56_33-Window](https://github.com/user-attachments/assets/f1a10490-12b6-4fe1-a78c-bb0880b8f43a)


**Shopping page**

![2025-05-13 19_56_50-Window](https://github.com/user-attachments/assets/35bf7c3b-5d08-4986-a59d-a9c7eac43d4d)


**Cart page**
![2025-05-13 19_57_24-Window](https://github.com/user-attachments/assets/8b82fd8c-b984-44f8-ba20-f80899beca11)



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
