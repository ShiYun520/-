<?php
// 数据库连接参数
$host = 'localhost';
$dbname = 'subscription_db';
$username = 'qiqi520';
$password = 'qiqi520';

try {
    // 创建数据库连接
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 连接失败时记录错误
    echo "数据库连接失败: " . $e->getMessage();
    exit;
}

// 获取传入的订阅ID
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // 删除订阅数据
    $stmt = $pdo->prepare("DELETE FROM subscriptions WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // 删除后重定向回订阅查询页面
    header("Location: subscriptions.php");
    exit;
} else {
    echo "无效的请求";
}
?>
