CREATE DATABASE stepintomanila;

CREATE TABLE IF NOT EXISTS Admin (
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Experiences (
    experience_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    price DECIMAL(10, 2) NOT NULL,
    duration VARCHAR(100), -- e.g., '3 hours'
    category VARCHAR(100), -- e.g., 'history', 'food', 'arts'
    available_slots INT DEFAULT 0,
    image_url VARCHAR(500)
);

CREATE TABLE Experience_Schedule (
    schedule_id INT PRIMARY KEY AUTO_INCREMENT,
    experience_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    available_slots INT NOT NULL CHECK (available_slots >= 0),
    FOREIGN KEY (experience_id) REFERENCES Experiences(experience_id)
);

CREATE TABLE Bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    experience_id INT NOT NULL,
    booking_date DATE NOT NULL,
    number_of_guests INT NOT NULL CHECK (number_of_guests > 0),
    status VARCHAR(20) NOT NULL CHECK (status IN ('confirmed', 'cancelled', 'pending')),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (experience_id) REFERENCES Experiences(experience_id)
);

CREATE TABLE Payment (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATETIME NOT NULL,
    payment_method VARCHAR(50),
    status VARCHAR(20) NOT NULL CHECK (status IN ('completed', 'pending', 'failed')),
    FOREIGN KEY (booking_id) REFERENCES Bookings(booking_id)
);

CREATE TABLE Reviews (
    username VARCHAR(50) NOT NULL,
    rating INT NOT NULL,
    description VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL
);

INSERT INTO Admin (username, password_hash)
VALUES ('admin', '$2y$10$6hMYSzevKv94GwgetbdzBe5qluhB2a..lAQ3XiRN9006mT1ZvECzC');