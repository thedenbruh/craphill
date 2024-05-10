-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 10 2024 г., 22:12
-- Версия сервера: 10.4.25-MariaDB
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `anarchyhill2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(60) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `admin`
--

INSERT INTO `admin` (`id`, `admin_id`, `action`, `time`) VALUES
(1, 1, 'Banned user2 for 0 minutes', '0000-00-00 00:00:00'),
(2, 1, 'Banned user3 for 0 minutes', '0000-00-00 00:00:00'),
(3, 3, 'Banned user2 for 0 minutes', '0000-00-00 00:00:00'),
(4, 5, 'Banned user2 for 0 minutes', '0000-00-00 00:00:00'),
(5, 5, 'Granted user1 item 3', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `aeo_table`
--

CREATE TABLE `aeo_table` (
  `winnerip` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `alert`
--

CREATE TABLE `alert` (
  `id` int(11) NOT NULL,
  `alert` varchar(225) NOT NULL,
  `textcolor` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL,
  `type` enum('warning','success','error') CHARACTER SET latin1 NOT NULL DEFAULT 'warning'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `avatar`
--

CREATE TABLE `avatar` (
  `user_id` int(11) NOT NULL,
  `head_color` varchar(6) NOT NULL,
  `torso_color` varchar(6) NOT NULL,
  `right_arm_color` varchar(6) NOT NULL,
  `left_arm_color` varchar(6) NOT NULL,
  `right_leg_color` varchar(6) NOT NULL,
  `left_leg_color` varchar(6) NOT NULL,
  `face` int(11) NOT NULL DEFAULT 0,
  `shirt` int(11) NOT NULL DEFAULT 0,
  `pants` int(11) NOT NULL DEFAULT 0,
  `tshirt` int(11) NOT NULL DEFAULT 0,
  `hat1` int(11) NOT NULL DEFAULT 0,
  `hat2` int(11) NOT NULL DEFAULT 0,
  `hat3` int(11) NOT NULL DEFAULT 0,
  `hat4` int(11) NOT NULL DEFAULT 0,
  `hat5` int(11) NOT NULL DEFAULT 0,
  `tool` int(11) NOT NULL DEFAULT 0,
  `head` int(11) NOT NULL DEFAULT 0,
  `cache` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `avatar`
--

INSERT INTO `avatar` (`user_id`, `head_color`, `torso_color`, `right_arm_color`, `left_arm_color`, `right_leg_color`, `left_leg_color`, `face`, `shirt`, `pants`, `tshirt`, `hat1`, `hat2`, `hat3`, `hat4`, `hat5`, `tool`, `head`, `cache`) VALUES
(1, 'f3b700', '3292d3', 'f3b700', 'f3b700', '76603f', '76603f', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1433134277),
(2, 'f3b700', '3292d3', 'f3b700', 'f3b700', '1c4399', '1c4399', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 83696176),
(3, 'f3b700', 'e58700', 'f3b700', 'f3b700', '76603f', '76603f', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1902622349),
(4, 'f3b700', '85ad00', 'f3b700', 'f3b700', '1d6a19', '1d6a19', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 475924343),
(5, 'f3b700', 'd34a05', 'f3b700', 'f3b700', '1c4399', '1c4399', 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1822207413),
(6, 'f3b700', '1d6a19', 'f3b700', 'f3b700', '1d6a19', '1d6a19', 19, 0, 0, 0, 4, 8, 0, 0, 0, 0, 0, 1551876962),
(7, 'f3b700', 'c60000', 'f3b700', 'f3b700', '650013', '650013', 0, 0, 0, 0, 6, 0, 0, 0, 0, 0, 0, 1160456292);

-- --------------------------------------------------------

--
-- Структура таблицы `awards`
--

CREATE TABLE `awards` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `beta_buy`
--

CREATE TABLE `beta_buy` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gross` decimal(5,2) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `beta_users`
--

