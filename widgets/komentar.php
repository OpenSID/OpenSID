<!-- widget Komentar-->
    <div class="single_bottom_rightbar">
        <h2><i class="fa fa-comments"></i> Tinggalkan Pesan</h2>
            <div class="tab-content">
              <div id="mostPopular2" class="tab-pane fade in active" role="tabpanel">
                <ul id="ul-menu">
					<div class="box-body">
                  <marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="3" direction="up" width="100%" height="280" align="center" behavior=”alternate”>
                      <ul class="sidebar-latest" id="li-komentar">
                        <?php foreach($komen As $data){?>
                          <li>
                            <i class="fa fa-comment"></i> <?php echo $data['owner']?> : <br /><?php echo $data['komentar']?><br /><small>dikirim pada <?php echo tgl_indo2($data['tgl_upload'])?></small>
                            <hr />
                          </li>
                        <?php }?>
                      </ul>
                    </marquee>
                  </div>
                </ul>
              </div>
            </div>
          </div>