<?php
/**
 * About Page - SastoMahango
 */

define('SASTOMAHANGO_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Item.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../includes/functions.php';

// Get stats
$itemObj = new Item();
$categoryObj = new Category();
$totalProducts = $itemObj->countItems();
$totalMarkets = $itemObj->countMarkets();
$totalCategories = count($categoryObj->getActiveCategories());

$pageTitle = 'About Us';
$metaDescription = 'Learn about SastoMahango - Nepal\'s premier price tracking platform bringing transparency to the marketplace.';
$metaKeywords = 'about sastomahango, price tracking nepal, market transparency, how it works';
$additionalCSS = ['pages/about.css'];

include __DIR__ . '/../includes/header_professional.php';
?>

<!-- Hero Section -->
<section class="about-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="badge-tag">ABOUT SASTOMAHANGO</div>
                <h1 class="hero-title">Bringing <span class="highlight">Transparency</span> to Nepal's Marketplace</h1>
                <p class="hero-subtitle">
                    SastoMahango (सस्तो महँगो) - translating to "Cheap or Expensive?" - is Nepal's first comprehensive marketplace intelligence and service platform. We answer the fundamental question every consumer asks: "Am I paying the right price?"
                </p>
                <p class="hero-subtitle" style="margin-top: 1rem;">
                    Through our community-driven ecosystem, we bridge the gap between consumers, service providers, and market data, bringing unprecedented transparency to Nepal's marketplace.
                </p>
                <div class="hero-stats">
                    <div class="stat">
                        <div class="stat-number"><?php echo $totalProducts; ?>+</div>
                        <div class="stat-label">Products Tracked</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number"><?php echo $totalMarkets; ?>+</div>
                        <div class="stat-label">Markets Covered</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number"><?php echo $totalCategories; ?></div>
                        <div class="stat-label">Categories</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="floating-card">
                        <i class="bi bi-graph-up-arrow"></i>
                        <h3>Real-time Updates</h3>
                        <p>Daily price tracking</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="mission-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mission-card">
                    <div class="icon-wrapper">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h2>Our Mission</h2>
                    <p>To empower every Nepali consumer with accurate, real-time price information and service rates, enabling informed purchasing decisions and protecting them from price exploitation. We're solving the market's information asymmetry - where consumers lack access to current prices, cannot compare across markets without physical visits, and don't know standard service rates for repairs and professional services.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mission-card">
                    <div class="icon-wrapper">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h2>Our Vision</h2>
                    <p>To become Nepal's most trusted marketplace intelligence platform, tracking 500+ items across 50+ markets and providing standard rates for 100+ services (plumbing, electrical, carpentry, repairs). We envision a fair marketplace where consumers can confidently answer: "Is this price fair?" and "What's the standard rate for this service?"</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Problem & Solution -->
<section class="mission-section" style="background: #f9fafb;">
    <div class="container">
        <div class="section-header text-center">
            <div class="badge-tag">THE CHALLENGE</div>
            <h2>Solving Nepal's Market Information Gap</h2>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="mission-card" style="border-left: 4px solid #ef4444;">
                    <div class="icon-wrapper" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h3>The Problem</h3>
                    <ul style="text-align: left; margin-top: 1rem; color: #6b7280; line-height: 1.8;">
                        <li><strong>Price Blindness:</strong> Consumers lack access to current market prices, relying solely on vendor claims</li>
                        <li><strong>No Comparison Tools:</strong> Comparing prices requires time-consuming physical visits to multiple markets (Kalimati, Asan, New Road)</li>
                        <li><strong>Missing Historical Context:</strong> No way to know if prices are seasonally high or low</li>
                        <li><strong>Unknown Service Rates:</strong> Consumers don't know standard rates for services (plumbing, electrical, repairs), making them vulnerable to exploitation</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mission-card" style="border-left: 4px solid #22c55e;">
                    <div class="icon-wrapper" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h3>Our Solution</h3>
                    <ul style="text-align: left; margin-top: 1rem; color: #6b7280; line-height: 1.8;">
                        <li><strong>Real-time Price Intelligence:</strong> Tracking 500+ items across 50+ markets with daily updates</li>
                        <li><strong>Service Rate Information:</strong> Standard rates for 100+ services so consumers know fair pricing</li>
                        <li><strong>Community-Driven Data:</strong> Network of registered agents updating prices and service rates on the ground</li>
                        <li><strong>Historical Trends:</strong> Track price changes over time to make smart purchasing decisions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Target Audience -->
<section class="mission-section">
    <div class="container">
        <div class="section-header text-center">
            <div class="badge-tag">WHO WE SERVE</div>
            <h2>Built for Everyone in Nepal's Marketplace</h2>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="mission-card text-center">
                    <div class="icon-wrapper" style="background: linear-gradient(135deg, #22c55e, #16a34a); margin: 0 auto 1.5rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h3>Everyday Consumers</h3>
                    <p>Individuals looking to buy products or hire services at fair prices without getting scammed. Get instant answers to "Am I paying the right price?"</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mission-card text-center">
                    <div class="icon-wrapper" style="background: linear-gradient(135deg, #10b981, #059669); margin: 0 auto 1.5rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3>Registered Agents</h3>
                    <p>Market explorers who gather data and update prices and service rates, earning experience points and building their reputation in the community.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mission-card text-center">
                    <div class="icon-wrapper" style="background: linear-gradient(135deg, #14b8a6, #0d9488); margin: 0 auto 1.5rem;">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3>Businesses</h3>
                    <p>Companies looking for advertising opportunities through banner placements and sponsored listings to reach engaged consumers actively searching for products.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works" style="background: #f9fafb;">
    <div class="container">
        <div class="section-header text-center">
            <div class="badge-tag">HOW IT WORKS</div>
            <h2>Simple & Transparent Process</h2>
            <p>We make price tracking easy and accessible for everyone</p>
        </div>
        
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">01</div>
                <div class="step-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3>Community Contributors</h3>
                <p>Verified contributors from markets across Nepal submit daily price updates</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">02</div>
                <div class="step-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3>Verification</h3>
                <p>Our admin team reviews and verifies all submitted prices for accuracy</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">03</div>
                <div class="step-icon">
                    <i class="bi bi-cloud-upload-fill"></i>
                </div>
                <h3>Real-time Updates</h3>
                <p>Verified prices are published instantly on our platform</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">04</div>
                <div class="step-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h3>Easy Access</h3>
                <p>Users can search and compare prices anytime, completely free</p>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="values-section">
    <div class="container">
        <div class="section-header text-center">
            <div class="badge-tag">OUR VALUES</div>
            <h2>What Drives Us</h2>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card">
                    <i class="bi bi-shield-fill-check"></i>
                    <h3>Transparency</h3>
                    <p>Open and honest pricing information for all</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <i class="bi bi-check2-circle"></i>
                    <h3>Accuracy</h3>
                    <p>Verified data you can trust and rely on</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <i class="bi bi-heart-fill"></i>
                    <h3>Community</h3>
                    <p>Built by Nepalis, for Nepalis</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sustainability Model -->
<section class="mission-section">
    <div class="container">
        <div class="section-header text-center">
            <div class="badge-tag">SUSTAINABILITY</div>
            <h2>Building a Sustainable Platform</h2>
            <p>We're committed to long-term service through multiple revenue streams</p>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="mission-card" style="height: 100%;">
                    <div class="icon-wrapper" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h3>Business Partnerships</h3>
                    <p style="margin-bottom: 1rem;">We partner with businesses to provide value to both companies and consumers:</p>
                    <ul style="text-align: left; color: #6b7280; line-height: 1.8;">
                        <li><strong>Sponsored Listings:</strong> Featured placement in hero sections with "Sponsored" tags appearing above normal search results</li>
                        <li><strong>Banner Advertisements:</strong> Strategic placements for local businesses to reach targeted audiences</li>
                        <li><strong>SEO-Driven Traffic:</strong> High-volume search terms (e.g., "gold price Nepal", "laptop repair Kathmandu") attract organic traffic, monetized via Google AdSense</li>
                    </ul>
                    <p style="margin-top: 1rem; font-size: 0.875rem; color: #6b7280;">Businesses interested in sponsorship can contact our admin team to negotiate custom contracts tailored to their needs.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mission-card" style="height: 100%;">
                    <div class="icon-wrapper" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <h3>Community Support</h3>
                    <p style="margin-bottom: 1rem;">Our platform thrives on community participation:</p>
                    <ul style="text-align: left; color: #6b7280; line-height: 1.8;">
                        <li><strong>User Donations:</strong> Voluntary contributions from satisfied users who value the service</li>
                        <li><strong>Contributor Network:</strong> Registered agents provide on-ground data collection, ensuring fresh and accurate information</li>
                        <li><strong>Open Platform:</strong> Always free for consumers - no paywalls, no subscriptions</li>
                    </ul>
                    <p style="margin-top: 1rem; font-size: 0.875rem; color: #6b7280;">Your support, whether through contributions or donations, helps us maintain and expand this essential service for all Nepalis.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Join Us CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-card">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <h2>Become a Contributor</h2>
                    <p>Help us build a transparent marketplace for Nepal. Join our network of verified contributors and earn reputation points.</p>
                    <a href="<?php echo SITE_URL; ?>/contributor/register.php" class="btn-cta">
                        Join as Contributor
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="col-lg-6">
                    <h2>Support Our Mission</h2>
                    <p>Love what we do? Help us maintain and expand this free service for all Nepalis through your voluntary support.</p>
                    <a href="#" class="btn-cta" style="background: linear-gradient(135deg, #10b981, #059669);">
                        Support Us
                        <i class="bi bi-heart-fill ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer_professional.php'; ?>
