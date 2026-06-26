<?php
// Include Configuration
require_once __DIR__ . '/common/config.php';

// Prepare Profile Data
$logo_src = 'https://ui-avatars.com/api/?name=SM&background=2563eb&color=fff&size=256'; // Fallback logo
if (!empty($settings)) {
    if ($settings['logo_type'] === 'upload' && !empty($settings['logo_upload'])) {
        $logo_src = 'upload/' . htmlspecialchars($settings['logo_upload']);
    } elseif ($settings['logo_type'] === 'url' && !empty($settings['logo_url'])) {
        $logo_src = htmlspecialchars($settings['logo_url']);
    }
}

$channel_name = !empty($settings['channel_name']) ? htmlspecialchars($settings['channel_name']) : 'SM YouTube Links';
$channel_desc = !empty($settings['channel_description']) ? htmlspecialchars($settings['channel_description']) : '';
$channel_url  = !empty($settings['channel_url']) ? htmlspecialchars($settings['channel_url']) : '#';

// Fetch Active Videos (Ordered by Display Order, then Newest First)
$videos_query = "SELECT * FROM videos WHERE status = 'active' ORDER BY display_order ASC, created_at DESC";
$videos_result = $conn->query($videos_query);

// Include Header
require_once __DIR__ . '/common/header.php';
?>

<div class="text-center mb-8 flex flex-col items-center">
    <a href="<?= $channel_url ?>" target="_blank" rel="noopener noreferrer" class="relative group block mb-4 outline-none">
        <div class="absolute inset-0 bg-primary rounded-full blur-md opacity-30 group-hover:opacity-60 transition duration-300"></div>
        <img src="<?= $logo_src ?>" alt="<?= $channel_name ?>" class="relative w-24 h-24 mx-auto rounded-full object-cover border-4 border-card shadow-xl transition-transform duration-300 group-hover:scale-105">
    </a>

    <a href="<?= $channel_url ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors duration-200 outline-none">
        <h1 class="text-2xl font-bold text-white mb-2 flex items-center justify-center gap-2">
            <?= $channel_name ?>
            <i class="fa-solid fa-circle-check text-primary text-sm" title="Verified"></i>
        </h1>
    </a>

    <?php if ($channel_desc): ?>
        <p class="text-gray-400 text-sm sm:text-base max-w-md mx-auto px-4 leading-relaxed">
            <?= nl2br($channel_desc) ?>
        </p>
    <?php endif; ?>
</div>

<div class="flex flex-col space-y-4 w-full px-2 sm:px-0">
    
    <?php if ($videos_result && $videos_result->num_rows > 0): ?>
        <?php while ($video = $videos_result->fetch_assoc()): ?>
            <?php
            // Thumbnail Resolution Logic
            $thumb_src = '';
            if ($video['thumbnail_type'] === 'upload' && !empty($video['thumbnail_upload'])) {
                $thumb_src = 'upload/' . htmlspecialchars($video['thumbnail_upload']);
            } elseif ($video['thumbnail_type'] === 'url' && !empty($video['thumbnail_url'])) {
                $thumb_src = htmlspecialchars($video['thumbnail_url']);
            } else {
                // Auto type or fallback
                $thumb_src = "https://img.youtube.com/vi/" . htmlspecialchars($video['youtube_video_id']) . "/hqdefault.jpg";
            }
            ?>
            
            <a href="<?= htmlspecialchars($video['youtube_url']) ?>" target="_blank" rel="noopener noreferrer" 
               class="flex items-center p-2 bg-card rounded-2xl border border-gray-800 hover-card group outline-none">
                
                <div class="relative shrink-0 w-24 h-16 sm:w-32 sm:h-20 mr-4 overflow-hidden rounded-xl bg-gray-900">
                    <img src="<?= $thumb_src ?>" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-300"></div>
                    <div class="absolute bottom-1 right-1 bg-black/70 text-white text-[10px] px-1.5 py-0.5 rounded flex items-center gap-1">
                        <i class="fa-brands fa-youtube text-red-500"></i>
                    </div>
                </div>

                <div class="flex-grow min-w-0 pr-2">
                    <h2 class="text-sm sm:text-base font-semibold text-gray-200 group-hover:text-white transition-colors duration-200 line-clamp-2 leading-tight">
                        <?= htmlspecialchars($video['title']) ?>
                    </h2>
                </div>

                <div class="shrink-0 pl-2 pr-3 text-gray-500 group-hover:text-primary transition-colors duration-300">
                    <div class="w-8 h-8 rounded-full bg-dark flex items-center justify-center group-hover:bg-primary/20">
                        <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
                    </div>
                </div>
            </a>
            
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center p-8 bg-card border border-gray-800 rounded-2xl">
            <i class="fa-regular fa-folder-open text-4xl text-gray-600 mb-3"></i>
            <p class="text-gray-400">No videos available at the moment.</p>
        </div>
    <?php endif; ?>

</div>

<?php 
// Include Bottom/Footer
require_once __DIR__ . '/common/bottom.php'; 
?>
