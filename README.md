# SM YouTube Link Manager 🚀

A modern, mobile-first Web App designed to curate and display your active YouTube videos. Built with pure PHP and MySQL, this project features a Linktree-style public interface and a secure, fully functional Admin Dashboard without relying on any heavy JavaScript frameworks or external authenticated APIs.

## ✨ Features

**User Interface (Public)**
* 📱 **Mobile-First Design:** Fully responsive layout optimized for all devices.
* 🌙 **Dark Theme:** Modern, clean, and professional dark UI.
* 🔗 **Linktree-Style Layout:** Clickable profile logo, channel name, and compact video cards.
* 🔒 **Content Protection:** Right-click, text-selection, and zoom shortcuts are strictly disabled.
* 🎬 **Auto-Thumbnails:** Automatically fetches high-quality YouTube thumbnails via oEmbed.

**Admin Panel**
* 🛡️ **Secure Login:** Session-based authentication with `password_hash()` protection.
* 📊 **Single-Page Dashboard:** Manage all aspects of the application from one place.
* ⚙️ **Channel Settings:** Update channel name, description, and logo (Upload or URL).
* 📹 **Video Management:** Add, edit, hide, or delete videos. Set display orders easily.
* 🔄 **Auto-Fetch Titles:** Automatically retrieves video titles from YouTube URLs.
* 🔑 **Security Management:** Update admin username and password directly from the dashboard.

## 🛠️ Technologies Used

* **Backend:** PHP (Vanilla, Standard POST/GET requests)
* **Database:** MySQL (Prepared Statements for security)
* **Styling:** Tailwind CSS (via CDN)
* **Icons:** Font Awesome (via CDN)
* *Note: No AJAX, React, Laravel, or jQuery used. Pure server-side rendering.*

## 📂 Folder Structure

```text
📁 Root
┣ 📁 admin
┃ ┣ 📁 common
┃ ┃ ┣ bottom.php
┃ ┃ ┗ header.php
┃ ┣ index.php
┃ ┗ login.php
┣ 📁 common
┃ ┣ bottom.php
┃ ┣ config.php
┃ ┗ header.php
┣ 📁 upload          # Stores locally uploaded images
┣ index.php          # Public Homepage
┗ install.php        # 1-Click Database Setup