CREATE TABLE `beta_users` (
  `id` int(11) NOT NULL,
  `username` varchar(26) NOT NULL,
  `usernameL` varchar(100) NOT NULL,
  `password` varchar(70) NOT NULL,
  `IP` varchar(46) NOT NULL,
  `birth` date NOT NULL,
  `gender` enum('male','female','hidden') NOT NULL DEFAULT 'hidden',
  `pronouns` enum('she/her','he/him','they/them') DEFAULT NULL,
  `date` date DEFAULT NULL,
  `last_online` datetime NOT NULL,
  `daily_bits` datetime NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `views` int(11) NOT NULL,
  `bucks` int(11) NOT NULL DEFAULT 1,
  `bits` int(11) NOT NULL DEFAULT 10,
  `primary_group` int(11) DEFAULT -1,
  `power` int(11) NOT NULL DEFAULT 0,
  `avatar_id` int(11) NOT NULL,
  `unique_key` varchar(20) NOT NULL,
  `theme` int(11) NOT NULL,
  `displayname` varchar(26) NOT NULL,
  `invite` int(11) NOT NULL,
  `verifiedshopdev` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `jackpot` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Структура таблицы `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` mediumtext NOT NULL,
  `information` mediumtext DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `pinned` int(11) NOT NULL,
  `image_link` varchar(255) NOT NULL,
  `image_alt` varchar(255) NOT NULL,
  `creation` date DEFAULT NULL,
  `background` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `clans`
--

CREATE TABLE `clans` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(26) NOT NULL,
  `tag` varchar(5) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `members` int(11) NOT NULL,
  `approved` enum('yes','no','declined') NOT NULL DEFAULT 'no',
  `funds` int(11) NOT NULL,
  `verified` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `clans_members`
--

CREATE TABLE `clans_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT 1,
  `status` enum('in','out','banned') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `clans_ranks`
--

CREATE TABLE `clans_ranks` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `power` int(11) NOT NULL,
  `name` varchar(26) NOT NULL,
  `perm_ranks` enum('yes','no') NOT NULL,
  `perm_posts` enum('yes','no') NOT NULL,
  `perm_members` enum('yes','no') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `clans_walls`
--

CREATE TABLE `clans_walls` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `post` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  `type` enum('pinned','normal','deleted') NOT NULL DEFAULT 'normal'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `crate`
--

CREATE TABLE `crate` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `serial` int(11) NOT NULL DEFAULT 0,
  `payment` enum('bits','bucks') NOT NULL DEFAULT 'bits',
  `price` int(11) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `own` enum('yes','no') NOT NULL DEFAULT 'yes'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Структура таблицы `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `verified` enum('yes','no') NOT NULL DEFAULT 'no',
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Структура таблицы `forum_banners`
--

CREATE TABLE `forum_banners` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_boards`
--

CREATE TABLE `forum_boards` (
  `id` int(11) NOT NULL,
  `name` varchar(26) NOT NULL,
  `description` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `forum_boards`
--

INSERT INTO `forum_boards` (`id`, `name`, `description`) VALUES
(1, 'robin can\'t do this', 'asdf'),
(2, 'shit hill', 'talk about rawdogs or else __halt_compiler();');

-- --------------------------------------------------------

--
-- Структура таблицы `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `forum_threads` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `locked` enum('yes','no') NOT NULL DEFAULT 'no',
  `pinned` enum('yes','no') NOT NULL DEFAULT 'no',
  `deleted` enum('yes','no') NOT NULL DEFAULT 'no',
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `views` int(11) NOT NULL,
  `latest_post` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



--
-- Структура таблицы `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `playing` int(11) NOT NULL DEFAULT 0,
  `visits` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `address` varchar(15) NOT NULL,
  `uid` varchar(20) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `type` enum('hat','shirt') NOT NULL,
  `robux` int(11) NOT NULL,
  `tickets` int(11) NOT NULL,
  `method` enum('free','both','robux','tickets','offsale') NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `item_comments`
--

CREATE TABLE `item_comments` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `list` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `uploaded` enum('Yes','No') NOT NULL,
  `bits` int(11) NOT NULL DEFAULT -1,
  `bucks` int(11) NOT NULL DEFAULT -1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Структура таблицы `membership`
--

CREATE TABLE `membership` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `length` int(11) NOT NULL,
  `active` enum('yes','no') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `membership_values`
--

CREATE TABLE `membership_values` (
  `value` int(11) NOT NULL,
  `name` varchar(12) NOT NULL,
  `daily_bucks` int(11) NOT NULL,
  `sets` int(11) NOT NULL,
  `items` int(11) NOT NULL,
  `create_clans` int(11) NOT NULL,
  `join_clans` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `author_id` varchar(26) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `title` varchar(52) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `read` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `misc` (
  `featured_game_id` varchar(1) NOT NULL,
  `alert` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `moderation`
--

CREATE TABLE `moderation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `reason` enum('Excessive or inappropriate use of profanity','Inappropriate/adult content','Requesting or giving private information','Engaging in third party/offsite deals','Harassing/bullying other users','Exploiting/scamming other users','Stolen account','Phishing/hacking/trading accounts','Other') NOT NULL,
  `admin_note` text NOT NULL,
  `issued` datetime NOT NULL,
  `length` int(11) NOT NULL,
  `active` enum('yes','no') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` varchar(3000) NOT NULL,
  `lupd` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `uid`, `title`, `body`, `lupd`) VALUES
(1, 1, 'a man has fallen into a river in lego city', '*other part of lego ad goes here*', '2024-05-10 16:00:44');

-- --------------------------------------------------------

--
-- Структура таблицы `promocodes`
--

CREATE TABLE `promocodes` (
  `code` varchar(255) NOT NULL,
  `itemid` varchar(100) NOT NULL DEFAULT '0',
  `uses` int(11) NOT NULL DEFAULT -1,
  `used` int(11) NOT NULL DEFAULT 0,
  `expired` tinyint(1) NOT NULL DEFAULT 0,
  `expirationdate` timestamp NOT NULL DEFAULT '2037-12-31 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gross` decimal(5,2) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) NOT NULL,
  `receipt` varchar(60) NOT NULL,
  `product` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `reg_keys`
--

