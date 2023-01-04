-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 04 2023 г., 18:20
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `qak`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin_command`
--

CREATE TABLE `admin_command` (
  `id` int NOT NULL,
  `uid` int NOT NULL,
  `command` varchar(300) NOT NULL,
  `time` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `black_list`
--

CREATE TABLE `black_list` (
  `id` int NOT NULL,
  `user_blocker` int NOT NULL,
  `user_blocked` int NOT NULL,
  `date_public` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `chats`
--

CREATE TABLE `chats` (
  `id` int NOT NULL,
  `token` varchar(70) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `description` varchar(120) DEFAULT NULL,
  `color` varchar(6) NOT NULL DEFAULT 'f6f6f6',
  `cuid` int NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `time` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `chats_members`
--

CREATE TABLE `chats_members` (
  `id` int NOT NULL,
  `ctoken` varchar(70) NOT NULL,
  `uid` int NOT NULL,
  `rank` int NOT NULL DEFAULT '1',
  `time` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `message` varchar(250) NOT NULL,
  `date_public` varchar(30) NOT NULL DEFAULT '1970-01-01 03.00.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `comments_likes`
--

CREATE TABLE `comments_likes` (
  `id` int NOT NULL,
  `uid` int NOT NULL DEFAULT '0',
  `pid` int NOT NULL DEFAULT '0',
  `cid` int NOT NULL DEFAULT '0',
  `time` int NOT NULL DEFAULT '1234000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `dialog`
--

CREATE TABLE `dialog` (
  `id` int NOT NULL,
  `did` varchar(30) NOT NULL,
  `uid` int NOT NULL,
  `uid2` int NOT NULL,
  `date` varchar(30) NOT NULL DEFAULT '1997-01-01 00:00:00',
  `date2` varchar(30) NOT NULL DEFAULT '1970-01-01 03:00:00',
  `status` int NOT NULL DEFAULT '1',
  `send` int NOT NULL,
  `recive` int NOT NULL,
  `key` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `dialog_messages`
--

CREATE TABLE `dialog_messages` (
  `id` int NOT NULL,
  `did` int NOT NULL,
  `uid` int NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'text',
  `text` varchar(10000) DEFAULT 'Text',
  `source` varchar(500) DEFAULT NULL,
  `reply` varchar(10000) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `date` varchar(30) DEFAULT '1997-01-01 03:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `follows`
--

CREATE TABLE `follows` (
  `id` int NOT NULL,
  `follower_id` int NOT NULL,
  `followed_id` int NOT NULL,
  `date_follow` varchar(30) DEFAULT NULL,
  `date_confirm` varchar(30) DEFAULT NULL,
  `confirm` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `invites`
--

CREATE TABLE `invites` (
  `id` int NOT NULL,
  `invite` varchar(500) NOT NULL DEFAULT 'XXXX-XXXX-XXXX',
  `date` varchar(250) DEFAULT NULL,
  `date_activated` varchar(250) DEFAULT NULL,
  `uid` int NOT NULL,
  `utoken` varchar(250) DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'system',
  `category` varchar(20) DEFAULT NULL,
  `message` varchar(200) DEFAULT NULL,
  `message2` varchar(200) DEFAULT NULL,
  `message3` varchar(200) DEFAULT NULL,
  `readed` tinyint(1) NOT NULL DEFAULT '0',
  `date_public` varchar(30) DEFAULT '1997-01-01 03:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `post_id` varchar(30) NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `creator_id` int NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'post',
  `type_emotion` varchar(10) NOT NULL DEFAULT 'single' COMMENT 'sigle - Одиночный, multi - Множественный',
  `category` int NOT NULL DEFAULT '0',
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `clip` tinyint(1) NOT NULL DEFAULT '0',
  `commented` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(100) DEFAULT NULL,
  `message` text,
  `image1` varchar(200) DEFAULT NULL,
  `image2` varchar(200) DEFAULT NULL,
  `image3` varchar(200) DEFAULT NULL,
  `video1` varchar(200) DEFAULT NULL,
  `language` varchar(2) NOT NULL DEFAULT 'en',
  `date_view` int NOT NULL DEFAULT '0',
  `date_public` varchar(30) NOT NULL DEFAULT '1970-01-01 03:00:00',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `post_category_favourites`
--

CREATE TABLE `post_category_favourites` (
  `id` int NOT NULL,
  `category` int NOT NULL,
  `uid` int NOT NULL,
  `time` int NOT NULL DEFAULT '123411111'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `post_emotions`
--

CREATE TABLE `post_emotions` (
  `id` int NOT NULL,
  `pid` int NOT NULL,
  `uid` int NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'like',
  `date_pub` varchar(30) NOT NULL DEFAULT '1970-01-01 03:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `post_views`
--

CREATE TABLE `post_views` (
  `id` int NOT NULL,
  `uid` int NOT NULL,
  `pid` int NOT NULL,
  `time` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `sender` int NOT NULL,
  `category` varchar(20) NOT NULL DEFAULT 'spam',
  `comment` varchar(200) DEFAULT NULL,
  `time` int NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1 - На рассмотрении. 2 - Отклонено. 3 - Рассмотрено',
  `rev` int NOT NULL,
  `rev_comment` varchar(200) DEFAULT NULL,
  `rev_time` int NOT NULL,
  `content` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `reports_comments`
--

CREATE TABLE `reports_comments` (
  `id` int NOT NULL,
  `data` varchar(20) NOT NULL DEFAULT 'spam',
  `date_reported` varchar(40) DEFAULT '1997-01-01 03:00:00',
  `user_id` int NOT NULL DEFAULT '0',
  `comment_id` varchar(11) NOT NULL DEFAULT '0',
  `comment_message` varchar(500) DEFAULT NULL,
  `message` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `reports_post`
--

CREATE TABLE `reports_post` (
  `id` int NOT NULL,
  `data` varchar(20) DEFAULT 'spam',
  `date_reported` varchar(40) DEFAULT '1997-01-01 03:00:00',
  `user_id` int NOT NULL DEFAULT '0',
  `message` varchar(100) DEFAULT NULL,
  `post_id` int NOT NULL DEFAULT '0',
  `post_message` varchar(500) DEFAULT NULL,
  `post_image1` varchar(500) DEFAULT NULL,
  `post_image2` varchar(500) DEFAULT NULL,
  `post_image3` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `reports_user`
--

CREATE TABLE `reports_user` (
  `id` int NOT NULL,
  `rep_id` int NOT NULL,
  `user_id` int NOT NULL,
  `data` varchar(30) DEFAULT 'spam',
  `message` varchar(200) DEFAULT NULL,
  `date_reported` varchar(30) NOT NULL DEFAULT '1997-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `uploaded_files`
--

CREATE TABLE `uploaded_files` (
  `id` int NOT NULL,
  `uid` int NOT NULL,
  `crypt` varchar(70) NOT NULL,
  `full_url` varchar(700) NOT NULL,
  `short_url` varchar(700) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'post',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `oauth_id` int NOT NULL DEFAULT '0',
  `ip` varchar(50) DEFAULT NULL,
  `interval_m` int NOT NULL DEFAULT '10000',
  `limit_post` int NOT NULL DEFAULT '2000',
  `rang` int NOT NULL DEFAULT '1' COMMENT '1 - пользователь, 2 - модератор, 3 - админ, 4 - разработчик',
  `type_auth` varchar(20) NOT NULL DEFAULT 'site',
  `nickname` varchar(40) DEFAULT NULL,
  `name` text,
  `avatar` varchar(200) DEFAULT NULL,
  `avatar_small` varchar(200) DEFAULT NULL,
  `background` varchar(500) DEFAULT NULL,
  `about` varchar(150) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `login` varchar(35) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT 'user',
  `password` varchar(200) NOT NULL,
  `url_site` varchar(50) DEFAULT NULL,
  `url_social` varchar(50) DEFAULT NULL,
  `url_phone` varchar(50) DEFAULT NULL,
  `url_email` varchar(50) DEFAULT NULL,
  `banned_mesage` varchar(100) DEFAULT 'Ваш аккаунт был заблокирован.',
  `email` varchar(100) DEFAULT NULL,
  `online` int NOT NULL,
  `favourite_posts` varchar(100) DEFAULT '0, 999',
  `reported` tinyint(1) NOT NULL DEFAULT '1',
  `report_posts` tinyint(1) NOT NULL DEFAULT '1',
  `report_comments` tinyint(1) NOT NULL DEFAULT '1',
  `verification_type` varchar(20) NOT NULL DEFAULT 'popular',
  `verification` tinyint(1) NOT NULL DEFAULT '0',
  `email_authorization` tinyint(1) NOT NULL DEFAULT '0',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `scam` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `restore_password` tinyint(1) NOT NULL DEFAULT '1',
  `find_me` tinyint(1) NOT NULL DEFAULT '1',
  `public_post` tinyint(1) NOT NULL DEFAULT '1',
  `chat_creating` tinyint(1) NOT NULL DEFAULT '1',
  `chat_joined` tinyint(1) NOT NULL DEFAULT '1',
  `chat_read` tinyint(1) NOT NULL DEFAULT '1',
  `show_online` tinyint(1) NOT NULL DEFAULT '1',
  `show_url` tinyint(1) NOT NULL DEFAULT '0',
  `private_message` tinyint(1) NOT NULL DEFAULT '1',
  `hide_popup_rec` tinyint(1) NOT NULL DEFAULT '0',
  `date_banned` varchar(30) NOT NULL DEFAULT '1970-01-01 03:00:00',
  `date_registration` varchar(30) NOT NULL,
  `date_upd_avatar` int DEFAULT NULL,
  `date_upd_login` int DEFAULT NULL,
  `date_upd_password` int DEFAULT NULL,
  `date_upd_restore_password` int DEFAULT NULL,
  `date_verification` varchar(30) DEFAULT NULL,
  `date_last_extract` int NOT NULL,
  `date_last_send_code` int NOT NULL,
  `date_last_restore_password` int NOT NULL,
  `date_last_post` int NOT NULL DEFAULT '0',
  `language` varchar(2) DEFAULT 'en',
  `email_restore_password_code` varchar(70) DEFAULT NULL,
  `email_authorization_code` varchar(70) DEFAULT NULL,
  `token` varchar(150) NOT NULL,
  `token_public` varchar(70) NOT NULL,
  `token_access` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'site',
  `sid` varchar(100) NOT NULL,
  `uid` int NOT NULL,
  `utoken` varchar(100) NOT NULL,
  `uagent` varchar(500) NOT NULL,
  `uip` varchar(40) NOT NULL,
  `time` int NOT NULL,
  `lasttime` int NOT NULL,
  `maxtime` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_command`
