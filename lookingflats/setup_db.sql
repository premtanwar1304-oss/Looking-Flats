-- Create database
CREATE DATABASE IF NOT EXISTS lookingflats_db;
USE lookingflats_db;

-- Table for properties
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    area VARCHAR(100),
    status VARCHAR(100),
    description TEXT,
    tag VARCHAR(100),
    image VARCHAR(255),
    gallery TEXT, -- Comma separated images
    highlights TEXT, -- Store as JSON or comma separated string
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for enquiries
CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    city VARCHAR(100),
    message TEXT,
    property_id INT, -- Optional, if linked to a property
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for admin users
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin (password: admin123)
-- Using INSERT IGNORE to prevent duplicate error if script runs again
INSERT IGNORE INTO admins (username, password) VALUES ('admin', 'admin123');

-- Insert sample data
INSERT INTO properties (title, price, location, type, area, status, description, tag, image, gallery, highlights) VALUES
('2 BHK Apartment in Noida', '₹82,00,000', 'Sector 150, Noida', '2 BHK • Residential', '1150 sq.ft', 'Ready to move', 'Luxury apartment in the greenest sector of Noida with modern amenities.', 'Hot listing', 'img1.jpg', 'img1.jpg,img2.jpg,img3.jpg', 'Green facing living room with balcony,Gated society with 24/7 security,Walking distance from Noida-Greater Noida Expressway'),
('3 BHK Luxury Flat in Noida Extension', '₹1,15,00,000', 'Greater Noida West', '3 BHK • Residential', '1450 sq.ft', 'Under construction', 'Premium residential project in Noida Extension with a world-class clubhouse.', 'Premium tower', 'img2.jpg', 'img2.jpg,img1.jpg,img3.jpg', 'High floor options with city view,Clubhouse with gym and swimming pool,Project on 130m wide road'),
('Premium 2 BHK near Sector 62', '₹95,00,000', 'Sector 62, Noida', '2 BHK • Residential', '1050 sq.ft', 'New launch', 'Located near major IT hubs and walking distance from the metro station.', 'Launching offer', 'img3.jpg', 'img3.jpg,img1.jpg,img2.jpg', 'Close to Fortis Hospital and major schools,Modern clubhouse and dedicated parking,Excellent connectivity to Delhi'),
('Spacious 3 BHK in Sector 75', '₹1,45,00,000', 'Sector 75, Noida', '3 BHK • Residential', '1650 sq.ft', 'Ready to move', 'Ready to move flat in a prime residential area with nearby markets.', 'Family choice', 'img4.jpg', 'img4.jpg,img1.jpg,img2.jpg', 'Large living and dining with multiple balconies,Near Spectrum Mall and metro station,Developed neighborhood with parks'),
('1 BHK Starter Home in Greater Noida', '₹42,00,000', 'Pari Chowk, Greater Noida', '1 BHK • Residential', '650 sq.ft', 'Ready to move', 'Perfect budget home for small families or working professionals.', 'Budget option', 'img1.jpg', 'img1.jpg,img4.jpg', 'Near Knowledge Park and Universities,Well-maintained society with parking,Easy access to Yamuna Expressway'),
('Residential Plot in Yamuna City', '₹65,00,000', 'Yamuna Expressway', 'Residential Plot', '1800 sq.ft', 'New launch', 'Freehold residential plots near the upcoming Noida International Airport.', 'Limited units', 'img2.jpg', 'img2.jpg,img3.jpg', 'High appreciation potential near Airport,Well-planned roads and infrastructure,Immediate registration and possession');
