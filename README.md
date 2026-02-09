# 🍥 Naruto MMORPG Browser Game

![Naruto MMORPG Banner](template/topo.png)

> **A feature-rich browser migration of the classic Naruto game engine, now updated for modern PHP 8 environments.**

## 📖 About the Project

This is a **browser-based MMORPG** set in the universe of **Naruto**. Players create their own ninja, choose a village, and embark on a journey to become the strongest shinobi. The game features a robust RPG system with leveling, missions, battles, and community interaction.

Recently, this project has undergone a significant **modernization effort** to ensure compatibility with **PHP 8.x**, fixing deprecated functions, optimizing database interactions, and resolving frontend issues.

---

## ✨ Key Features

*   **🥋 Character Progression:** detailed stat system (Ninjutsu, Taijutsu, Genjutsu), leveling, and rank exams (Gennin, Chuunin, Jounin, ANBU, Kage).
*   **🏘️ Village System:** Join one of the iconic villages (Leaf, Sand, Mist, Stone, etc.) or become a **Renegade (Akatsuki)**.
*   **⚔️ Combat System:**
    *   **PvE:** Hunt wild beasts, complete missions, and fight NPCs.
    *   **PvP:** Challenge other players in the arena or open world.
    *   **War:** Large scale village wars and territory control.
*   **📜 Mission System:** Hundreds of ranked missions (D to S rank) and special tasks.
*   **🛡️ Items & Equipment:** Shop system, blacksmithing, and rare drops (Legendary Weapons).
*   **🦅 Organizations:** Create or join clans/organizations with exclusive bases and benefits.
*   **🎓 Academy & Jutsus:** Learn hundreds of jutsus from the anime/manga.
*   **🐶 Pet/Summoning System:** Tamable animals and summons to aid in battle.

---

## 🛠️ Technology Stack

*   **Backend:** PHP (Originally 5.x, now **PHP 8.2+ Compatible**)
*   **Database:** MySQL / MariaDB
*   **Frontend:** HTML5, CSS3, JavaScript (jQuery 1.9.0)
*   **Server:** Apache/Nginx (WAMP/XAMPP ready)

---

## 🚀 Installation & Setup

### Prerequisites
*   A web server (Apache/Nginx)
*   PHP 8.0 or higher
*   MySQL Database

### Steps

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/gbernichpro/naruto-browser.git
    ```

2.  **Database Setup**
    *   Create a database usually named `naruto` or `naruto_game`.
    *   Import the SQL dump file provided in the `_sql/` or root directory (if available).

3.  **Configuration**
    *   Navigate to the `_inc/` directory.
    *   Edit `conexao.php` with your database credentials:
        ```php
        $db = mysqli_connect("localhost", "root", "password", "database_name");
        ```

4.  **Run the Game**
    *   Place the project folder in your web server's root (e.g., `www` or `htdocs`).
    *   Access via browser: `http://localhost/Naruto`

---

## 🔧 Recent Updates (Refactoring)

We have successfully revived this legacy codebase with the following fixes:

*   ✅ **PHP 8 Migration:** Replaced deprecated `mysql_*` functions with a custom `mysqli` shim (`_inc/mysqli_shim.php`) and updated `ereg` to `preg_match`.
*   ✅ **Security:** Implemented `anti_sql_injection` and safer session handling.
*   ✅ **Frontend Modernization:** Updated jQuery to v1.9.0, fixed conflicting libraries, and resolved JS syntax errors.
*   ✅ **Asset Fixes:** Restored missing paths and suppressed 404 errors for lost assets.

---

## 🤝 Contributing

Contributions are welcome! Please feel free to check the [Issues](https://github.com/gbernichpro/naruto-browser/issues) tab or submit a Pull Request.

1.  Fork the project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

---

## 📜 License

This project is intended for educational purposes. All Naruto characters and assets are copyright **Masashi Kishimoto**.

**Maintained by [G. Bernich](https://github.com/gbernichpro)**
