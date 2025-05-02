<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$eventFilePath = __DIR__ . '/event.json';
$customTime = "00:00:00";
$json = file_exists($eventFilePath) ? file_get_contents($eventFilePath) : file_get_contents($file);
$data = json_decode($json, true);
$file = ($data['hitungmundur'] ? __DIR__ . "/event.json" : "https://raw.githubusercontent.com/ariandii/json/main/calendar.json");
?>
<?php $result = getUpcomingEvent($file, $customTime); ?>
<?php if ($result['nextEvent'] !== null) : ?>
<?php
  $eventDateTime = strtotime($result['nextEvent']['date']);
  $remainingTime = $eventDateTime - time();
  $days = floor($remainingTime / (60 * 60 * 24));
  $hours = floor(($remainingTime % (60 * 60 * 24)) / (60 * 60));
  $minutes = floor(($remainingTime % (60 * 60)) / 60);
  $seconds = $remainingTime % 60;
  ?>
  <div id="countdown-<?= $result['index'] ?>">
    <div class="countdown-panel flexright">
      <div class="flexcenter">
        <div>
          <span style="color: #dbdbdb; font-family: 'ManifoldCF-Medium', sans-serif; font-weight: bold; font-size: smaller; text-shadow:-1px -1px 0 rgba(0,0,0,.3),1px -1px 0 rgba(0,0,0,.3),-1px 1px 0 rgba(0,0,0,.3),1px 1px 0 rgba(0,0,0,.3)"><?= ucwords(implode(", ", $result['nextEvent']['description'])) ?></span>
          <h3><?= ucwords(implode(", ", $result['nextEvent']['summary'])) ?></h3>
          <ul>
            <li><span id="days-<?= $result['index'] ?>"></span>Hari</li>
            <li><span id="hours-<?= $result['index'] ?>"></span>Jam</li>
            <li><span id="minutes-<?= $result['index'] ?>"></span>Menit</li>
            <li><span id="seconds-<?= $result['index'] ?>"></span>Detik</li>
          </ul>
          <span style="color: #dbdbdb; font-family: 'ManifoldCF-Medium', sans-serif; font-weight: bold; font-size: smaller; text-shadow:-1px -1px 0 rgba(0,0,0,.3),1px -1px 0 rgba(0,0,0,.3),-1px 1px 0 rgba(0,0,0,.3),1px 1px 0 rgba(0,0,0,.3)"><span id="event-details-<?= $result['index'] ?>"></span></span>
        </div>
      </div>
    </div>
  </div>
  <script>
    (function () {
      const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;
      const countDownDate = new Date(<?= json_encode(date('Y-m-d H:i:s', $result['index'])) ?>).getTime();
      const x = setInterval(function () {
        const now = new Date().getTime();
        const distance = countDownDate - now;
        document.getElementById("days-<?= $result['index'] ?>").innerText = Math.floor(distance / day);
        document.getElementById("hours-<?= $result['index'] ?>").innerText = Math.floor((distance % day) / hour);
        document.getElementById("minutes-<?= $result['index'] ?>").innerText = Math.floor((distance % hour) / minute);
        document.getElementById("seconds-<?= $result['index'] ?>").innerText = Math.floor((distance % minute) / second);
        const eventDate = new Date(<?= json_encode(date('Y-m-d', $result['index'])) ?>);
        const daysOfWeek = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const dayOfWeek = daysOfWeek[eventDate.getDay()];
        document.getElementById("event-details-<?= $result['index'] ?>").innerText = dayOfWeek + ", <?= str_replace('"', '', tgl_indo2(date('Y-m-d', $result['index']))) ?>";
        if (distance <= 0) {
          clearInterval(x);
          document.getElementById("countdown-<?= $result['index'] ?>").style.display = "none";
        }
      }, 1000);
    })();
  </script>
<?php endif; ?>
