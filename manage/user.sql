-- Tạo bảng người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    ho_ten VARCHAR(100),
    role ENUM('user', 'admin') DEFAULT 'user'
);
INSERT INTO users (username, password, email, ho_ten, role)
VALUES ('hoainhan', '1231', 'd.hoainhan256@gmail.com', 'Hoai Nhan', 'admin');