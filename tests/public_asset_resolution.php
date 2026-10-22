<?php

declare(strict_types=1);

require_once __DIR__ . '/../function/PublicAsset.php';

function assertAsset(bool $expected, bool $actual, string $case): void
{
    if ($expected !== $actual) {
        throw new RuntimeException('Public asset access check failed: ' . $case);
    }
}

$scratchRoot = (string) getenv('TMPDIR');
if ($scratchRoot === '' || !is_dir($scratchRoot)) {
    throw new RuntimeException('TMPDIR must be an isolated scratch directory.');
}
$fixtureRoot = rtrim($scratchRoot, DIRECTORY_SEPARATOR) . '/magangverify-assets-' . bin2hex(random_bytes(8));
$assetsRoot = $fixtureRoot . '/assets';
$outsideRoot = $fixtureRoot . '/outside';
mkdir($assetsRoot . '/css', 0700, true);
mkdir($outsideRoot, 0700, true);
file_put_contents($assetsRoot . '/css/app.css', 'body{}');
file_put_contents($assetsRoot . '/source.php', '<?php echo "not public";');
file_put_contents($outsideRoot . '/private.css', 'not public');
symlink($outsideRoot . '/private.css', $assetsRoot . '/css/escape.css');

try {
    $css = resolvePublicAsset('/assets/css/app.css', $assetsRoot);
    assertAsset(true, is_array($css) && ($css['mime'] ?? '') === 'text/css', 'allowlisted asset resolves with an explicit MIME type');
    assertAsset(true, resolvePublicAsset('/assets/source.php', $assetsRoot) === null, 'PHP source is never exposed as a static asset');
    assertAsset(true, resolvePublicAsset('/assets/css/escape.css', $assetsRoot) === null, 'symlink escape is rejected');
    assertAsset(true, resolvePublicAsset('/controllers/LoginController.php', $assetsRoot) === null, 'non-asset repository paths are not served');
    assertAsset(true, resolvePublicAsset('/assets/missing.js', $assetsRoot) === null, 'missing assets resolve to not-found');
} finally {
    unlink($assetsRoot . '/css/escape.css');
    unlink($assetsRoot . '/css/app.css');
    unlink($assetsRoot . '/source.php');
    unlink($outsideRoot . '/private.css');
    rmdir($assetsRoot . '/css');
    rmdir($assetsRoot);
    rmdir($outsideRoot);
    rmdir($fixtureRoot);
}

fwrite(STDOUT, "Public asset containment checks passed (scratch fixtures; no server/database).\n");
