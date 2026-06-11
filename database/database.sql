CREATE DATABASE IF NOT EXISTS morning_bakery_db;
USE morning_bakery_db;

DROP TABLE IF EXISTS order_items, orders, cart, products, categories, users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(100) NOT NULL,
  description TEXT
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  product_name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(12,2) NOT NULL,
  stock INT DEFAULT 0,
  weight VARCHAR(50),
  image VARCHAR(255),
  best_seller TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  qty INT NOT NULL DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  receiver_name VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  address TEXT NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  total_price DECIMAL(12,2) NOT NULL,
  status ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending',
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  qty INT NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO users(fullname,email,password,role) VALUES
('Administrator','admin@morningbakery.com','$2y$10$X/3gb4Vflp5A6KgubYQZ2.4lm3mJ1sxdZMoL1C7IuLsNEIkT1pS0K','admin'),
('Demo User','user@morningbakery.com','$2y$10$X/3gb4Vflp5A6KgubYQZ2.4lm3mJ1sxdZMoL1C7IuLsNEIkT1pS0K','user');

INSERT INTO categories(category_name,description) VALUES
('Roti Kasur','Soft filled bread variants'),('Roti Tawar','Daily toast bread'),('Roti Sobek','Shareable filled bread'),('Classic Bun','Classic bakery buns'),('Biskuit Kelapa','Crunchy coconut biscuit'),('Selai','Sweet spread'),('Cake','Premium whole cakes'),('Muffin','Soft muffins'),('Slice Cake','Individual cake slices'),('Swiss Roll','Soft rolled sponge cake');

INSERT INTO products(category_id,product_name,description,price,stock,weight,image,best_seller) VALUES
(1,'Roti Kasur Srikaya Pandan','Roti lembut dengan isian srikaya pandan harum.',14000,30,'270 g','placeholder.jpg',1),
(1,'Roti Kasur Kacang Merah','Roti lembut dengan isian kacang merah manis.',14000,30,'270 g','placeholder.jpg',0),
(1,'Roti Kasur Cokelat','Roti lembut dengan isian cokelat manis.',14000,30,'270 g','placeholder.jpg',1),
(1,'Roti Kasur Kacang Hijau','Roti lembut dengan isian kacang hijau.',14000,30,'270 g','placeholder.jpg',0),
(1,'Roti Kasur Keju','Roti kasur lembut dengan isian keju gurih.',14000,30,'200 g','placeholder.jpg',0),
(2,'Roti Tawar Kupas Putih','Roti tawar kupas putih lembut dan halus.',18000,25,'350 g','placeholder.jpg',1),
(2,'Roti Tawar Kulit','Roti tawar dengan kulit, tekstur klasik dan ringan.',16000,25,'350 g','placeholder.jpg',0),
(2,'Roti Tawar Kupas Pandan','Roti tawar aroma pandan yang lembut.',19000,25,'350 g','placeholder.jpg',0),
(2,'Roti Tawar Gandum','Roti gandum lembut, cocok untuk sarapan.',22000,25,'260 g','placeholder.jpg',0),
(3,'Roti Sobek Cokelat','Roti sobek lembut dengan isian cokelat.',19000,20,'210 g','placeholder.jpg',1),
(3,'Roti Sobek Keju','Roti sobek lembut dengan isian keju.',20000,20,'210 g','placeholder.jpg',0),
(4,'Classic Bun Coffee Boy','Roti kopi dengan topping khas dan isian butter.',17000,18,'210 g','placeholder.jpg',1),
(4,'Classic Bun Kosong Manis','Roti manis lembut dan ringan.',14000,18,'280 g','placeholder.jpg',0),
(5,'Biskuit Kelapa Bungkus','Biskuit kelapa crunchy dengan rasa manis gurih.',12000,40,'185 g','placeholder.jpg',0),
(5,'Biskuit Kelapa Toples','Biskuit kelapa crunchy kemasan toples.',18000,25,'210 g','placeholder.jpg',0),
(6,'Selai Srikaya Pandan','Selai srikaya pandan lembut dan harum.',28000,15,'220 g','placeholder.jpg',1),
(7,'Black Forest Cake','Chocolate chiffon dan chocolate cream.',231000,8,'20 cm','placeholder.jpg',1),
(7,'Vanilla Blueberry Cake','Vanilla chiffon dengan blueberry cream.',231000,8,'20 cm','placeholder.jpg',0),
(7,'Mango Cake','Vanilla chiffon dengan mango cream.',253000,8,'20 cm','placeholder.jpg',0),
(8,'Muffin Chocolate Chip','Muffin cokelat dengan choco chips.',14000,30,'1 pcs','placeholder.jpg',1),
(8,'Muffin Banana','Muffin pisang lembut dan moist.',13000,30,'1 pcs','placeholder.jpg',0),
(9,'Slice Cake Opera','Cake berlapis kopi dan cokelat.',30000,20,'1 slice','placeholder.jpg',1),
(9,'Slice Cake Red Velvet','Cake lembut dengan cream cheese.',22000,20,'1 slice','placeholder.jpg',0),
(10,'Swiss Roll Cheese','Sponge ringan dengan buttercream keju.',17000,25,'1 pcs','placeholder.jpg',1),
(10,'Swiss Roll Pandan','Sponge pandan dengan buttercream lembut.',17000,25,'1 pcs','placeholder.jpg',0);
