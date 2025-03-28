CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE lost_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    lost_location VARCHAR(255) NOT NULL,
    lost_date DATE NOT NULL,
    status ENUM('Lost', 'Found') DEFAULT 'Lost',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE found_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    lost_item_id INT(11) NOT NULL,
    found_by INT(11) NOT NULL,
    meet_location VARCHAR(255) NOT NULL,
    meet_time DATETIME NOT NULL,
    contact_info VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lost_item_id) REFERENCES lost_items(id) ON DELETE CASCADE,
    FOREIGN KEY (found_by) REFERENCES users(id) ON DELETE CASCADE
);

