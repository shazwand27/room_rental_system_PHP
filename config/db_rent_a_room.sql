CREATE TABLE `states`(
    `state_id` INT(11) NOT NULL AUTO_INCREMENT,
    `state_name` VARCHAR(50) NOT NULL,
    `state_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `state_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(`state_id`)
);

INSERT INTO
    `states`(`state_name`)
VALUES
    ('Johor'),
    ('Kedah'),
    ('Kelantan'),
    ('Melaka'),
    ('Negeri Sembilan'),
    ('Pahang'),
    ('Perak'),
    ('Perlis'),
    ('Pulau Pinang'),
    ('Sabah'),
    ('Sarawak'),
    ('Selangor'),
    ('Terengganu'),
    ('Wilayah Persekutuan Kuala Lumpur'),
    ('Wilayah Persekutuan Labuan'),
    ('Wilayah Persekutuan Putrajaya');

CREATE TABLE `users`(
    `user_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_name` VARCHAR(255) NULL,
    `user_ic` VARCHAR(12) NULL,
    `user_username` VARCHAR(50) NULL,
    `user_email` VARCHAR(255) NOT NULL,
    `user_phone` VARCHAR(20) NULL,
    `user_emergency_contact` VARCHAR(20) NULL,
    `user_address` VARCHAR(255) NULL,
    `user_postcode` VARCHAR(10) NULL,
    `user_city` VARCHAR(50) NULL,
    `user_state_id` INT(11) NULL,
    `user_occupation` VARCHAR(255) NULL,
    `user_work_address` VARCHAR(255) NULL,
    `user_work_contact` VARCHAR(20) NULL,
    `user_password` VARCHAR(255) NULL,
    `user_role` enum('admin', 'staff', 'tenant') NOT NULL DEFAULT 'tenant',
    `user_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `user_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(`user_id`),
    UNIQUE KEY `user_username`(`user_username`),
    UNIQUE KEY `user_email`(`user_email`)
);

CREATE TABLE `houses`(
    `house_id` INT(11) NOT NULL AUTO_INCREMENT,
    `house_name` VARCHAR(255) NOT NULL,
    `house_address` VARCHAR(255) NOT NULL,
    `house_postcode` VARCHAR(10) NOT NULL,
    `house_city` VARCHAR(50) NOT NULL,
    `house_state_id` INT(11) NOT NULL,
    `house_type` enum(
        'apartment',
        'condominium',
        'terrace',
        'bungalow',
        'semi-detached',
        'detached'
    ) NOT NULL,
    `house_description` TEXT NULL,
    `house_image` VARCHAR(255) NULL,
    `house_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `house_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `house_deleted_at` DATETIME NULL,
    PRIMARY KEY(`house_id`),
    FOREIGN KEY(`house_state_id`) REFERENCES `states`(`state_id`)
);

CREATE TABLE `rooms`(
    `room_id` INT(11) NOT NULL AUTO_INCREMENT,
    `room_name` VARCHAR(255) NOT NULL,
    `room_house_id` INT(11) NOT NULL,
    `room_type` enum(
        'single',
        'double',
        'triple',
        'quad',
        'queen',
        'king',
        'twin',
        'double-double',
        'studio',
        'master'
    ) NOT NULL,
    `room_price` DECIMAL(10, 2) NOT NULL,
    `room_furnishing` enum('fully', 'partially', 'unfurnished') NOT NULL DEFAULT 'fully',
    -- `room_furnishing` enum('fully furnished', 'partially furnished', 'unfurnished') NOT NULL DEFAULT 'fully furnished',
    `room_bathroom` enum('private', 'shared') NOT NULL DEFAULT 'private',
    `room_deposit` DECIMAL(10, 2) NOT NULL,
    `room_monthly_rental` DECIMAL(10, 2) NOT NULL,
    `room_description` TEXT NULL,
    `room_image` VARCHAR(255) NULL,
    `room_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `room_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `room_deleted_at` DATETIME NULL,
    PRIMARY KEY(`room_id`),
    FOREIGN KEY(`room_house_id`) REFERENCES `houses`(`house_id`)
);

-- add more enum values room_furnishing
ALTER TABLE
    `rooms` CHANGE COLUMN `room_furnishing` `room_furnishing` enum(
        'fully furnished',
        'partially furnished',
        'unfurnished'
    ) NOT NULL DEFAULT 'fully furnished';

CREATE TABLE `tenants`(
    `tenant_id` INT(11) NOT NULL AUTO_INCREMENT,
    `tenant_user_id` INT(11) NOT NULL,
    `tenant_room_id` INT(11) NOT NULL,
    `tenant_status` enum('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `tenant_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `tenant_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `tenant_deleted_at` DATETIME NULL,
    PRIMARY KEY(`tenant_id`),
    FOREIGN KEY(`tenant_user_id`) REFERENCES `users`(`user_id`),
    FOREIGN KEY(`tenant_room_id`) REFERENCES `rooms`(`room_id`)
);

CREATE TABLE `rents`(
    `rent_id` INT(11) NOT NULL AUTO_INCREMENT,
    `rent_tenant_id` INT(11) NOT NULL,
    `rent_start_date` DATE NULL,
    `rent_end_date` DATE NULL,
    `rent_deposit` DECIMAL(10, 2) NULL,
    `rent_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `rent_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `rent_deleted_at` DATETIME NULL,
    PRIMARY KEY(`rent_id`),
    FOREIGN KEY(`rent_tenant_id`) REFERENCES `tenants`(`tenant_id`)
);

CREATE TABLE `payments`(
    `payment_id` INT(11) NOT NULL AUTO_INCREMENT,
    `payment_rent_id` INT(11) NOT NULL,
    `payment_amount` DECIMAL(10, 2) NOT NULL,
    `payment_type` enum('rent', 'deposit', 'others') NOT NULL DEFAULT 'rent',
    `payment_method` enum('cash', 'online') NOT NULL DEFAULT 'cash',
    `payment_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `payment_updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `payment_deleted_at` DATETIME NULL,
    PRIMARY KEY(`payment_id`),
    FOREIGN KEY(`payment_rent_id`) REFERENCES `rents`(`rent_id`)
);

INSERT INTO
    `users`(
        `user_name`,
        `user_username`,
        `user_email`,
        `user_password`,
        `user_role`
    )
VALUES
    (
        'Admin',
        'admin',
        'admin@example.com',
        '$2y$10$gXVH/18EjkMFnf1QBwhnr.eXZUvuQnUn63hd7xxzzMipcJGIiKDz6',
        -- admin
        'admin'
    );