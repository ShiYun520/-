<?php
header('Content-Type: application/json');

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
    error_log("数据库连接失败: " . $e->getMessage());
    echo json_encode(["error" => "数据库连接失败"]);
    exit;
}

// 获取当前日期
$currentDate = date('Y-m-d');

// 处理 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取原始 POST 数据
    $rawData = file_get_contents("php://input");
    $inputData = json_decode($rawData, true);

    if (!$inputData) {
        echo json_encode(["error" => "无效的输入数据"]);
        exit;
    }

    // 如果输入的数据是一个对象，转化为数组
    if (isset($inputData['name'])) {
        $inputData = [$inputData];
    }

    // 准备 SQL 插入语句
    $stmt = $pdo->prepare("INSERT INTO subscriptions (name, traffic, days, uploadTime, link) 
                           VALUES (:name, :traffic, :days, :uploadTime, :link)");

    // 循环插入每个订阅数据
    foreach ($inputData as $subscription) {
        $stmt->execute([
            ':name' => $subscription['name'] ?? '未提供名称',  // 如果没有提供名称，使用默认值
            ':traffic' => $subscription['traffic'] ?? 0,
            ':days' => $subscription['days'] ?? '未提供天数',
            ':uploadTime' => $subscription['uploadTime'] ?? $currentDate,
            ':link' => $subscription['link'] ?? ''
        ]);
    }

    // 返回成功信息
    echo json_encode(["message" => "订阅数据已成功上传"]);
    exit;
} else {
    // 处理 GET 请求，返回所有订阅数据
    $stmt = $pdo->query("SELECT * FROM subscriptions");
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subscriptions);
    exit;
}
?>