--
ALTER TABLE `admin_command`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `black_list`
--
ALTER TABLE `black_list`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `chats_members`
--
ALTER TABLE `chats_members`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments_likes`
--
ALTER TABLE `comments_likes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dialog`
--
ALTER TABLE `dialog`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dialog_messages`
--
ALTER TABLE `dialog_messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `invites`
--
ALTER TABLE `invites`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `post_category_favourites`
--
ALTER TABLE `post_category_favourites`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `post_emotions`
--
ALTER TABLE `post_emotions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `post_views`
--
ALTER TABLE `post_views`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reports_comments`
--
ALTER TABLE `reports_comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reports_post`
--
ALTER TABLE `reports_post`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reports_user`
--
ALTER TABLE `reports_user`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin_command`
--
ALTER TABLE `admin_command`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `black_list`
--
ALTER TABLE `black_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `chats_members`
--
ALTER TABLE `chats_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments_likes`
--
ALTER TABLE `comments_likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `dialog`
--
ALTER TABLE `dialog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `dialog_messages`
--
ALTER TABLE `dialog_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `invites`
--
ALTER TABLE `invites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_category_favourites`
--
ALTER TABLE `post_category_favourites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_emotions`
--
ALTER TABLE `post_emotions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_views`
--
ALTER TABLE `post_views`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reports_comments`
--
ALTER TABLE `reports_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reports_post`
--
ALTER TABLE `reports_post`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reports_user`
--
ALTER TABLE `reports_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `uploaded_files`
--
ALTER TABLE `uploaded_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
