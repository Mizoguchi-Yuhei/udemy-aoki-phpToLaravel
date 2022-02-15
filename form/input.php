<?php

session_start();

header('X-FRAME-OPTIONS:DENY');

// スーパーグローバル変数 phpには9種類
if (!empty($_SESSION)) {
    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre>';
}

// XSS対策
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 入力、確認、完了
$pageFlag = 0;

if (!empty($_POST['btn_confirm'])) {
    $pageFlag = 1;
}
if (!empty($_POST['btn_submit'])) {
    $pageFlag = 2;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>input_form</title>
</head>

<body>
    <!-- 入力画面 -->
    <?php if ($pageFlag === 0) : ?>
        <?php
        if (!isset($_SESSION['csrfToken'])) {
            $csrfToken = bin2hex(random_bytes(32));
            $_SESSION['csrfToken'] = $csrfToken;
        }
        $token = $_SESSION['csrfToken'];
        ?>

        <form action="input.php" method="POST">
            氏名
            <input type="text" name="your_name" value="<?php if (!empty($_POST['your_name'])) {
                                                            echo h($_POST['your_name']);
                                                        } ?>">
            <br>
            メールアドレス
            <input type="email" name="email" value="<?php if (!empty($_POST)) {
                                                        echo h($_POST['email']);
                                                    } ?>">
            <br>

            <input type="submit" name="btn_confirm" value="確認する">
            <input type="hidden" name="csrf" value="<?php echo $token; ?>">
        </form>

    <?php endif; ?>


    <!-- 確認画面 -->
    <?php if ($pageFlag === 1) : ?>
        <?php if ($_POST['csrf'] === $_SESSION['csrfToken']) : ?>

            <form action="input.php" method="POST">
                氏名
                <?php echo h($_POST['your_name']); ?>
                <br>
                メールアドレス
                <?php echo h($_POST['email']); ?>
                <br>

                <!-- 戻るボタン -->
                <input type="submit" name="back" value="戻る">

                <input type="submit" name="btn_submit" value="送信する">

                <!-- 完了画面に値を渡す準備 -->
                <input type="hidden" name="your_name" value="<?php echo h($_POST['your_name']); ?>">
                <input type="hidden" name="email" value="<?php echo h($_POST['email']); ?>">
                <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']); ?>">
            </form>

        <?php endif; ?>
    <?php endif; ?>

    <!-- 完了画面 -->
    <?php if ($pageFlag === 2) : ?>
        <?php if ($_POST['csrf'] === $_SESSION['csrfToken']) : ?>

            送信が完了しました。

            <?php unset($_SESSION['csrfToken']); ?>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>