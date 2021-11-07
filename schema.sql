CREATE TABLE `notifications` (
 `id` int(11) NOT NULL,
 `user_id` int(11) NOT NULL,
 `message` text NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
 `id` int(11) NOT NULL,
 `email` varchar(255) NOT NULL,
 `phone` varchar(255) NOT NULL,
 `password` varchar(255) NOT NULL,
 `phone_verified_at` timestamp NULL DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `verification_attempts` (
 `id` int(11) NOT NULL,
 `user_id` int(11) NOT NULL,
 `code` varchar(6) NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `verification_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `notifications`
    ADD PRIMARY KEY (`id`),
    ADD KEY `notifications_user_id_fk` (`user_id`);

ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `email` (`email`),
    ADD UNIQUE KEY `phone` (`phone`);

ALTER TABLE `verification_attempts`
    ADD PRIMARY KEY (`id`),
    ADD KEY `verification_attempts_user_id_fk` (`user_id`);

ALTER TABLE `verification_codes`
    ADD PRIMARY KEY (`id`),
    ADD KEY `verification_codes_user_id_fk` (`user_id`),
    ADD KEY `code` (`code`);


ALTER TABLE `notifications`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `verification_attempts`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `verification_codes`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `notifications`
    ADD CONSTRAINT `notifications_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `verification_attempts`
    ADD CONSTRAINT `verification_attempts_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `verification_codes`
    ADD CONSTRAINT `verification_codes_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;
