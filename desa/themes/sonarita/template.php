<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" class="light" data-header-styles="light" data-menu-styles="dark">

    <?php $this->load->view("$folder_themes/core/header"); ?>

	<body>

        <!-- SWITCHER -->
        <?php $this->load->view("$folder_themes/core/setting"); ?>
        <!-- END SWITCHER -->

        <!-- LOADER -->
        <?php $this->load->view("$folder_themes/core/loader"); ?>
		<!-- END LOADER -->

        <!-- PAGE -->
		<div class="page">

            <!-- HEADER -->
            <?php $this->load->view("$folder_themes/core/app-header"); ?>
            <!-- END HEADER -->

            <!-- SIDEBAR -->
            <?php $this->load->view("$folder_themes/core/sidebar"); ?>
            <!-- END SIDEBAR -->

            <!-- MAIN-CONTENT -->
            <div class="content">
                <div class="main-content">
            
                    kontent
                </div>
            </div>

            <!-- END MAIN-CONTENT -->

            <!-- SEARCH-MODAL -->
            <?php $this->load->view("$folder_themes/core/search-modal"); ?>
            <!-- END SEARCH-MODAL -->

            <!-- FOOTER -->
            <?php $this->load->view("$folder_themes/core/footer"); ?>
            <!-- END FOOTER -->

		</div>
        <!-- END PAGE-->

        <?php $this->load->view("$folder_themes/core/js"); ?>

	</body>
</html>
