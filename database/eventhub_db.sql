DROP DATABASE IF EXISTS eventhub_db;
CREATE DATABASE eventhub_db;
USE eventhub_db;

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO categories (category_name) VALUES
('Workshop'),
('Seminar'),
('Competition'),
('Sports'),
('Cultural'),
('Career');


CREATE TABLE clubs (
    club_id INT AUTO_INCREMENT PRIMARY KEY,
    club_name VARCHAR(150) NOT NULL,
    description TEXT
);

INSERT INTO clubs (club_name, description) VALUES
('IEEE Student Branch','IEEE Student Branch NSBM'),
('FOSS Community','Free and Open Source Software Community'),
('Rotaract Club','Rotaract Club NSBM'),
('Leo Club','Leo Club NSBM'),
('Media Society','Media Society NSBM'),
('AI Society','Artificial Intelligence Society');


CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) DEFAULT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Student','Club Admin','Super Admin') NOT NULL DEFAULT 'Student',
    club_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_users_club
        FOREIGN KEY (club_id)
        REFERENCES clubs(club_id)
        ON DELETE SET NULL
);


INSERT INTO users
(student_id, first_name, last_name, email, password, role, club_id)
VALUES
(NULL,'System','Administrator','kavi@nsbm.ac.lk','$2y$10$mKxp8h7pS192hubCt5mQp.Ed/AeIAonmoWETZfcfJg5k2VubrY1xC','Super Admin',NULL);


CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(200) NOT NULL,
    description TEXT,

    category_id INT NOT NULL,
    club_id INT NOT NULL,

    venue VARCHAR(150) NOT NULL,

    event_date DATE NOT NULL,
    event_time TIME NOT NULL,

    capacity INT NOT NULL,

    banner_image VARCHAR(255),

    created_by INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_events_category
        FOREIGN KEY(category_id)
        REFERENCES categories(category_id),

    CONSTRAINT fk_events_club
        FOREIGN KEY(club_id)
        REFERENCES clubs(club_id),

    CONSTRAINT fk_events_user
        FOREIGN KEY(created_by)
        REFERENCES users(user_id)
);

INSERT INTO events
(title,description,category_id,club_id,venue,event_date,event_time,capacity,banner_image,created_by)
VALUES
(
'AI Workshop',
'Introduction to Artificial Intelligence',
1,
6,
'FOC Auditorium',
'2026-09-15',
'09:00:00',
120,
'uploads/events/ai-workshop.jpg',
1
),
(
'Hackathon 2026',
'24 Hour Coding Competition',
3,
1,
'Innovation Building',
'2026-10-02',
'08:00:00',
80,
'uploads/events/hackathon.jpg',
1
),
(
'Career Fair',
'Meet Industry Professionals',
6,
2,
'Main Hall',
'2026-09-28',
'10:00:00',
300,
'uploads/events/career-fair.jpg',
1
),
(
'Cultural Night',
'Annual Cultural Celebration',
5,
4,
'Open Air Theatre',
'2026-11-20',
'18:00:00',
500,
'uploads/events/cultural-night.jpg',
1
);

CREATE TABLE registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    event_id INT NOT NULL,

    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(user_id,event_id),

    CONSTRAINT fk_registration_user
        FOREIGN KEY(user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_registration_event
        FOREIGN KEY(event_id)
        REFERENCES events(event_id)
        ON DELETE CASCADE
);



CREATE TABLE announcements (
    announcement_id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,

    created_by INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_announcement_user
        FOREIGN KEY(created_by)
        REFERENCES users(user_id)
);

INSERT INTO announcements
(title,message,created_by)
VALUES
(
'Welcome to EventHub',
'The EventHub system is now online.',
1
);

CREATE TABLE event_requests (

    request_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    title VARCHAR(200) NOT NULL,

    description TEXT NOT NULL,

    category_id INT NOT NULL,

    event_date DATE NOT NULL,

    event_time TIME NOT NULL,

    venue VARCHAR(150) NOT NULL,

    capacity INT NOT NULL,

    status ENUM('Pending','Approved','Rejected')
        NOT NULL DEFAULT 'Pending',

    admin_comment TEXT DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_request_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_request_category
        FOREIGN KEY (category_id)
        REFERENCES categories(category_id)

);