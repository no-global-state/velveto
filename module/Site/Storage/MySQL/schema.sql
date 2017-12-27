
CREATE TABLE velveto_payment_systems (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order` INT NOT NULL COMMENT 'Soring order',
    `name` varchar(255) NOT NULL
);

CREATE TABLE velveto_discounts (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT NOT NULL COMMENT 'Attached hotel ID',
    `name` varchar(255) NOT NULL COMMENT 'Discount name',
    `percentage` FLOAT NOT NULL,

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_price_groups (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL COMMENT 'Price Group Name',
    `order` INT NOT NULL COMMENT 'Price Group Sorting Order',
    `currency` varchar(255) NOT NULL COMMENT 'Attached currency',
    `daily_tax` FLOAT NOT NULL COMMENT 'Daily tax for the current group'
);

CREATE TABLE velveto_regions (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order` INT NOT NULL COMMENT 'Region sorting order'
);

CREATE TABLE velveto_regions_translation (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `name` varchar(255),

    FOREIGN KEY (lang_id) REFERENCES velveto_languages(id) ON DELETE CASCADE,
    FOREIGN KEY (id) REFERENCES velveto_regions(id) ON DELETE CASCADE
);

CREATE TABLE velveto_reviews_types (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order` INT NOT NULL COMMENT 'Sorting order',
    `name` varchar(255)
);

CREATE TABLE velveto_reviews (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `lang_id` INT NOT NULL,
    `hotel_id` INT NOT NULL,
    `date` TIMESTAMP NOT NULL,
    `title` varchar(255) NOT NULL COMMENT 'Review title',
    `review` TEXT COMMENT 'Review itself',
    `rating` SMALLINT NOT NULL COMMENT 'Summary rating',

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_id) REFERENCES velveto_languages(id) ON DELETE CASCADE
);

CREATE TABLE velveto_languages (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL COMMENT 'Language name',
    `code` varchar(5) NOT NULL COMMENT 'Language code',
    `order` INT DEFAULT 0 COMMENT 'Sorting order'
);

CREATE TABLE velveto_facilitiy_relations (
    `master_id` INT NOT NULL COMMENT 'Hotel ID',
    `slave_id` INT NOT NULL COMMENT 'Item ID'

    FOREIGN KEY (master_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (slave_id) REFERENCES velveto_facilitiy_items(id) ON DELETE CASCADE
);

CREATE TABLE velveto_facilitiy_categories (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT
);

CREATE TABLE velveto_facilitiy_categories_translation (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `name` varchar(255),

    FOREIGN KEY (lang_id) REFERENCES velveto_languages(id) ON DELETE CASCADE,
    FOREIGN KEY (id) REFERENCES velveto_facilitiy_categories(id) ON DELETE CASCADE
);

CREATE TABLE velveto_facilitiy_items (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `icon` varchar(255) NOT NULL,
    `front` SMALLINT(1) NOT NULL COMMENT 'Whether item must be front or not',
    `category_id` INT NOT NULL,

    FOREIGN KEY (category_id) REFERENCES velveto_facilitiy_categories(id) ON DELETE CASCADE
);

CREATE TABLE velveto_facilitiy_items_translation (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `name` varchar(255),

    FOREIGN KEY (lang_id) REFERENCES velveto_languages(id) ON DELETE CASCADE,
    FOREIGN KEY (id) REFERENCES velveto_facilitiy_items(id) ON DELETE CASCADE
);

CREATE TABLE velveto_hotels_photos (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT NOT NULL,
    `file` varchar(30) NOT NULL COMMENT 'Base name of photo file',
    `order` INT NOT NULL COMMENT 'Sorting order',

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_hotels_photos_covers (
    `master_id` INT NOT NULL COMMENT 'Hotel ID',
    `slave_id` INT NOT NULL COMMENT 'Photo ID',

    FOREIGN KEY (master_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (slave_id) REFERENCES velveto_hotels_photos(id) ON DELETE CASCADE
);

CREATE TABLE velveto_hotels_transactions (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT DEFAULT 1 COMMENT 'Attached hotel ID',
    `datetime` TIMESTAMP,
    `holder` varchar(255) NOT NULL COMMENT 'Card holder name',
    `payment_system` varchar(30) NOT NULL,
    `amount` FLOAT NOT NULL,
    `currency` varchar(20) NOT NULL,
    `comment` TEXT NOT NULL,

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_users (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT NOT NULL COMMENT 'Attached hotel ID',
    `name` varchar(255) NULL NOT NULL,
    `email` varchar(255) NOT NULL,
    `login` varchar(255) NOT NULL,
    `password` varchar(100) NOT NULL,
    `role` varchar(1) NOT NULL,

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_floor (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT NOT NULL COMMENT 'Attached hotel ID',
    `name` varchar(255) NULL,

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_floor_room (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `floor_id` INT NOT NULL,
    `type_id` INT NOT NULL COMMENT 'Room type ID',
    `hotel_id` INT NOT NULL COMMENT 'Attached hotel ID',
    `persons` INT COMMENT 'Maximal amount of persons for the room',
    `name` varchar(255) NOT NULL,
    `square` float COMMENT 'Square of the room',
    `cleaned` varchar(1) NOT NULL COMMENT 'Cleaning code status of the room',
    `quality` SMALLINT(1) NOT NULL COMMENT 'Room quality code',
    `description` TEXT NOT NULL COMMENT 'Room description',

    FOREIGN KEY (floor_id) REFERENCES velveto_floor(id) ON DELETE CASCADE,
    FOREIGN KEY (type_id) REFERENCES velveto_floor_room_types(id) ON DELETE CASCADE,
    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_floor_room_types (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT NOT NULL COMMENT 'Attached hotel ID',
    `type` varchar(255) NOT NULL,

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_floor_room_type_prices (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `room_type_id` INT NOT NULL COMMENT 'Room type ID',
    `price_group_id` INT NOT NULL COMMENT 'Attached price group ID',
    `price` FLOAT NOT NULL,

    FOREIGN KEY (room_type_id) REFERENCES velveto_floor_room_types(id) ON DELETE CASCADE,
    FOREIGN KEY (price_group_id) REFERENCES velveto_price_groups(id) ON DELETE CASCADE
);

CREATE TABLE velveto_floor_room_gallery (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `room_id` INT NOT NULL COMMENT 'Attahced Room ID',
    `file` varchar(255) COMMENT 'Photo file path',
    `order` INT COMMENT 'Sorting order',

    FOREIGN KEY (room_id) REFERENCES velveto_floor_room(id) ON DELETE CASCADE
);

CREATE TABLE velveto_inventory (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT NOT NULL COMMENT 'Attached hotel ID',
    `name` varchar(255) NOT NULL,

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_room_inventory (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `room_id` INT NOT NULL COMMENT 'Attached room ID',
    `inventory_id` INT NOT NULL COMMENT 'Attached inventory ID',
    `code` varchar(255) NOT NULL COMMENT 'Serial number or code',
    `qty` INT NOT NULL COMMENT 'Quantity',
    `comment` TEXT,

    FOREIGN KEY (inventory_id) REFERENCES velveto_inventory(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES velveto_floor_room(id) ON DELETE CASCADE
);

CREATE TABLE velveto_room_services (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT DEFAULT 1 COMMENT 'Attached hotel ID',
    `name` varchar(255) NOT NULL,
    `price` FLOAT NOT NULL,
    `unit` varchar(3) NOT NULL,
    `comment` TEXT,

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_hotels (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `phone` varchar(255) NOT NULL,
    `currency` varchar(30) NOT NULL COMMENT 'Default hotel currency',
    `start_price` FLOAT NOT NULL,
    `rate` SMALLINT COMMENT 'Hotel rate',
    `discount` FLOAT COMMENT 'Discount if available',
    `daily_tax` FLOAT COMMENT 'Daily tax for living',
    `website` varchar(255) NOT NULL COMMENT 'Web-site URL',
    `email` varchar(255) NOT NULL COMMENT 'Hotel email',
    `active` BOOLEAN NOT NULL COMMENT 'Whether this hotel must be visible or not'
);

CREATE TABLE velveto_hotels_translation (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL,
    `city` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `address` varchar(255) NOT NULL,
    `description` TEXT NOT NULL,

    FOREIGN KEY (lang_id) REFERENCES velveto_languages(id) ON DELETE CASCADE,
    FOREIGN KEY (id) REFERENCES velveto_hotels(id) ON DELETE CASCADE
);

CREATE TABLE velveto_reservation (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `hotel_id` INT NOT NULL COMMENT 'Attached hotel ID',
	`room_id` INT NOT NULL,
    `price_group_id` INT COMMENT 'Attached price group ID',
    `payment_system_id` INT NOT NULL COMMENT 'Payment system attached ID',
	`full_name` varchar(255) NOT NULL COMMENT 'First, last, middle names',
	`gender` varchar(1) NOT NULL,
	`country` varchar(2) NOT NULL,
	`status` varchar(1) NOT NULL COMMENT 'Person status code (like regular or VIP)',
    `state` SMALLINT(1) NOT NULL COMMENT 'Reservation state code',
    `purpose` SMALLINT(1) NOT NULL COMMENT 'Reservation purpose code',
    `source` SMALLINT(1) NOT NULL COMMENT 'Source code',
    `legal_status` SMALLINT(1) NOT NULL COMMENT 'Legal status code',
    `phone` varchar(250) NOT NULL,
    `email` varchar(100) NOT NULL,
    `passport` TEXT NOT NULL COMMENT 'Passport data',
    `discount` FLOAT NOT NULL COMMENT 'Discount percentage',
    `comment` TEXT NOT NULL COMMENT 'Additional note if any',
    `company` varchar(255) NOT NULL,
    `tax` float NOT NULL COMMENT 'Fixed tax',
    `price` float NOT NULL COMMENT 'Total reservation price',

    /* Date */
	`arrival` DATE NOT NULL,
	`departure` DATE NOT NULL,

    FOREIGN KEY (hotel_id) REFERENCES velveto_hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_system_id) REFERENCES velveto_payment_systems(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES velveto_floor_room(id) ON DELETE CASCADE,
    FOREIGN KEY (price_group_id) REFERENCES velveto_price_groups(id) ON DELETE CASCADE
);

CREATE TABLE velveto_reservation_services (
    `master_id` INT NOT NULL COMMENT 'Reservation ID',
    `slave_id` INT NOT NULL COMMENT 'Service ID',

    FOREIGN KEY (master_id) REFERENCES velveto_reservation(id) ON DELETE CASCADE,
    FOREIGN KEY (slave_id) REFERENCES velveto_room_services(id) ON DELETE CASCADE
);
