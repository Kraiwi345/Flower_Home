DATABASE users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
     fname VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('customer', 'owner') NOT NULL,
    last_login DATETIME NOT NULL
);

-- เพิ่มข้อมูลตัวอย่าง
INSERT INTO users (username, password, user_type) VALUES
('customer1', PASSWORD('customerpassword1'), 'customer'),
('owner1', PASSWORD('ownerpassword1'), 'owner');

*************************************************************
DATABASE  flowerhome;


CREATE TABLE IF NOT EXISTS tbproduct (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    prev_price FLOAT(12,2) NOT NULL DEFAULT 0.00,
    current_price FLOAT(12,2) NOT NULL DEFAULT 0.00,
    img_path VARCHAR(255) NOT NULL,
    type_shop VARCHAR(255) NOT NULL,
    date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
************************************************************
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*************************************************************
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES tbproduct(id) ON DELETE CASCADE
);