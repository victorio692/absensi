
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100),
  `role` ENUM('admin', 'siswa') NOT NULL DEFAULT 'siswa',
  `siswa_id` int(11),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert test admin user
INSERT IGNORE INTO `users` (id, username, password, role, is_active) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Insert test siswa user
INSERT IGNORE INTO `users` (id, username, password, email, role, siswa_id, is_active) VALUES
(2, 'ahmad.rafli', '$2y$10$EixZaYVK1fsbw1ZfbX3OzeIYRDHJQFXWVbdvXKVW8C.v2Ng6T0hjC', 'ahmad.rafli@school.com', 'siswa', 1, 1);

