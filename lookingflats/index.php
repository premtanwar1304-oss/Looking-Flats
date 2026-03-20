<?php
include 'db.php';

$sql = "SELECT * FROM properties LIMIT 3";
$result = $conn->query($sql);

$featuredProperties = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $featuredProperties[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lookingflats – Smart Property Deals</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <div class="logo">
                <span class="logo-mark">L</span>
                <span class="logo-text">Lookingflats</span>
            </div>
            <nav class="main-nav">
                <a href="index.php">Home</a>
                <a href="properties.php">Properties</a>
                <a href="contact.php">Contact</a>
            </nav>
            <a href="contact.php" class="header-cta">Talk to Dealer</a>
        </div>
    </header>
    <main>
        <section class="hero" id="hero-slider">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <div>
                    <h1>Find Better Flats with Trusted Dealers</h1>
                    <p>Lookingflats helps property dealers showcase verified projects and close more deals with a modern online presence.</p>
                    <form class="hero-search" action="properties.php" method="GET">
                        <input type="text" name="search" placeholder="Search by city, area or project name">
                        <select name="type">
                            <option value="">Property Type</option>
                            <option value="1 BHK">1 BHK</option>
                            <option value="2 BHK">2 BHK</option>
                            <option value="3 BHK">3 BHK</option>
                            <option value="Plot">Plot</option>
                        </select>
                        <button type="submit">Search</button>
                    </form>
                    <div class="hero-meta">
                        <span>Top city focus: Noida • Greater Noida • Noida Extension</span>
                        <span>Dealer friendly • Lead focused design</span>
                    </div>
                </div>
            </div>
        </section>

        <script>
            // Hero Image Slider Logic
            const hero = document.getElementById('hero-slider');
            const images = [
                'wow-monk.jpg',
                'img1.jpg',
                'img2.jpg',
                'img3.jpg'
            ];
            let currentIndex = 0;

            function changeBg() {
                currentIndex = (currentIndex + 1) % images.length;
                hero.style.backgroundImage = `linear-gradient(135deg, rgba(37, 99, 235, 0.8), rgba(16, 185, 129, 0.7)), url("${images[currentIndex]}")`;
            }

            setInterval(changeBg, 5000); // Change image every 5 seconds
        </script>
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2>Featured Properties</h2>
                    <a href="properties.php" class="link-text">View all</a>
                </div>
                <div class="property-grid">
                    <?php foreach ($featuredProperties as $property): ?>
                        <article class="property-card">
                            <div class="property-thumb">
                                <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                            </div>
                            <div class="property-card-body">
                                <div class="property-badge"><?php echo htmlspecialchars($property['tag']); ?></div>
                                <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                                <p class="property-location"><?php echo htmlspecialchars($property['location']); ?></p>
                                <p class="property-type"><?php echo htmlspecialchars($property['type']); ?></p>
                                <p class="property-price"><?php echo htmlspecialchars($property['price']); ?></p>
                                <div class="property-actions">
                                    <a href="property.php?id=<?php echo $property['id']; ?>" class="btn-primary">View Details</a>
                                    <a href="contact.php" class="btn-secondary">Schedule Visit</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section section-alt">
            <div class="container benefits">
                <div class="benefit-block">
                    <h3>For Property Dealers</h3>
                    <ul>
                        <li>Showcase multiple projects on a single clean website</li>
                        <li>Highlight premium inventory and limited period offers</li>
                        <li>Get more qualified leads with clear call to actions</li>
                    </ul>
                </div>
                <div class="benefit-block">
                    <h3>Why Lookingflats</h3>
                    <ul>
                        <li>Modern layout similar to top real estate portals</li>
                        <li>Mobile friendly design for on-the-go buyers</li>
                        <li>Simple structure that can grow with your business</li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container cta-strip">
                <div>
                    <h2>Want this site branded for your properties?</h2>
                    <p>Share your project details, branding and inventory. We will plug everything into this Lookingflats layout for you.</p>
                </div>
                <a href="contact.php" class="btn-primary btn-large">Send Project Details</a>
            </div>
        </section>
    </main>
    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                <span class="logo-mark small">L</span>
                <span class="logo-text">Lookingflats</span>
            </div>
            <p>Demo property dealer website layout. Customize for your own inventory and city.</p>
            <p class="footer-copy">© <?php echo date('Y'); ?> Lookingflats. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/918810433515?text=Hello%2C%20I%20am%20interested%20in%20properties%20at%20Lookingflats" class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
        <svg viewBox="0 0 32 32">
            <path d="M16 3C9.373 3 4 8.373 4 15c0 2.637.86 5.08 2.48 7.13L4 29l7.13-2.48A11.93 11.93 0 0 0 16 27c6.627 0 12-5.373 12-12S22.627 3 16 3zm0 22c-1.98 0-3.91-.58-5.54-1.67l-.39-.25-4.23 1.47 1.47-4.23-.25-.39A9.94 9.94 0 0 1 6 15c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10zm5.07-7.75c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.08-.16.18-.32.2-.6.07-.28-.14-1.18-.44-2.25-1.4-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.32.42-.48.14-.16.18-.28.28-.46.09-.18.05-.34-.02-.48-.07-.14-.61-1.47-.84-2.01-.22-.53-.45-.46-.61-.47-.16-.01-.34-.01-.52-.01-.18 0-.48.07-.73.34-.25.27-.96.94-.96 2.3s.98 2.67 1.12 2.85c.14.18 1.93 2.95 4.68 4.02.65.28 1.16.45 1.56.58.65.21 1.24.18 1.7.11.52-.08 1.65-.67 1.88-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.18-.53-.32z"/>
        </svg>
    </a>
</body>
</html>
