# 📱 SM YouTube Link Manager

A premium, modern, and ultra-responsive mobile-first **YouTube Link Management System** (similar to Linktree) built strictly using native **PHP, MySQL, and Tailwind CSS**. 

This system allows creators to centralize their video content into a single custom dashboard while disabling intrusive interactions like right-click, text selection, and forced zoom to deliver a secure, native app-like experience.

---

## ✨ Features

### 👤 User Front-End (Linktree Style)
* 🌟 **Responsive Dark Theme:** Fully optimized for mobile screens with high-contrast AMOLED aesthetics.
* 🔗 **Interactive Profile Header:** Clickable channel logo and name that seamlessly opens your YouTube channel.
* 🎥 **Dynamic Video Cards:** Custom rounded corners, hover glow effects, and a clean landscape thumbnail layout.
* ⏱️ **Smart Ordering:** Automatically lists active videos by customized display order, sorting newest uploads first.

### 🔐 Administrative Dashboard
* 🛠️ **Dedicated Video Manager:** Easily add, edit, and organize your YouTube links through a clean dashboard.
* ⚙️ **Advanced Settings Panel:** A separate configuration area to manage channel metadata, logos, and admin security credentials.
* 🤖 **Auto-Fetch Content:** Generates YouTube video IDs, extracts official titles through unauthenticated oEmbed APIs, and builds high-quality thumbnail links automatically.
* 📂 **Secure Image Uploads:** Native support for local file uploads (JPG, PNG, WEBP) with server-side validation or direct image URLs.
* 🛡️ **Hardened Security:** Built using PHP Prepared Statements to eliminate SQL Injection, robust session architecture, and `password_hash()` encryption.
* 🚫 **App Protection Script:** Injected blocks to disable Right-click, Text Selection, `Ctrl+U`/`F12` inspection, and pinch-to-zoom on mobile devices.

---

## 📁 Project Structure

```text
📁 sm-youtube-link-manager
┣ 📁 common
┃ ┣ 📄 config.php        # DB connection, security functions & image processor
┃ ┣ 📄 header.php        # UI Document Head, Tailwind Config & global styling
┃ ┗ 📄 bottom.php        # Front-End global footer & security restrictions script
┣ 📁 admin
┃ ┣ 📄 login.php         # Secure administrator access gate
┃ ┣ 📄 index.php         # Dashboard Panel (Video Management)
┃ ┣ 📄 setting.php       # Channel Configuration & Admin Security
┃ ┗ 📁 common
┃   ┣ 📄 header.php      # Admin restricted validation & dynamic navigation
┃   ┗ 📄 bottom.php      # Admin panel execution wrappers
┣ 📁 upload              # Auto-created secure storage for images & logos
┣ 📄 index.php           # Public Front-end Landing Page
┗ 📄 install.php          # Auto-installation and structural creation script
