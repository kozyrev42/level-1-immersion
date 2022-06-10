CREATE TABLE `users-dive` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255) DEFAULT 'user',
    `avatar-url` VARCHAR(255),
    `status` VARCHAR(255),
    `name-filter` VARCHAR(255),
    `name` VARCHAR(255),
    `position` VARCHAR(255),
    `tel-href` VARCHAR(255),
    `tel` VARCHAR(255),
    `mail` VARCHAR(255),
    `address` VARCHAR(255),
    PRIMARY KEY (id)
) default charset utf8mb4;
