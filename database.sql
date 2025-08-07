-- Drop existing tables
DROP TABLE IF EXISTS video_categories;
DROP TABLE IF EXISTS videos;
DROP TABLE IF EXISTS categories;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `user_id`, `user_name`, `password`, `date`) VALUES
(1, 36839081261254186, 'Galongatinho', 'cecilia', '2025-07-28 00:06:26');

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`),
  ADD KEY `user_name` (`user_name`);

ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- 1. categories
CREATE TABLE categories (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)   NOT NULL UNIQUE
);

-- 2. videos
CREATE TABLE videos (
  id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  youtube_video_id  VARCHAR(24)      NOT NULL UNIQUE,
  title             VARCHAR(255)     NOT NULL,
  description       TEXT             NULL,
  published_at      DATETIME         NOT NULL,
  retrieved_at      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_published (published_at),
  FULLTEXT KEY ft_title_desc (title, description)
);

-- 3. video_categories (join table)
CREATE TABLE video_categories (
  video_id    BIGINT UNSIGNED NOT NULL,
  category_id INT UNSIGNED    NOT NULL,
  PRIMARY KEY (video_id, category_id),
  CONSTRAINT fk_vc_video
    FOREIGN KEY (video_id)
    REFERENCES videos(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_vc_category
    FOREIGN KEY (category_id)
    REFERENCES categories(id)
    ON DELETE CASCADE
);

-- seed some categories
INSERT INTO categories (name) VALUES
  ('Ação'),
  ('Aventura'),
  ('Comédia'),
  ('Drama'),
  ('Família'),
  ('Ficção'),
  ('Suspense'),
  ('Terror');

-- seed a couple videos
-- Inserir vídeos
INSERT INTO videos (youtube_video_id, title, description, published_at) VALUES
  ('AbCdEfGhIjk', 'A Face do Crime 1954', NULL, '2020-01-01 12:00:00'),
  ('Dq3zkJAsUkw', 'O Mundo Perdido 1925', NULL, '2020-01-01 12:00:00'),
  ('TzgDiwHxB_w', 'Armando o Laço 1934', NULL, '2020-01-01 12:00:00'),
  ('tvVJC33Bl4o', 'Os Colonizadores 1931', NULL, '2020-01-01 12:00:00'),
  ('I1O1xqGl7HI', 'Branca de Neve e os Sete Anões 1937', NULL, '2020-01-01 12:00:00');


-- link videos to categories
-- A Face do Crime 1954
INSERT INTO video_categories (video_id, category_id)
SELECT v.id, c.id FROM videos v, categories c
WHERE v.youtube_video_id = 'AbCdEfGhIjk' AND c.name IN ('Ação', 'Drama', 'Suspense');

-- O Mundo Perdido 1925
INSERT INTO video_categories (video_id, category_id)
SELECT v.id, c.id FROM videos v, categories c
WHERE v.youtube_video_id = 'Dq3zkJAsUkw' AND c.name IN ('Aventura', 'Ação', 'Terror');

-- Armando o Laço 1934
INSERT INTO video_categories (video_id, category_id)
SELECT v.id, c.id FROM videos v, categories c
WHERE v.youtube_video_id = 'TzgDiwHxB_w' AND c.name IN ('Ação', 'Comédia', 'Família');

-- Os Colonizadores 1931
INSERT INTO video_categories (video_id, category_id)
SELECT v.id, c.id FROM videos v, categories c
WHERE v.youtube_video_id = 'tvVJC33Bl4o' AND c.name IN ('Comédia');

-- Branca de Neve e os Sete Anões
INSERT INTO video_categories (video_id, category_id)
SELECT v.id, c.id FROM videos v, categories c
WHERE v.youtube_video_id = 'I1O1xqGl7HI' AND c.name IN ('Ficção', 'Família');
