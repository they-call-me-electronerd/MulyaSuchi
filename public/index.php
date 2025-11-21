<?php
/**
 * Landing Page - Mulyasuchi
 */

// Bootstrap application
define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Logger.php';
require_once __DIR__ . '/../classes/Item.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Home';
$additionalCSS = 'public.css';
$additionalJS = 'ticker.js';

// Get categories with item counts
$categoryObj = new Category();
$categories = $categoryObj->getCategoriesWithItemCounts();

// Get recent items
$itemObj = new Item();
$recentItems = $itemObj->getActiveItems(8);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle . ' - ' . SITE_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Tailwind CSS (CDN for immediate usage) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Manrope', 'sans-serif'],
                        nepali: ['Noto Sans Devanagari', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            crimson: '#DC143C',
                            blue: '#003893',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Manrope', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hero-gradient {
            background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 50%, #3b82f6 100%);
        }
        .nav-link { position: relative; }
        .nav-link::after {
            content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0;
            background-color: #4f46e5; transition: width 0.3s;
        }
        .nav-link:hover::after { width: 100%; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- 1. Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top bg-white/90 backdrop-blur-md shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand font-heading font-extrabold text-2xl tracking-tight text-gray-900" href="<?php echo SITE_URL; ?>">
                <span class="text-indigo-600">Mulya</span>Suchi
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-lg-4 font-medium text-gray-600">
                    <li class="nav-item"><a class="nav-link hover:text-indigo-600 transition-colors" href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link hover:text-indigo-600 transition-colors" href="browse.php">Browse</a></li>
                    <li class="nav-item"><a class="nav-link hover:text-indigo-600 transition-colors" href="#search">Search</a></li>
                    <li class="nav-item"><a class="nav-link hover:text-indigo-600 transition-colors" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link hover:text-indigo-600 transition-colors" href="how-it-works.php">How It Works</a></li>
                </ul>
                
                <div class="d-flex gap-3 mt-3 mt-lg-0 align-items-center">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary rounded-full px-4 font-semibold dropdown-toggle flex items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle text-lg"></i> 
                                <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-xl mt-2 p-2">
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item rounded-lg py-2" href="<?php echo SITE_URL; ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Admin Dashboard</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item rounded-lg py-2" href="<?php echo SITE_URL; ?>/contributor/dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider my-2"></li>
                                <li><a class="dropdown-item rounded-lg py-2 text-red-600 hover:bg-red-50" href="<?php echo SITE_URL; ?>/admin/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/contributor/login.php" class="btn btn-outline-primary rounded-full px-4 font-semibold hover:shadow-lg transition-all">
                            Contributor
                        </a>
                        <a href="<?php echo SITE_URL; ?>/admin/login.php" class="btn bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-full px-4 font-semibold hover:shadow-lg hover:scale-105 transition-all border-0">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Hero Section -->
    <section class="hero-gradient relative overflow-hidden min-vh-80 d-flex align-items-center py-5">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="container position-relative z-10">
            <div class="row align-items-center g-5">
                <!-- Left Content -->
                <div class="col-lg-7 text-white">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full glass-card mb-4 text-sm font-medium text-indigo-100">
                        <span class="flex h-2 w-2 rounded-full bg-green-400"></span>
                        Live Market Intelligence
                    </div>
                    
                    <h1 class="display-3 fw-bold mb-3 tracking-tight leading-tight">
                        Welcome to <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-indigo-200">Mulyasuchi</span>
                    </h1>
                    <p class="lead mb-2 font-nepali text-2xl opacity-90">
                        तपाईंको विश्वसनीय बजार मूल्य सूची प्लेटफर्म
                    </p>
                    <p class="text-lg text-indigo-100 mb-5 max-w-xl leading-relaxed">
                        Your trusted market intelligence platform. Access real-time, accurate, and validated prices for vegetables, fruits, and commodities across Nepal.
                    </p>

                    <!-- Search Bar -->
                    <div class="bg-white p-2 rounded-xl shadow-2xl max-w-lg transform transition-all hover:scale-[1.01]">
                        <form action="browse.php" method="GET" class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-gray-400 ps-3">
                                <i class="bi bi-search text-xl"></i>
                            </span>
                            <input type="text" name="q" class="form-control border-0 shadow-none py-3 text-gray-700 font-medium focus:ring-0" placeholder="Search items... तरकारी, फलफूल, इलेक्ट्रोनिक्स">
                            <button class="btn bg-indigo-600 text-white rounded-lg px-4 py-2 font-semibold hover:bg-indigo-700 transition-colors" type="submit">
                                Search
                            </button>
                        </form>
                    </div>
                    
                    <div class="mt-4 text-sm text-indigo-200 font-medium">
                        Trending: <span class="text-white underline decoration-indigo-400 underline-offset-4">Tomato</span>, <span class="text-white underline decoration-indigo-400 underline-offset-4">Gold</span>, <span class="text-white underline decoration-indigo-400 underline-offset-4">Rice</span>
                    </div>
                </div>

                <!-- Right Content (Glass Cards) -->
                <div class="col-lg-5">
                    <div class="d-flex flex-column gap-4 perspective-1000">
                        <!-- Card 1 -->
                        <div class="glass-card p-4 rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300 ms-lg-auto w-100 max-w-md">
                            <div class="d-flex align-items-center gap-4">
                                <div class="bg-white/20 p-3 rounded-xl text-white">
                                    <i class="bi bi-graph-up-arrow text-2xl"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 font-bold text-white">Live Price Updates</h5>
                                    <p class="mb-0 text-indigo-100 text-sm">Real-time data from markets</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="glass-card p-4 rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300 ms-lg-4 w-100 max-w-md">
                            <div class="d-flex align-items-center gap-4">
                                <div class="bg-white/20 p-3 rounded-xl text-white">
                                    <i class="bi bi-shield-check text-2xl"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 font-bold text-white">Admin Validated</h5>
                                    <p class="mb-0 text-indigo-100 text-sm">100% Verified Information</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="glass-card p-4 rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300 w-100 max-w-md">
                            <div class="d-flex align-items-center gap-4">
                                <div class="bg-white/20 p-3 rounded-xl text-white">
                                    <i class="bi bi-geo-alt text-2xl"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 font-bold text-white">Nepal-Focused</h5>
                                    <p class="mb-0 text-indigo-100 text-sm">Covering 50+ Local Markets</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Live Price Strip -->
    <div class="container relative z-20 -mt-8">
        <div class="bg-white rounded-xl shadow-lg p-3 d-flex align-items-center gap-3 border border-gray-100">
            <div class="d-flex align-items-center gap-2 px-3 py-1 bg-red-50 text-red-600 rounded-full font-bold text-sm whitespace-nowrap">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                LIVE UPDATES
            </div>
            <div class="overflow-hidden w-100">
                <div class="d-flex gap-5 animate-marquee whitespace-nowrap text-sm font-medium text-gray-600" id="priceTicker">
                    <span>Loading latest market prices...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="py-20 bg-white">
        <div class="container">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Browse by Category</h2>
                <p class="text-gray-500">Explore items organized by categories</p>
            </div>

            <div class="row g-4">
                <?php foreach ($categories as $category): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="browse.php?category=<?php echo $category['slug']; ?>" class="group block bg-gray-50 rounded-2xl p-6 text-center hover:bg-white hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                        <div class="w-16 h-16 mx-auto bg-white rounded-full shadow-sm flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform">
                            <?php 
                            $icons = ['vegetables'=>'🥦','fruits'=>'🍎','kitchen-appliances'=>'🍳','study-material'=>'📚','clothing'=>'👕','tools'=>'🔧','electrical-appliances'=>'💡','tech-gadgets'=>'📱','miscellaneous'=>'📦'];
                            echo $icons[$category['slug']] ?? '📦';
                            ?>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($category['category_name']); ?></h3>
                        <p class="text-sm text-gray-500 font-nepali mb-2"><?php echo htmlspecialchars($category['category_name_nepali']); ?></p>
                        <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full">
                            <?php echo $category['item_count']; ?> Items
                        </span>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Items Section -->
    <?php if (!empty($recentItems)): ?>
    <section class="py-20 bg-gray-50">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Market Movers</h2>
                    <p class="text-gray-500">Latest price updates from the market</p>
                </div>
                <a href="browse.php" class="text-indigo-600 font-semibold hover:text-indigo-800 flex items-center gap-1">
                    View All Items <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-4">
                <?php foreach ($recentItems as $item): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 h-100">
                        <div class="relative h-48 bg-gray-100 overflow-hidden group">
                            <?php if ($item['image_path']): ?>
                                <img src="<?php echo UPLOAD_URL . $item['image_path']; ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-4xl font-bold bg-gray-100">
                                    <?php echo mb_substr($item['item_name'], 0, 1); ?>
                                </div>
                            <?php endif; ?>
                            <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded">
                                <?php echo timeAgo($item['updated_at']); ?>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded uppercase tracking-wider">
                                    <?php echo htmlspecialchars($item['category_name']); ?>
                                </span>
                                <?php if ($item['market_location']): ?>
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($item['market_location']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 mb-3 line-clamp-1">
                                <a href="item.php?id=<?php echo $item['item_id']; ?>" class="hover:text-indigo-600 transition-colors">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                </a>
                            </h3>
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm text-gray-500 font-medium">NPR</span>
                                <span class="text-2xl font-extrabold text-gray-900"><?php echo formatPrice($item['current_price']); ?></span>
                                <span class="text-sm text-gray-400">/ <?php echo $item['unit']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h4 class="font-bold text-2xl mb-4">Mulyasuchi</h4>
                    <p class="text-gray-400 mb-4">Empowering Nepal with transparent, real-time market intelligence. Making informed decisions easier for everyone.</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 transition-colors"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 transition-colors"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-indigo-600 transition-colors"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h5 class="font-bold mb-4">Platform</h5>
                    <ul class="list-unstyled text-gray-400 space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">Browse Items</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Live Ticker</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Market Locations</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6">
                    <h5 class="font-bold mb-4">Company</h5>
                    <ul class="list-unstyled text-gray-400 space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="font-bold mb-4">Stay Updated</h5>
                    <form class="input-group">
                        <input type="email" class="form-control bg-gray-800 border-0 text-white focus:ring-0" placeholder="Enter your email">
                        <button class="btn bg-indigo-600 text-white hover:bg-indigo-700" type="button">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
                &copy; <?php echo date('Y'); ?> Mulyasuchi. All rights reserved. Made with ❤️ in Nepal.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Ticker Script -->
    <script>
        // Simple ticker animation
        const ticker = document.getElementById('priceTicker');
        // In a real app, fetch data here
        setTimeout(() => {
            ticker.innerHTML = `
                <span class="mx-4">🍅 Tomato Big: NPR 120 <span class="text-red-500">▼ 5%</span></span>
                <span class="mx-4">🧅 Onion Dry: NPR 85 <span class="text-green-500">▲ 2%</span></span>
                <span class="mx-4">🥔 Potato Red: NPR 65 <span class="text-gray-400">- 0%</span></span>
                <span class="mx-4">🍎 Apple Fuji: NPR 320 <span class="text-green-500">▲ 1.5%</span></span>
                <span class="mx-4">🍌 Banana: NPR 140 <span class="text-red-500">▼ 2%</span></span>
                <span class="mx-4">🥕 Carrot: NPR 90 <span class="text-green-500">▲ 4%</span></span>
            `;
        }, 1000);
    </script>
</body>
</html>
