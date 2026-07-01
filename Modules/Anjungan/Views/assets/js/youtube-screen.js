// Load YouTube API
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;

function onYouTubeIframeAPIReady() {
    player = new YT.Player('yt-video', {
        events: {
            'onStateChange': onPlayerStateChange
        }
    });
}

function onPlayerStateChange(event) {
    if (event.data === YT.PlayerState.ENDED) {
        // tampilkan overlay
        document.getElementById('video-overlay').style.display = 'flex';
    }
}

// Klik overlay → mulai ulang video
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('video-overlay').addEventListener('click', function() {
        this.style.display = 'none'; // sembunyikan overlay
        player.seekTo(0);            // kembalikan video ke awal
        player.playVideo();          // putar kembali
    });
});