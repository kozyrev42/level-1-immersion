CREATE TABLE `users-dive` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255) DEFAULT 'user',
    `avatar` VARCHAR(255),
    `status` VARCHAR(255),
    `name` VARCHAR(255),
    `position` VARCHAR(255),
    `tel` VARCHAR(255),
    `address` VARCHAR(255),
    PRIMARY KEY (id)
) default charset utf8mb4;


ALTER TABLE `users-dive`
    ADD COLUMN `vk` VARCHAR(255) AFTER `address`;
ALTER TABLE `users-dive`
    ADD COLUMN `teleg` VARCHAR(255) AFTER `vk`;
ALTER TABLE `users-dive`
    ADD COLUMN `insta` VARCHAR(255) AFTER `teleg`;
    