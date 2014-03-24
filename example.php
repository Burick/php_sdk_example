<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PHP SDK Example</title>
        <link href="http://www.odnoklassniki.ru/oauth/resources.do?type=css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        /*
        Этот код необходимо добавить один раз перед первым использованием SDK, указав в директиве require путь к файлу с SDK.
        Он подключает SDK и проверяет, установлена ли необходимая для работы SDK библиотека curl.
        */
        require("./odnoklassniki_sdk.php");
        if (!OdnoklassnikiSDK::checkCurlSupport()){
            print "У вас не установлен модуль curl, который требуется для работы с SDK одноклассников.  Инструкция по установке есть, например, <a href=\"http://www.php.net/manual/en/curl.installation.php\">здесь</a>.";
            return;
        }
        ?>
        <?php
        /*
        Это пример использования SDK.
        */
        $template = "<div id=\"t\"><div id=\"tr\"><div class=\"tc\" id=\"tc1\"><img src=\"%s\" class=\"pic\" alt=\"user photo\">%s</div><div class=\"tc\" id=\"tc2\">дружит с</div><div class=\"tc\" id=\"tc3\"><img src=\"%s\" class=\"pic\" alt=\"user photo\">%s</div></div></div>";
        // если в запросе есть параметр code (считается, что параметр получен после авторизации пользователя на ok)
        if (!is_null(OdnoklassnikiSDK::getCode())){
            if(OdnoklassnikiSDK::changeCodeToToken(OdnoklassnikiSDK::getCode())){
                // пример вызова метода с параметрами
                // запрашиваем информацию о текущем пользователе
                $current_user = OdnoklassnikiSDK::makeRequest("users.getCurrentUser", array("fields" => "name,pic_5"));
                // пример вызова метода без параметров
                // запрашиваем списко друзей пользователя
                $friends = OdnoklassnikiSDK::makeRequest("friends.get");
                // запрашиваем имя и ссылку на фото первого друга из списка
                $first_friend = OdnoklassnikiSDK::makeRequest("users.getInfo", array("fields" => "name,pic_5", "uids" => $friends[0]))[0];
                printf($template, $current_user["pic_5"], $current_user["name"], $first_friend["pic_5"], $first_friend["name"]);
            }
        } else {
        print "<div><a class=\"odkl-oauth-lnk\" href=\"http://www.odnoklassniki.ru/oauth/authorize?client_id=217568256&scope=VALUABLE_ACCESS&response_type=code&redirect_uri=http://artem.deaddev.com/php_sdk/example.php\"></a></div>";
        }
        ?>
    </body>
</html>