<!-- widget Komentar-->
    <div class="single_bottom_rightbar">
        <h2><i class="fa fa-comments"></i> Komentar Terkini</h2>
            <div class="tab-content">
              <div id="mostPopular2" class="tab-pane fade in active" role="tabpanel">
                <ul id="ul-menu">
					<div class="box-body">
                  <marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="3" direction="up" width="100%" height="280" align="center" behavior=”alternate”>
                      <ul class="sidebar-latest" id="li-komentar">
                        <?php foreach($komen As $data){?>
                          <li>
                            <table class="table table-bordered table-striped dataTable table-hover nowrap">
                                <thead class="bg-gray disabled color-palette">
                                    <tr>
                                        <th><i class="fa fa-comment"></i> <?= $data['owner']?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <font color='green'><small><?= tgl_indo2($data['tgl_upload'])?></small></font><br/><?= $data['komentar']?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                          </li>
                        <?php }?>
                      </ul>
                    </marquee>
                  </div>
                </ul>
              </div>
            </div>
          </div>