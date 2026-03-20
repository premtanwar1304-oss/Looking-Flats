<?php
include 'db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Use prepared statements for security and best practices
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$property = null;
if ($result && $result->num_rows > 0) {
    $property = $result->fetch_assoc();
    // Convert highlights string back to array, ensuring it's always an array for the foreach loop
    if (!empty($property['highlights'])) {
        $property['highlights'] = explode(',', $property['highlights']);
    } else {
        $property['highlights'] = [];
    }
    // Convert gallery string to array
    if (!empty($property['gallery'])) {
        $property['gallery'] = explode(',', $property['gallery']);
    } else {
        $property['gallery'] = [$property['image']]; // Fallback to main image if no gallery
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $property ? htmlspecialchars($property['title']) . ' – Lookingflats' : 'Property Not Found – Lookingflats'; ?></title>
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
        <?php if ($property): ?>
            <section class="page-header">
                <div class="container">
                    <a href="properties.php" class="back-link">← Back to properties</a>
                    <h1><?php echo htmlspecialchars($property['title']); ?></h1>
                    <p><?php echo htmlspecialchars($property['location']); ?></p>
                </div>
            </section>
            <section class="section">
                <div class="container property-detail">
                    <div class="property-detail-main">
                        <!-- Image Slider -->
                        <div class="property-gallery">
                            <div class="slider-container">
                                <?php foreach ($property['gallery'] as $index => $img): ?>
                                    <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="Gallery Image">
                                    </div>
                                <?php endforeach; ?>
                                
                                <?php if (count($property['gallery']) > 1): ?>
                                    <button class="slider-prev" onclick="moveSlide(-1)">&#10094;</button>
                                    <button class="slider-next" onclick="moveSlide(1)">&#10095;</button>
                                <?php endif; ?>
                            </div>
                            <div class="slider-dots">
                                <?php foreach ($property['gallery'] as $index => $img): ?>
                                    <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index; ?>)"></span>
                                <?php endforeach; ?>
                            </div>
                            <!-- Mini Thumbnail Slider -->
                            <div class="thumbnail-gallery">
                                <?php foreach ($property['gallery'] as $index => $img): ?>
                                    <div class="thumb <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index; ?>)">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="Thumbnail">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <p class="property-price large"><?php echo htmlspecialchars($property['price']); ?></p>
                        <p class="property-type"><?php echo htmlspecialchars($property['type']); ?></p>
                        <p class="property-meta-row">
                            <span><?php echo htmlspecialchars($property['area']); ?></span>
                            <span><?php echo htmlspecialchars($property['status']); ?></span>
                        </p>
                        <p class="property-description"><?php echo htmlspecialchars($property['description']); ?></p>
                        <h3>Highlights</h3>
                        <ul class="highlight-list">
                            <?php foreach ($property['highlights'] as $highlight): ?>
                                <li><?php echo htmlspecialchars($highlight); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <aside class="property-detail-sidebar">
                        <div class="info-box">
                            <h3>Share with Buyer</h3>
                            <p>Use WhatsApp or SMS to share this link when talking to your client.</p>
                            <input type="text" value="<?php echo htmlspecialchars('http://localhost/property/loookinflats/property.php?id=' . $id); ?>" readonly>
                        </div>
                        <div class="info-box">
                            <h3>Talk to Dealer</h3>
                            <p>Mention this property ID when calling or chatting.</p>
                            <p class="property-id">Property ID: LKF-<?php echo str_pad((string) $id, 3, '0', STR_PAD_LEFT); ?></p>
                            <a href="contact.php" class="btn-primary full-width">Send enquiry</a>
                        </div>
                    </aside>
                </div>
            </section>
        <?php else: ?>
            <section class="page-header">
                <div class="container">
                    <h1>Property not found</h1>
                    <p>The property you are trying to view is not available.</p>
                    <a href="properties.php" class="btn-primary">Back to properties</a>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                <span class="logo-mark small">L</span>
                <span class="logo-text">Lookingflats</span>
            </div>
            <p>Detail view page for serious buyers. Attach photos and floor plans here later.</p>
            <p class="footer-copy">© <?php echo date('Y'); ?> Lookingflats. All rights reserved.</p>
        </div>
    </footer>

    <script>
        let slideIndex = 0;
        const slides = document.getElementsByClassName("slide");
        const dots = document.getElementsByClassName("dot");
        const thumbs = document.getElementsByClassName("thumb");

        function showSlides(n) {
            if (n >= slides.length) slideIndex = 0;
            if (n < 0) slideIndex = slides.length - 1;
            
            for (let i = 0; i < slides.length; i++) {
                slides[i].classList.remove("active");
                if (dots[i]) dots[i].classList.remove("active");
                if (thumbs[i]) thumbs[i].classList.remove("active");
            }
            
            slides[slideIndex].classList.add("active");
            if (dots[slideIndex]) dots[slideIndex].classList.add("active");
            if (thumbs[slideIndex]) thumbs[slideIndex].classList.add("active");
        }

        function moveSlide(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }
    </script>
    
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/918810433515?text=Hello%2C%20I%20am%20interested%20in%20this%20property%20at%20Lookingflats" class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
        <svg viewBox="0 0 32 32">
            <path d="M16 3C9.373 3 4 8.373 4 15c0 2.637.86 5.08 2.48 7.13L4 29l7.13-2.48A11.93 11.93 0 0 0 16 27c6.627 0 12-5.373 12-12S22.627 3 16 3zm0 22c-1.98 0-3.91-.58-5.54-1.67l-.39-.25-4.23 1.47 1.47-4.23-.25-.39A9.94 9.94 0 0 1 6 15c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10zm5.07-7.75c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.08-.16.18-.32.2-.6.07-.28-.14-1.18-.44-2.25-1.4-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.32.42-.48.14-.16.18-.28.28-.46.09-.18.05-.34-.02-.48-.07-.14-.84-2.01-.22-.53-.45-.46-.61-.47-.16-.01-.34-.01-.52-.01-.18 0-.48.07-.73.34-.25.27-.96.94-.96 2.3s.98 2.67 1.12 2.85c.14.18 1.93 2.95 4.68 4.02.65.28 1.16.45 1.56.58.65.21 1.24.18 1.7.11.52-.08 1.65-.67 1.88-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.18-.53-.32z"/>
        </svg>
    </a>
</body>
</html>
