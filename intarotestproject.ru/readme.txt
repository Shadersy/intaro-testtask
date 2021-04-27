Для эксплутации веб-приложения создать собственный db.php в папке с проектом,
подключающий: require "libs/rb-mysql.php";
R::setup( 'server'; 'dbname="db_name", 'login','password');
старт сессии -> session_start();