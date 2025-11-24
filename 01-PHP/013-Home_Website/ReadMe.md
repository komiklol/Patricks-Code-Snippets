# 💻 Home Website - HttpsDoppel.SlashSlash

## 📖 **About the Project**

A Website for Local Usages, featuring small Ideas to make Life a bit easier.
Current Features:
- News Feed
- Delivery Overview with Reviews (WIP)
- Network Access Overview
- Movie and Series Streaming (WIP / Currently not Pushed)
- Login and Session Management

---

## 🚀 **Website Structure** 

```bash
HTTPS://
├── Back/
│   ├── autenticateUser.php
│   ├── home_entry.php
│   ├── lieferdienst_dish_entry.php
│   ├── lieferdienst_restraunt_entry.php
│   ├── logout.php
│   ├── router.php
│   └── Server.php
│
├── Data/
│   ├── Restraunt-Images
│   └── home_entrys.json
│
├── Front/
│   └── assets/
│       ├── Ignorable Bootstrap Files
│       ├── Home.php
│       ├── Lieferdienste.php
│       ├── Login.php
│       └── Netzwerk.php
│
├── vendor
│   └── Ignorable
├── composer.json
└── composer.lock
```

### 🧩 **Front-end**

The `Front` directory contains all user-facing pages and assets:

- **Pages**
    - `Home.php` - Main landing page
    - `Login.php` - User authentication page
    - `Lieferdienste.php` - Delivery services page
    - `Netzwerk.php` - Network page

- **Assets**
    - CSS files (Bootstrap and custom styles)
    - JavaScript files
    - Images (This folder contains design Images [non at the Time])

### 🛠️ **Back-end**

The `Back` directory contains server-side logic:

- `Server.php` - Main server configuration
- `autheticateUser.php` - User authentication logic
- `router.php` - Request routing
- `home_entrys.php` - Home page data handler (News Feed)
- `lieferdienst_dish_entry.php` - Dish entry handler for delivery services
- `lieferdienst_restaurant_entry.php` - Restaurant entry handler
- `logout.php` - User logout handler
---

## 📸 **Screenshots**

Screenshots will follow.
(And so Will Code)