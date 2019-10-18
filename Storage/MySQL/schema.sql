
CREATE TABLE `users_encounter` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `sender_id` INT NOT NULL,
    `receiver_id` INT NOT NULL,
    `datetime` DATETIME NOT NULL,
    `like` TINYINT NOT NULL COMMENT 'Reaction constant',
    `read` TINYINT NOT NULL COMMENT 'Whether this encounter is read',

    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);