CREATE TABLE `reg_keys` (
  `id` int(11) NOT NULL,
  `key_content` varchar(1000) NOT NULL,
  `used` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `r_type` varchar(10) NOT NULL,
  `r_id` int(11) NOT NULL,
  `r_reason` text DEFAULT NULL,
  `seen` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `reports`
--


-- --------------------------------------------------------

--
-- Структура таблицы `shop_items`
--

CREATE TABLE `shop_items` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(52) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `bucks` int(11) NOT NULL DEFAULT -1,
  `bits` int(11) NOT NULL DEFAULT -1,
  `type` varchar(10) NOT NULL COMMENT 'HAT | FACE | TOOL | SHIRT | TSHIRT | PANTS ',
  `date` date NOT NULL,
  `last_updated` date NOT NULL,
  `offsale` enum('yes','no') NOT NULL DEFAULT 'no',
  `collectible` enum('yes','no') NOT NULL DEFAULT 'no',
  `collectable-edition` enum('yes','no') NOT NULL DEFAULT 'no',
  `collectible_q` int(11) NOT NULL DEFAULT 0,
  `zoom` varchar(11) DEFAULT NULL,
  `approved` enum('yes','no','declined') NOT NULL,
  `private` int(11) NOT NULL,
  `eventinfo` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `site_banner`
--

CREATE TABLE `site_banner` (
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `site_banner`
--

INSERT INTO `site_banner` (`text`) VALUES
('some stuff is not fixed yet.<br>deal with it skids...');

-- --------------------------------------------------------

--
-- Структура таблицы `special_sellers`
--

CREATE TABLE `special_sellers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `serial` int(11) NOT NULL,
  `bucks` int(11) NOT NULL,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `statuses`
--

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `body` varchar(124) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `crap` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `theme selected` enum('defualt','theme1') NOT NULL DEFAULT 'defualt'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(70) NOT NULL,
  `age` enum('under13','over13') NOT NULL,
  `safechat` enum('safe','supersafe') NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `join_date` datetime NOT NULL DEFAULT current_timestamp(),
  `robux` int(11) NOT NULL DEFAULT 0,
  `tickets` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `last_seen` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `user_promocodes`
--

CREATE TABLE `user_promocodes` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `timeredeemed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `user_rewards`
--

CREATE TABLE `user_rewards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `user_walls`
--

CREATE TABLE `user_walls` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `post` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  `type` enum('pinned','normal','deleted') NOT NULL DEFAULT 'normal'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `aeo_table`
--
ALTER TABLE `aeo_table`
  ADD PRIMARY KEY (`winnerip`);

--
-- Индексы таблицы `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `beta_buy`
--
ALTER TABLE `beta_buy`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `beta_users`
--
ALTER TABLE `beta_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clans`
--
ALTER TABLE `clans`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clans_members`
--
ALTER TABLE `clans_members`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clans_ranks`
--
ALTER TABLE `clans_ranks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clans_walls`
--
ALTER TABLE `clans_walls`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `crate`
--
ALTER TABLE `crate`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forum_banners`
--
ALTER TABLE `forum_banners`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forum_boards`
--
ALTER TABLE `forum_boards`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forum_threads`
--
ALTER TABLE `forum_threads`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `item_comments`
--
ALTER TABLE `item_comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `list`
--
ALTER TABLE `list`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `membership_values`
--
ALTER TABLE `membership_values`
  ADD PRIMARY KEY (`value`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `misc`
--
ALTER TABLE `misc`
  ADD KEY `featured_game_id` (`featured_game_id`);

--
-- Индексы таблицы `moderation`
--
ALTER TABLE `moderation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`code`);

--
-- Индексы таблицы `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reg_keys`
--
ALTER TABLE `reg_keys`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `shop_items`
--
ALTER TABLE `shop_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `special_sellers`
--
ALTER TABLE `special_sellers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_promocodes`
--
ALTER TABLE `user_promocodes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_rewards`
--
ALTER TABLE `user_rewards`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_walls`
--
ALTER TABLE `user_walls`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `avatar`
--
ALTER TABLE `avatar`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `awards`
--
ALTER TABLE `awards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `beta_buy`
--
ALTER TABLE `beta_buy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `beta_users`
--
ALTER TABLE `beta_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `clans`
--
ALTER TABLE `clans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `clans_members`
--
ALTER TABLE `clans_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `clans_ranks`
--
ALTER TABLE `clans_ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `clans_walls`
--
ALTER TABLE `clans_walls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `crate`
--
ALTER TABLE `crate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `forum_banners`
--
ALTER TABLE `forum_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `forum_boards`
--
ALTER TABLE `forum_boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `forum_threads`
--
ALTER TABLE `forum_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `item_comments`
--
ALTER TABLE `item_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `list`
--
ALTER TABLE `list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `membership`
--
ALTER TABLE `membership`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `membership_values`
--
ALTER TABLE `membership_values`
  MODIFY `value` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `moderation`
--
ALTER TABLE `moderation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reg_keys`
--
ALTER TABLE `reg_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `shop_items`
--
ALTER TABLE `shop_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `special_sellers`
--
ALTER TABLE `special_sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_promocodes`
--
ALTER TABLE `user_promocodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_rewards`
--
ALTER TABLE `user_rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_walls`
--
ALTER TABLE `user_walls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
