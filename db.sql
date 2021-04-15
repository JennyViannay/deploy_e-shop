-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 15 avr. 2021 à 20:40
-- Version du serveur :  5.7.30
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données : `e_shop_0920`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `model` varchar(80) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `model`, `qty`, `price`, `brand_id`, `size_id`, `color_id`) VALUES
(3, 'Air Max', 4, 100, 11, 17, 2),
(4, 'Air Max', 0, 100, 13, 16, 3),
(5, 'Air Max', 6, 100, 12, 20, 10);

-- --------------------------------------------------------

--
-- Structure de la table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `is_used` tinyint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `brand`
--

INSERT INTO `brand` (`id`, `name`, `is_used`) VALUES
(10, 'adidas', 0),
(11, 'nike', 1),
(12, 'fila', 1),
(13, 'puma', 1);

-- --------------------------------------------------------

--
-- Structure de la table `color`
--

CREATE TABLE `color` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `is_used` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `color`
--

INSERT INTO `color` (`id`, `name`, `is_used`) VALUES
(2, 'black', 1),
(3, 'white', 1),
(4, 'red', 0),
(5, 'blue', 0),
(6, 'green', 0),
(7, 'yellow', 0),
(8, 'orange', 0),
(9, 'grey', 0),
(10, 'marine', 1),
(11, 'neutral', 0);

-- --------------------------------------------------------

--
-- Structure de la table `command`
--

CREATE TABLE `command` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `command`
--

INSERT INTO `command` (`id`, `user_id`, `address`, `total`, `created_at`) VALUES
(1, 1, '123 street planete rap', 200, '2021-04-15 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `command_article`
--

CREATE TABLE `command_article` (
  `id_command` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `command_article`
--

INSERT INTO `command_article` (`id_command`, `id_article`, `qty`) VALUES
(1, 4, 1),
(1, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `url`, `article_id`) VALUES
(6, 'https://media.gq.com/photos/5fa2ce5dc0e38edb6bd3ac91/4:3/w_1500,h_1125,c_limit/airmax.jpg', 3),
(7, 'https://media.gq.com/photos/5fa2ce5dc0e38edb6bd3ac91/4:3/w_1500,h_1125,c_limit/airmax.jpg', 3),
(8, 'https://medias-cache.placedestendances.com/image/65/8/2771658.jpg', 4),
(9, '', 4),
(10, 'https://www.walkjogrun.net/wp-content/uploads/2019/05/Best-Fila-Shoes-Ray-Tracer.jpeg', 5),
(11, '', 5);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Structure de la table `size`
--

CREATE TABLE `size` (
  `id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `is_used` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `size`
--

INSERT INTO `size` (`id`, `size`, `is_used`) VALUES
(12, 44, 0),
(14, 47, 0),
(15, 46, 0),
(16, 37, 1),
(17, 39, 1),
(18, 38, 0),
(19, 40, 0),
(20, 41, 1),
(21, 42, 0),
(22, 43, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(80) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `password`, `role_id`) VALUES
(1, 'plop@plop.com', 'plop', '5f4dcc3b5aa765d61d8327deb882cf99', 1);

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`id`, `article_id`, `user_id`) VALUES
(1, 4, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_article_brand` (`brand_id`),
  ADD KEY `FK_article_color` (`color_id`),
  ADD KEY `FK_article_size` (`size_id`);

--
-- Index pour la table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `command`
--
ALTER TABLE `command`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_command_user` (`user_id`);

--
-- Index pour la table `command_article`
--
ALTER TABLE `command_article`
  ADD KEY `FK_command_article` (`id_article`),
  ADD KEY `FK_command_command` (`id_command`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_image_article` (`article_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_user_role` (`role_id`);

--
-- Index pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_wishlist_article` (`article_id`),
  ADD KEY `FK_wishlist_user` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `color`
--
ALTER TABLE `color`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `command`
--
ALTER TABLE `command`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `size`
--
ALTER TABLE `size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_article_brand` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`),
  ADD CONSTRAINT `FK_article_color` FOREIGN KEY (`color_id`) REFERENCES `color` (`id`),
  ADD CONSTRAINT `FK_article_size` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`);

--
-- Contraintes pour la table `command`
--
ALTER TABLE `command`
  ADD CONSTRAINT `FK_command_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `command_article`
--
ALTER TABLE `command_article`
  ADD CONSTRAINT `FK_command_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_command_command` FOREIGN KEY (`id_command`) REFERENCES `command` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_image_article` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_user_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Contraintes pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `FK_wishlist_article` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
