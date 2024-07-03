-- =====================================================================
-- EstateEase - seed.sql
-- Sample data for quick local demos.
--
-- Passwords below are bcrypt hashes of the plain text "password".
-- (Never ship real default passwords in production.)
-- =====================================================================

USE realstate;

INSERT INTO users (id, email, password_hash, first_name, last_name) VALUES
    (1, 'qirat@estateease.test',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'Qirat',   'Fatima'),
    (2, 'aun@estateease.test',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'Aun',     'Abbas'),
    (3, 'mehran@estateease.test',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'Mehran',  'Haider');

INSERT INTO purchase
    (id, user_id, name, total_amount, area_sqm, purchase_date, photo)
VALUES
    (1, 1, 'DHA Phase 6 - Plot 42',       18500000.00, 500, '2024-01-15', 'dha.jpg'),
    (2, 1, 'Bahria Town - House B-17',    14200000.00, 320, '2024-02-20', 'bahria.jpg'),
    (3, 1, 'Gulberg Apartment #302',       9500000.00, 180, '2024-03-05', 'apartment1.jpg'),
    (4, 2, 'Model Town - 1 Kanal House',  26750000.00, 450, '2024-03-12', 'villa.png'),
    (5, 2, 'Johar Town Studio',            5400000.00, 110, '2024-04-01', 'apartment2.jpg'),
    (6, 3, 'Cantt Bungalow',              32000000.00, 600, '2024-04-20', 'modern.jpg'),
    (7, 3, 'Askari Heights Flat',          8100000.00, 150, '2024-05-10', 'apartment3.jpg');

INSERT INTO soldproperty
    (id, purchase_id, sold_date, total_amount, received_amount, next_date)
VALUES
    (1, 3, '2024-05-25', 11000000.00, 11000000.00, NULL),
    (2, 5, '2024-06-02',  6100000.00,  3000000.00, '2024-07-15'),
    (3, 7, '2024-06-18',  9000000.00,  4500000.00, '2024-08-01');

INSERT INTO contact_message (name, email, message) VALUES
    ('Sana Riaz',  'sana@example.com',  'Loved the hero section - how do I list a property?'),
    ('Omer Saeed', 'omer@example.com',  'Do you support commercial plots as well?');
