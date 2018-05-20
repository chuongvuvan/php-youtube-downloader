<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/20/18
 * Time: 17:23
 */
include 'youtube_download.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Youtube Download</title>
    <style>
        .center {
            margin-top: 50px;
            align-content: center;
            text-align: center;
        }
    </style>
</head>
<body>
<form class="center" action="youtube.php" method="post">
    Youtube ID:
    <input type="text" name="youtube_id" value="">
    <input type="submit" value="Submit">
</form>
<div class="center">
    <?php
    $youtubeId     = isset($_POST['youtube_id']) ? htmlentities($_POST['youtube_id']) : '';
    $link_download = '';
    if (!empty($youtubeId)) {
        $list_link = get_link_download_from_youtube($youtubeId);
        if ($list_link === null) {
            echo "Hình như YoutubeId đếu đúng nên đếu lấy được link";
        } else {
            foreach ($list_link as $key => $item) {
                echo 'Download chất lượng <strong>' . $item['quality'] . '</strong> - Link Download: <a download href=\'' . trim($item['url']) . '\'>Click to Download</a><br />';
                $link_download .= trim($item['url']) . "\n";
            }
        }
    }
    echo '<br /><br />Copy list Link: <br /><br /><textarea rows="10" cols="500">' . $link_download . '</textarea>';
    ?>
</div>
</body>
</html>
