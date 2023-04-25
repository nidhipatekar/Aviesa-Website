<?php
define("LOCAL_ENVIORNMENT", true);
if (constant("LOCAL_ENVIORNMENT") === true) {
    define("SERVER_NAME", "localhost");
    define("HOST_NAME", "localhost");
    define("DB_USER", "root");
    define("DB_PASSWORD", "");
    define("DB_NAME", "aviesa");
    define("BASE_URL", "http://localhost/xampp_projects/Website/");
} else {
    define("SERVER_NAME", "aviesa.in");
    define("HOST_NAME", "aviesa.in");
    define("DB_USER", "");
    define("DB_PASSWORD", "");
    define("DB_NAME", "");
    define("BASE_URL", "https://aviesa.in/");
}
define("KEY", "7654309876234567");
define("CIPHER", "AES-128-CTR");
define("OPTIONS", 0);
define("USER_ROLE", array("admin" => 1, "user" => 2));