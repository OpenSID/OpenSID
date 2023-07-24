@if ($saas->count() != 0 && $saas->first()->sisa_aktif < 21)
<div class="row">
  <div class='col-md-12'>
    <div class="callout callout-warning">
      <h4><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;Pengingat Layanan Saas!</h4>
      <p align="justify">
        Pelanggan yang terhomat, 
        <br>
        Ini adalah pengingat layanan Saas akan segera berakhir dalam waktu {{ $saas->first()->sisa_aktif }} hari
        <br> 
        Perlu diketahui bahwa jika layanan tidak diperpanjang, situs web atau layanan apa pun yang terkait layanan Saas akan berhenti bekerja. Perbarui sekarang untuk menghindari gangguan dalam layanan.
      </p>
       
    </div>
    
  </div>
</div>
@endif
 