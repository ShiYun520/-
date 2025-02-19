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

// 获取所有订阅数据
$stmt = $pdo->query("SELECT * FROM subscriptions");
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订阅查询</title>
    <style>
        /* 设置全局字体、背景等 */
        /* ... existing code ... */

/* 更新全局样式 */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f6f9fc, #e9ecef);
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

/* 更新页面主标题样式 */
h1 {
    text-align: center;
    color: #2c3e50;
    padding: 40px 0 10px;
    margin: 0;
    font-size: 2.5em;
    font-weight: 600;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

/* 更新容器样式 */
.container {
    max-width: 1100px;
    margin: 30px auto 60px;
    padding: 30px;
    background-color: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    transition: transform 0.3s ease;
}

.container:hover {
    transform: translateY(-5px);
}

/* 更新表格样式 */
/* 更新表格样式 */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    table-layout: fixed; /* 添加固定布局 */
}

/* 更新表头样式 */
thead tr {
    background-color: #f8f9fa;
}

thead th {
    font-weight: 600;
    color: #2c3e50;
    padding: 16px 20px;
    text-transform: uppercase;
    font-size: 0.85em;
    letter-spacing: 0.5px;
    text-align: center; /* 居中对齐 */
}

/* 更新单元格样式 */
td {
    padding: 16px 20px;
    color: #495057;
    border-bottom: 1px solid #f1f3f5;
    font-size: 0.95em;
    text-align: center; /* 居中对齐 */
    word-break: break-all; /* 处理长文本 */
    vertical-align: middle; /* 垂直居中 */
}

/* 链接列特殊处理 */
td:nth-child(5) {
    max-width: 300px; /* 限制链接列宽度 */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* 操作列特殊处理 */
td:last-child {
    width: 100px; /* 固定操作列宽度 */
}


/* 更新删除按钮样式 */
button {
    padding: 8px 16px;
    background-color: #ff6b6b;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9em;
    font-weight: 500;
}

button:hover {
    background-color: #fa5252;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(250, 82, 82, 0.2);
}

/* 添加响应式设计 */
@media (max-width: 768px) {
    .container {
        margin: 20px;
        padding: 15px;
    }
    
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    th, td {
        padding: 12px 15px;
    }
    
    h1 {
        font-size: 2em;
        padding: 30px 0 5px;
    }
}

/* 添加空状态样式 */
.container p {
    font-size: 1.1rem;
    color: #6c757d;
    text-align: center;
    margin: 40px 0;
    padding: 40px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px dashed #dee2e6;
}

    </style>
</head>
<body>

    <h1>订阅数据</h1>

    <div class="container">
        <?php if ($subscriptions): ?>
            <table>
                <thead>
                    <tr>
                        <th>订阅名称</th>
                        <th>可用流量</th>
                        <th>剩余天数</th>
                        <th>上传时间</th>
                        <th>链接</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscriptions as $subscription): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subscription['name']); ?></td>
                            <td><?php echo htmlspecialchars($subscription['traffic']); ?></td>
                            <td><?php echo htmlspecialchars($subscription['days']); ?></td>
                            <td><?php echo htmlspecialchars($subscription['uploadTime']); ?></td>
                            <td>
                                <!-- 若链接需要可点击打开，可以用 <a> 包裹： -->
                                <a href="<?php echo htmlspecialchars($subscription['link']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($subscription['link']); ?>
                                </a>
                            </td>
                            <td>
                                <form method="POST" action="delete_subscription.php" style="margin:0;" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="id" value="<?php echo $subscription['id']; ?>">
                                    <button type="submit">删除</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>没有找到订阅数据。</p>
        <?php endif; ?>

        <!-- 可选：返回首页或上传页面的按钮（如果有需要） -->
        <!-- <a class="back-btn" href="index.html">返回上传页面</a> -->
    </div>

    <script>
        // 提示确认删除
        function confirmDelete() {
            return confirm("你确定要删除这条订阅吗？");
        }
    </script>

</body>
</html>
