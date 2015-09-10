-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 10 Septembre 2015 à 20:37
-- Version du serveur :  5.6.25
-- Version de PHP :  5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ihmsquid`
--

-- --------------------------------------------------------

--
-- Structure de la table `uf_authorize_group`
--

CREATE TABLE IF NOT EXISTS `uf_authorize_group` (
  `id` int(10) unsigned NOT NULL,
  `group_id` int(11) NOT NULL,
  `hook` varchar(200) NOT NULL COMMENT 'A code that references a specific action or URI that the group has access to.',
  `conditions` text NOT NULL COMMENT 'The conditions under which members of this group have access to this hook.'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `uf_authorize_group`
--

INSERT INTO `uf_authorize_group` (`id`, `group_id`, `hook`, `conditions`) VALUES
(1, 1, 'uri_dashboard', 'always()'),
(2, 2, 'uri_dashboard', 'always()'),
(3, 2, 'uri_users', 'always()'),
(4, 1, 'uri_account_settings', 'always()'),
(5, 1, 'update_account_setting', 'equals(self.id, user.id)&&in(property,["email","locale","password"])'),
(6, 2, 'update_account_setting', 'in(property,["email","display_name","title","locale","enabled"])'),
(7, 2, 'view_account_setting', 'in(property,["user_name","email","display_name","title","locale","enabled","groups","primary_group_id"])'),
(8, 2, 'delete_account', '!in_group(user.id,2)'),
(9, 2, 'create_account', 'always()'),
(10, 2, 'app_admin', 'always()'),
(11, 5, 'uri_access', 'always()'),
(12, 6, 'uri_filterconf', 'always()'),
(13, 7, 'uri_stats', 'always()');

-- --------------------------------------------------------

--
-- Structure de la table `uf_authorize_user`
--

CREATE TABLE IF NOT EXISTS `uf_authorize_user` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `hook` varchar(200) NOT NULL COMMENT 'A code that references a specific action or URI that the user has access to.',
  `conditions` text NOT NULL COMMENT 'The conditions under which the user has access to this action.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `uf_configuration`
--

CREATE TABLE IF NOT EXISTS `uf_configuration` (
  `id` int(10) unsigned NOT NULL,
  `plugin` varchar(50) NOT NULL COMMENT 'The name of the plugin that manages this setting (set to ''userfrosting'' for core settings)',
  `name` varchar(150) NOT NULL COMMENT 'The name of the setting.',
  `value` longtext NOT NULL COMMENT 'The current value of the setting.',
  `description` text NOT NULL COMMENT 'A brief description of this setting.'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='A configuration table, mapping global configuration options to their values.';

--
-- Contenu de la table `uf_configuration`
--

INSERT INTO `uf_configuration` (`id`, `plugin`, `name`, `value`, `description`) VALUES
(1, 'userfrosting', 'site_title', 'IHM SQUID', 'The title of the site.  By default, displayed in the title tag, as well as the upper left corner of every user page.'),
(2, 'userfrosting', 'admin_email', 'admin@userfrosting.com', 'The administrative email for the site.  Automated emails, such as activation emails and password reset links, will come from this address.'),
(3, 'userfrosting', 'email_login', '1', 'Specify whether users can login via email address or username instead of just username.'),
(4, 'userfrosting', 'can_register', '0', 'Specify whether public registration of new accounts is enabled.  Enable if you have a service that users can sign up for, disable if you only want accounts to be created by you or an admin.'),
(5, 'userfrosting', 'enable_captcha', '0', 'Specify whether new users must complete a captcha code when registering for an account.'),
(6, 'userfrosting', 'require_activation', '0', 'Specify whether email activation is required for newly registered accounts.  Accounts created on the admin side never need to be activated.'),
(7, 'userfrosting', 'resend_activation_threshold', '0', 'The time, in seconds, that a user must wait before requesting that the activation email be resent.'),
(8, 'userfrosting', 'reset_password_timeout', '10800', 'The time, in seconds, before a user''s password reminder email expires.'),
(9, 'userfrosting', 'default_locale', 'fr_FR', 'The default language for newly registered users.'),
(10, 'userfrosting', 'minify_css', '0', 'Specify whether to use concatenated, minified CSS (production) or raw CSS includes (dev).'),
(11, 'userfrosting', 'minify_js', '0', 'Specify whether to use concatenated, minified JS (production) or raw JS includes (dev).'),
(12, 'userfrosting', 'version', '0.3.0', 'The current version of UserFrosting.'),
(13, 'userfrosting', 'author', 'Florian Cartron, Eliot Gillet, Laurent Lecomte', 'The author of the site.  Will be used in the site''s author meta tag.'),
(14, 'userfrosting', 'show_terms_on_register', '0', 'Specify whether or not to show terms and conditions when registering.'),
(15, 'userfrosting', 'site_location', '', 'The nation or state in which legal jurisdiction for this site falls.'),
(16, 'userfrosting', 'install_status', 'complete', ''),
(17, 'userfrosting', 'root_account_config_token', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `uf_group`
--

CREATE TABLE IF NOT EXISTS `uf_group` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Specifies whether this permission is a default setting for new accounts.',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Specifies whether this permission can be deleted from the control panel.',
  `theme` varchar(100) NOT NULL DEFAULT 'default' COMMENT 'The theme assigned to primary users in this group.',
  `landing_page` varchar(200) NOT NULL DEFAULT 'account' COMMENT 'The page to take primary members to when they first log in.',
  `new_user_title` varchar(200) NOT NULL DEFAULT 'New User' COMMENT 'The default title to assign to new primary users.',
  `icon` varchar(100) NOT NULL DEFAULT 'fa fa-user' COMMENT 'The icon representing primary users in this group.'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `uf_group`
--

INSERT INTO `uf_group` (`id`, `name`, `is_default`, `can_delete`, `theme`, `landing_page`, `new_user_title`, `icon`) VALUES
(1, 'Utilisateurs', 1, 0, 'nyx', 'dashboard', 'Utilisateur du site', 'fa fa-user'),
(2, 'Administrateur', 0, 0, 'nyx', 'dashboard', 'Administrateur du site', 'fa fa-flag'),
(5, 'Accès de la salle', 2, 1, 'nyx', 'dashboard', 'Formateur', 'fa fa-user'),
(6, 'Gestion du filtrage', 0, 1, 'nyx', 'dashboard', 'Formateur', 'fa fa-user'),
(7, 'Statistiques d''utilisation', 0, 1, 'nyx', 'dashboard', 'Personnel Administratif', 'fa fa-user');

-- --------------------------------------------------------

--
-- Structure de la table `uf_group_user`
--

CREATE TABLE IF NOT EXISTS `uf_group_user` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Maps users to their group(s)';

--
-- Contenu de la table `uf_group_user`
--

INSERT INTO `uf_group_user` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 2, 2),
(4, 2, 5),
(5, 2, 6),
(6, 2, 7);

-- --------------------------------------------------------

--
-- Structure de la table `uf_salle`
--

CREATE TABLE IF NOT EXISTS `uf_salle` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `network` varchar(15) COLLATE utf8_bin NOT NULL,
  `mask_cidr` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `uf_user`
--

CREATE TABLE IF NOT EXISTS `uf_user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `activation_token` varchar(225) NOT NULL,
  `last_activation_request` datetime NOT NULL,
  `lost_password_request` tinyint(1) NOT NULL DEFAULT '0',
  `lost_password_timestamp` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(150) NOT NULL,
  `sign_up_stamp` datetime NOT NULL,
  `last_sign_in_stamp` datetime DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Specifies if the account is enabled.  Disabled accounts cannot be logged in to, but they retain all of their data and settings.',
  `primary_group_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Specifies the primary group for the user.',
  `locale` varchar(10) NOT NULL DEFAULT 'en_US' COMMENT 'The language and locale to use for this user.'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `uf_user`
--

INSERT INTO `uf_user` (`id`, `user_name`, `display_name`, `password`, `email`, `activation_token`, `last_activation_request`, `lost_password_request`, `lost_password_timestamp`, `active`, `title`, `sign_up_stamp`, `last_sign_in_stamp`, `enabled`, `primary_group_id`, `locale`) VALUES
(1, 'admin', 'admin', '$2y$10$aRDK/rBvEgJitNuxtvenCuPJcKfIxjmvqsFL8eabFSGi6mIIDR6PK', 'admin@admin.fr', '4bd088cd3f96f75d5eb2d9ee1729652b', '2015-09-01 13:45:58', 0, NULL, 1, 'Utilisateur root', '2015-09-01 13:45:58', '2015-09-10 15:37:14', 1, 1, 'fr_FR'),
(2, 'florian', 'Florian Cartron', '$2y$10$zlKxcGNxF0Yzuc0SgFOdk.ccvON3ogz7tPdc6MV79w80nRizvBP/.', 'florian.cartron@gmail.com', 'cd52c33fb49f568960305b53af55980e', '2015-09-02 15:45:56', 0, '2015-09-02 15:45:56', 1, 'New User', '2015-09-01 17:54:49', '2015-09-08 19:36:31', 1, 1, 'fr_FR');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `uf_authorize_group`
--
ALTER TABLE `uf_authorize_group`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `uf_authorize_user`
--
ALTER TABLE `uf_authorize_user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `uf_configuration`
--
ALTER TABLE `uf_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `uf_group`
--
ALTER TABLE `uf_group`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `uf_group_user`
--
ALTER TABLE `uf_group_user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `uf_salle`
--
ALTER TABLE `uf_salle`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `uf_user`
--
ALTER TABLE `uf_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `uf_authorize_group`
--
ALTER TABLE `uf_authorize_group`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `uf_authorize_user`
--
ALTER TABLE `uf_authorize_user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `uf_configuration`
--
ALTER TABLE `uf_configuration`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `uf_group`
--
ALTER TABLE `uf_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `uf_group_user`
--
ALTER TABLE `uf_group_user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `uf_salle`
--
ALTER TABLE `uf_salle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `uf_user`
--
ALTER TABLE `uf_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
