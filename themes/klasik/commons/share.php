<div class="form-group" style="clear:both;">
  <ul id="pageshare" title="Bagikan ke teman anda" class="pagination">
    <li class="sbutton" id="fb"><a name="fb_share" href="http://www.facebook.com/sharer.php?u=<?= $link; ?>" target="_blank" rel="nofollow noopener"><i class="fa fa-facebook-square"></i>&nbsp;Facebook</a></li>
    <li class="sbutton" id="rt"><a href="http://twitter.com/share?url=<?= $link ?>" class="twitter-share-button" target="_blank" rel="nofollow noopener"><i class="fa fa-twitter"></i>&nbsp;Tweet</a></li>
    <li class="sbutton" id="wa_share"><a href="https://api.whatsapp.com/send?text=<?= $link; ?>" target="_blank" rel="nofollow noopener"><i class="fa fa-whatsapp" style="color:green"></i>&nbsp;WhatsApp</a></li>
    <li class="sbutton" id="tele_share"><a href="https://telegram.me/share/url?url=<?= $link; ?>&text=<?= htmlspecialchars($judul); ?>" target="_blank" rel="nofollow noopener"><i class="fa fa-telegram" style="color:blue"></i>&nbsp;Telegram</a></li>
  </ul>
</div>