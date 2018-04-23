<?php
// Get songs
$curl = curl_init((isset($_SERVER['HTTPS']) ? "https" : "http")."://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}/api/call.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
$return = curl_exec($curl);
curl_close($curl);

$songs = json_decode($return, true);
print_r($songs);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Les derniers titres</title>

        <!-- Third party -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css" integrity="sha256-zIG416V1ynj3Wgju/scU80KAEWOsO5rRLfVyRDuOv7Q=" crossorigin="anonymous" />
        <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>

        <link rel="stylesheet" href="./css/main.css">
    </head>
    <body>
        <div id="main">
            <div class="container">
                <div id="songs-list">
                    <?php foreach ($songs['track'] as $key => $song): ?>
                        <article class="media box one-song">
                            <figure class="media-left">
                                <p class="image is-64x64">
                                    <img src="<?php echo $song['cover'] ?>">
                                </p>
                            </figure>
                            <div class="media-content">
                                <div class="content">
                                    <p class="song-artists"><?php echo $song['artists'] ?></p>
                                    <p class="song-title"><?php echo $song['title'] ?></p>
                                </div>
                            </div>
                            <div class="media-right">
                                <?php if ($key == 0): ?>
                                    <span class="tag is-primary has-text-weight-semibold is-uppercase">on air</span>
                                <?php else: ?>
                                    <p><i class="far fa-clock"></i> <?php echo date('H:i', strtotime($song['dateofdiff'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </body>
</html>
