<?php

session_start();
?>

<html>

<head></head>

<body>


    <?php

    echo 'セッションを破棄しました<br>';

    $_SESSION = [];

    if (isset($_COOKIE['PHPSESSID'])) {
        setcookie('PHPSESSID', '', time() - 1800, '/');
    }

    session_destroy();

    echo 'セッション';
    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre>';

    echo 'クッキー';
    echo '<pre>';
    var_dump($_COOKIE);
    echo '</pre>';
    ?>

</body>

</html>