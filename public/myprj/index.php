<?php
// Tự liệt kê tất cả file .php trong các thư mục bài tập
$files = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    if ($f->getExtension() === 'php' && $f->getFilename() !== 'index.php') {
        $files[] = str_replace(__DIR__ . '/', '', $f->getPathname());
    }
}
sort($files);
?>
<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Các bài tập</title></head>
<body>
    <h1>Các bài tập đã làm</h1>
    <ul>
    <?php foreach ($files as $f): ?>
        <li><a href="<?= htmlspecialchars(implode('/', array_map('rawurlencode', explode('/', $f)))) ?>"><?= htmlspecialchars($f) ?></a></li>
    <?php endforeach; ?>
    </ul>
</body>
</html>