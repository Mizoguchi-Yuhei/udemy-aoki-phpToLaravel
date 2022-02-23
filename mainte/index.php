<?php

// ファイルの読み込み
require 'db_connection.php';

// ユーザー入力なし query
// $sql = "select * from contacts where id = 2";
// $stmt = $pdo->query($sql);  // sql実行 ステートメント

// $result = $stmt->fetchAll();

// echo '<pre>';
// var_dump($result);
// echo '</pre>';


// ユーザー入力あり prepare bind execute SQLインジェクション対策
// $sql = "select * from contacts where id = ?";  // プレイスホルダー
$sql = "select * from contacts where id = :id";  // 名前付きプレイスホルダー
$stmt = $pdo->prepare($sql);  // プリペアードステイトメント
$stmt->bindValue('id', 3, PDO::PARAM_INT);  // 紐付け
$stmt->execute();  // 実行

$result2 = $stmt->fetchAll();

echo '<pre>';
var_dump($result2);
echo '</pre>';


// トランザクション まとまって処理 beginTransaction, commit, rollback
$pdo->beginTransaction();

try {
    // SQL処理
    $stmt = $pdo->prepare($sql);  // プリペアードステイトメント
    $stmt->bindValue('id', 3, PDO::PARAM_INT);  // 紐付け
    $stmt->execute();  // 実行
} catch (PDOException $e) {
    $pdo->rollBack();  // 更新のキャンセル
}
