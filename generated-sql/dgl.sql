
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `name` VARCHAR(32) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(128),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- role
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role`
(
    `name` VARCHAR(32) NOT NULL,
    `annotation` VARCHAR(250),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user_role
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role`
(
    `user_id` INTEGER NOT NULL,
    `role_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`role_id`),
    INDEX `user_role_fi_1ff99e` (`role_id`),
    CONSTRAINT `user_role_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `user_role_fk_1ff99e`
        FOREIGN KEY (`role_id`)
        REFERENCES `role` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- permission
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `permission`;

CREATE TABLE `permission`
(
    `name` VARCHAR(64) NOT NULL,
    `annotation` VARCHAR(250) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- role_permission
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `role_permission`;

CREATE TABLE `role_permission`
(
    `role_id` INTEGER NOT NULL,
    `permission_id` INTEGER NOT NULL,
    PRIMARY KEY (`role_id`,`permission_id`),
    INDEX `role_permission_fi_2b894c` (`permission_id`),
    CONSTRAINT `role_permission_fk_1ff99e`
        FOREIGN KEY (`role_id`)
        REFERENCES `role` (`id`),
    CONSTRAINT `role_permission_fk_2b894c`
        FOREIGN KEY (`permission_id`)
        REFERENCES `permission` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- setting
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting`
(
    `name` VARCHAR(128),
    `value` VARCHAR(1000),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_archive`;

CREATE TABLE `user_archive`
(
    `name` VARCHAR(32) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(128),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `id` INTEGER NOT NULL,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
