<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script src="<?php echo base_url("$this->theme_folder/natra/assets/js/jquery.min.js"); ?>"></script> 
<script src="<?php echo base_url("$this->theme_folder/natra/assets/js/wow.min.js"); ?>"></script> 
<script src="<?php echo base_url("$this->theme_folder/natra/assets/js/slick.min.js"); ?>"></script> 
<script src="<?php echo base_url("$this->theme_folder/natra/assets/js/custom.js"); ?>"></script>
<script type="text/javascript">
    (function () {
        var options = {
            whatsapp: "+628115222660",
            facebook: "kulinersemut",
            telegram: "ariandi",
            email: "natai.raya@mail.com",
            sms: "+628115222660",
            call: "+628115222660",
            company_logo_url: "<?php echo LogoDesa($desa['logo']);?>", // URL of company logo (png, jpg, gif)
            greeting_message: "Selamat Datang di Website <?php echo ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'];?>.",
            //call_to_action: "Kontak",
            button_color: "#e64946",
            position: "left", // Position may be 'right' or 'left'
            order: "whatsapp,facebook,telegram,email,sms,call" // Order of buttons
        };
        
        var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
  </script>
