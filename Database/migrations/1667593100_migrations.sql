
CREATE TABLE `migrations` (
  `id` int NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `migrations` (`id`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, '1667593100', 'cria tabela migrations', '2020-07-01 12:33:40', NULL),
(2, '1667592641', 'cria tabela empresa segmentos', '2020-07-01 12:33:40', NULL),
(3, '1667592767', 'cria tabela empresas', '2020-07-01 12:33:40', NULL);

ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

