<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Streaming</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/6.2.0/alt/video-js-cdn.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/6.2.0/video.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.5.3/videojs-contrib-hls.min.js"></script>
</head>
<body>
<video id="player" class="video-js vjs-default-skin" width="640" height="360" controls>
    <source src="http://messenger.moviao.com:8080/hls/hahaha.m3u8" type="application/x-mpegURL" />
</video>
<script>
    var player = videojs('#player');
    player.play();
</script>
</body>
</html>