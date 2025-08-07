<?php
// get_videos.php
require_once __DIR__ . '/../includes/config.php';

// Grab all videos with their categories, newest first
$sql = <<<SQL
SELECT c.name AS category, v.id, v.title, v.description, v.youtube_video_id, v.published_at
FROM categories c
LEFT JOIN video_categories vc ON c.id = vc.category_id
LEFT JOIN videos v ON vc.video_id = v.id
ORDER BY v.published_at DESC
SQL;

$rows = $pdo->query($sql)->fetchAll();

// Group into 'recent' and by category
$recent = [];
$cats   = [];

foreach ($rows as $r) {
  if ($r['id']) {
    $recent[] = [
      'id'               => $r['id'],
      'title'            => $r['title'],
      'description'      => $r['description'],
      'youtube_video_id' => $r['youtube_video_id'],
      'category'         => $r['category'] ?: 'Uncategorized',
      'published_at'     => $r['published_at'],
    ];
    $catName = $r['category'] ?: 'Uncategorized';
    $cats[$catName][] = end($recent);
  }
}

// Keep only top 10 most recent
$recent = array_slice($recent, 0, 10);

echo json_encode([
  'recent'     => $recent,
  'categories' => $cats
]);
