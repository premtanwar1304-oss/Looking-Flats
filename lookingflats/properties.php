<?php
include 'db.php';

$sql = "SELECT * FROM properties";
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

$sql = "SELECT * FROM properties WHERE 1=1";

if ($filter !== 'All') {
    $escapedFilter = $conn->real_escape_string($filter);
    $sql .= " AND (location LIKE '%$escapedFilter%' OR status LIKE '%$escapedFilter%' OR tag LIKE '%$escapedFilter%')";
}

if (!empty($search)) {
    $escapedSearch = $conn->real_escape_string($search);
    $sql .= " AND (title LIKE '%$escapedSearch%' OR location LIKE '%$escapedSearch%' OR description LIKE '%$escapedSearch%')";
}

if (!empty($type)) {
    $escapedType = $conn->real_escape_string($type);
    $sql .= " AND type LIKE '%$escapedType%'";
}

$result = $conn->query($sql);

$properties = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $properties[$row['id']] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Properties – Lookingflats</title>
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
                <a href="properties.php" class="active">Properties</a>
                <a href="contact.php">Contact</a>
            </nav>
            <a href="contact.php" class="header-cta">Talk to Dealer</a>
        </div>
    </header>
    <main>
        <section class="page-header">
            <div class="container">
                <h1>Available Properties</h1>
                <p>Select from curated flats and plots ideal for serious buyers.</p>
            </div>
        </section>
        <section class="section">
            <div class="container property-layout">
                <div class="property-filters">
                    <h3>Quick Filters</h3>
                    <a href="properties.php?filter=All" class="btn-filter <?php echo ($filter == 'All') ? 'active' : ''; ?>">All</a>
                    <a href="properties.php?filter=Noida" class="btn-filter <?php echo ($filter == 'Noida') ? 'active' : ''; ?>">Noida</a>
                    <a href="properties.php?filter=Greater Noida" class="btn-filter <?php echo ($filter == 'Greater Noida') ? 'active' : ''; ?>">Greater Noida</a>
                    <a href="properties.php?filter=Ready to move" class="btn-filter <?php echo ($filter == 'Ready to move') ? 'active' : ''; ?>">Ready to move</a>
                    <a href="properties.php?filter=New launch" class="btn-filter <?php echo ($filter == 'New launch') ? 'active' : ''; ?>">New launch</a>
                </div>
                <div class="property-grid">
                    <?php foreach ($properties as $id => $property): ?>
                        <article class="property-card">
                            <div class="property-thumb">
                                <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                            </div>
                            <div class="property-card-body">
                                <div class="property-badge small"><?php echo htmlspecialchars($property['tag']); ?></div>
                                <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                                <p class="property-location"><?php echo htmlspecialchars($property['location']); ?></p>
                                <p class="property-type"><?php echo htmlspecialchars($property['type']); ?></p>
                                <p class="property-meta-row">
                                    <span><?php echo htmlspecialchars($property['area']); ?></span>
                                    <span><?php echo htmlspecialchars($property['status']); ?></span>
                                </p>
                                <p class="property-price"><?php echo htmlspecialchars($property['price']); ?></p>
                                <div class="property-actions">
                                    <a href="property.php?id=<?php echo $id; ?>" class="btn-primary">View Details</a>
                                    <a href="contact.php" class="btn-secondary">Share with Buyer</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                <span class="logo-mark small">L</span>
                <span class="logo-text">Lookingflats</span>
            </div>
            <p>Use this page to list real inventory with photos, pricing and offers.</p>
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
