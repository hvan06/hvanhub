<?php
// Tự động liệt kê các file .php trong từng thư mục bài tập
$groups = [];
$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__, FilesystemIterator::SKIP_DOTS)
);
foreach ($it as $f) {
    if (strtolower($f->getExtension()) !== 'php') continue;
    $rel = str_replace('\\', '/', substr($f->getPathname(), strlen(__DIR__) + 1));
    if ($rel === 'index.php' || strpos($rel, 'vendor/') !== false) continue;
    $parts = explode('/', $rel);
    $group = count($parts) > 1 ? $parts[0] : 'Khác';
    $groups[$group][] = $rel;
}
ksort($groups, SORT_NATURAL | SORT_FLAG_CASE);
foreach ($groups as &$list) { natcasesort($list); }
unset($list);

function link_to(string $rel): string {
    return implode('/', array_map('rawurlencode', explode('/', $rel)));
}
function label(string $rel): string {
    $name = pathinfo($rel, PATHINFO_FILENAME);
    return trim(str_replace(['_', '-'], ' ', $name));
}
function group_title(string $g): string {
    $t = ucwords(str_replace(['-', '_'], ' ', $g));
    return preg_replace('/^Bt\b/', 'BT', $t);
}
$total = array_sum(array_map('count', $groups));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Các bài tập đã làm</title>
    <style>
        :root {
            --bg: #f4f7f5;
            --card: #ffffff;
            --text: #1f2a24;
            --muted: #6b7a72;
            --accent: #4caf50;
            --accent-dark: #388e3c;
            --border: #e2e9e4;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #14191a;
                --card: #1d2527;
                --text: #e8efe9;
                --muted: #93a39a;
                --border: #2b3639;
            }
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }
        header {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff;
            padding: 48px 20px 56px;
            text-align: center;
        }
        header h1 { margin: 0 0 8px; font-size: 2rem; }
        header p { margin: 0; opacity: .9; }
        main {
            max-width: 960px;
            margin: -28px auto 48px;
            padding: 0 16px;
        }
        section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px 22px 24px;
            margin-bottom: 20px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .05);
        }
        section h2 {
            margin: 0 0 16px;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        section h2 .count {
            font-size: .8rem;
            font-weight: 600;
            color: var(--accent-dark);
            background: color-mix(in srgb, var(--accent) 16%, transparent);
            padding: 2px 10px;
            border-radius: 999px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
        }
        a.item {
            display: block;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            transition: transform .15s, border-color .15s, box-shadow .15s;
        }
        a.item small {
            display: block;
            margin-top: 2px;
            font-weight: 400;
            color: var(--muted);
            font-size: .78rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        a.item:hover {
            transform: translateY(-3px);
            border-color: var(--accent);
            box-shadow: 0 6px 16px rgba(76, 175, 80, .22);
        }
        footer {
            text-align: center;
            color: var(--muted);
            font-size: .85rem;
            padding-bottom: 32px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Các bài tập đã làm</h1>
        <p>Lập trình web với PHP &middot; <?= $total ?> bài</p>
    </header>

    <main>
        <?php foreach ($groups as $group => $files): ?>
            <section>
                <h2>
                    <?= htmlspecialchars(group_title($group)) ?>
                    <span class="count"><?= count($files) ?> bài</span>
                </h2>
                <div class="grid">
                    <?php foreach ($files as $rel): ?>
                        <a class="item" href="<?= htmlspecialchars(link_to($rel)) ?>">
                            <?= htmlspecialchars(label($rel)) ?>
                            <small><?= htmlspecialchars($rel) ?></small>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>

    <footer>Bấm vào một bài để chạy</footer>
</body>
</html>
