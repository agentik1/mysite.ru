<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки базы данных
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'site' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'root' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', '' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'j1d@8Dm#{I21Q}@A$P~^#|/37)<D+i.4*s_QQ`2=a4`I0s2U8D:V6#Nc-[^$7Eie' );
define( 'SECURE_AUTH_KEY',  'IUr!C0>=TleM*+b)YC7DdX9}oi`8v,Y-#KCH=M1`-3CT2>toQ>W:XA@UOz_vO2kL' );
define( 'LOGGED_IN_KEY',    '/OOO2<KZ(fs/);`xWL9Fss{B~NVvYAgo` R+~SKO)wTvH|(7P*bP%aI[N[:<(j,z' );
define( 'NONCE_KEY',        '|_SgKp)}lbQt4sbvj6&<mskO^>~B)c|@g<dxD9LIo>3CjjU45~DU?y)_WI|ODPr;' );
define( 'AUTH_SALT',        '=/pLQD>v!5u|-n%YtB;E=6kxok]=1-?%T>S}#bb[pV>8|pTnV}hNiT|Q7!4=@yBu' );
define( 'SECURE_AUTH_SALT', 'wDgN2axYIV%;g-Y*@MW[6ymNEH``/h&x,4v.m$PZl0tvawqeP*[Qe5yrsGl3m=mv' );
define( 'LOGGED_IN_SALT',   'yD )S#frU@/AF1>l&+edkZ{Br,5 Gj[Ns=cw^>dy =GZI)z*LoXk(5PzEW%N5[|N' );
define( 'NONCE_SALT',       'I%i**h(u[SN=4YL]ar4T|Kq&Nr2/_xgv^`n?Tb>:(,t9H1-+reV>B`WJ/o^!cQ&a' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
