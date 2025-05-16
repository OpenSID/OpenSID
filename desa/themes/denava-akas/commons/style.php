<?php $bgtop = theme_config('bgtop', '#ba6acb'); ?>
<?php $gradient_left = theme_config('gradient_left', '#c3336f'); ?>
<?php $gradient_right = theme_config('gradient_right', '#00427f'); ?>
<?php $textlink = theme_config('textlink', '#450017'); ?>
<?php $texthover = theme_config('texthover', '#980737'); ?>
<?php $withscroll = theme_config('withscroll', '#d4bfd3'); ?>
<?php $bgcenter = theme_config('bgcenter', '#d689c5'); ?>

<style type="text/css">
    .sunrise{background: <?= $gradient_left ?>;}
    a{text-decoration:none; color:<?= $textlink ?> !important;}
    a:hover{color:<?= $texthover ?> !important; text-decoration:none !important;}
    a:focus{outline:none; text-decoration:none !important ;color:<?= $texthover ?> !important;}
    .color-1{color:<?= $gradient_left ?>;}
    .color-light{color:#ff9ac4;}
    .darkmode .color-1{color:#d07ada;}
    .bgwhite{background:#fff;}
    .bg-toska{background-color:#42b095;}
    .bg-biru{background-color:#42a5c1;}
    .bg-ungu{background-color:#a58cc5;}
    .bg-hijau{background-color:#38c255;}
    .bg-color1{background-color:<?= $bgcenter ?>;}
    .bg-color2{background-color:<?= $bgtop ?>;}
    .bg-color3{background-color:<?= $gradient_left ?>;}
    .bg-color4{background-color:<?= $gradient_left ?>;}
    .bg-color5{background-color:<?= $gradient_left ?>;}
    .mainmenu:before{background:<?= $gradient_left ?>;}
    .mainmenu:after{background:<?= $gradient_right ?>;}
    .border-color{border-color:<?= $bgtop ?> !important;}
    .mainmenu .carouselcustom:before{background: transparent;
    background: -moz-linear-gradient(90deg, transparent 0%, <?= $gradient_right ?> 90%);
    background: -webkit-linear-gradient(90deg, transparent 0%, <?= $gradient_right ?> 90%);
    background: linear-gradient(90deg, transparent 0%, <?= $gradient_right ?> 90%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="transparent",endColorstr="<?= $gradient_right ?>",GradientType=1);}
    .bg-gradient1{background: transparent;
    background: -moz-linear-gradient(90deg, transparent 45%, <?= $bgcenter ?> 95%);
    background: -webkit-linear-gradient(90deg, transparent 45%, <?= $bgcenter ?> 95%);
    background: linear-gradient(90deg, transparent 45%, <?= $bgcenter ?> 95%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="transparent",endColorstr="<?= $bgcenter ?>",GradientType=1);}
    .bg-gradient2{background:rgba(25,151,0,0.5);}
    .mainheader-cover-color{opacity:0.6;background: <?= $gradient_left ?>;
    background: -moz-linear-gradient(45deg, <?= $gradient_left ?> 0%, #8a5ca5 90%);
    background: -webkit-linear-gradient(45deg, <?= $gradient_left ?> 0%, #8a5ca5 90%);
    background: linear-gradient(45deg, <?= $gradient_left ?> 0%, #8a5ca5 90%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="<?= $gradient_left ?>",endColorstr="#8a5ca5",GradientType=1);}
    .bg-mainmenu{background: <?= $gradient_left ?>;background: -moz-linear-gradient(90deg, <?= $gradient_left ?> 5%, rgba(195,51,111,0.5) 30%, rgba(0,66,127,0.5) 70%, <?= $gradient_right ?> 95%);background: -webkit-linear-gradient(90deg, <?= $gradient_left ?> 5%, rgba(195,51,111,0.5) 30%, rgba(0,66,127,0.5) 70%, <?= $gradient_right ?> 95%);background: linear-gradient(90deg, <?= $gradient_left ?> 5%, rgba(195,51,111,0.5) 30%, rgba(0,66,127,0.5) 70%, <?= $gradient_right ?> 95%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="<?= $gradient_left ?>",endColorstr="<?= $gradient_right ?>",GradientType=1);}
    .bg-gradient-hor{background: <?= $gradient_left ?>;background: -moz-linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);background: -webkit-linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);background: linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="<?= $gradient_left ?>",endColorstr="<?= $gradient_right ?>",GradientType=1);}
    .bg-gradient-vert{background: <?= $gradient_left ?>;background: -moz-linear-gradient(0deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);background: -webkit-linear-gradient(0deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);background: linear-gradient(0deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="<?= $gradient_left ?>",endColorstr="<?= $gradient_right ?>",GradientType=1);}
    .bg-color-radial{background: #fff;background: -moz-radial-gradient(circle, #fff 0%, <?= $bgcenter ?> 100%);background: -webkit-radial-gradient(circle, #fff 0%, <?= $bgcenter ?> 100%);background: radial-gradient(circle, #fff 0%, <?= $bgcenter ?> 100%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#fff",endColorstr="<?= $bgcenter ?>",GradientType=1);}
    .head-module-center h1, .head-module-center h2{border-bottom:<?= $gradient_right ?> 5px solid;}
    .progress-moved .progress-bar2 {background-color: <?= $bgcenter ?>;animation: progress 5s infinite;}
    @keyframes progress {
    0% {width: 0%;background: transparent;}
    100% {width: 100%;background: <?= $bgcenter ?>;}
    }
    .persen-progress:after{border-top:<?= $bgcenter ?> 14px solid !important;border-right:10px solid transparent;}
    .layanan-info-title:after{border-top:<?= $gradient_left ?> 60px solid;border-right:40px solid transparent;}
    .progress {background-color: <?= $bgtop ?> !important;}
    .to-home{background:<?= $bgtop ?>;}
    .to-home:after{background: <?= $gradient_left ?>;background: -moz-linear-gradient(90deg, <?= $gradient_left ?> 10%, transparent 100%);background: -webkit-linear-gradient(90deg, <?= $gradient_left ?> 10%, transparent 100%);background: linear-gradient(90deg, <?= $gradient_left ?> 10%, transparent 100%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="transparent",endColorstr="transparent",GradientType=1);}
    .image-zoom:after{border-left:<?= $bgtop ?> 90px solid;}
    .articlehome .image-zoom:after{border-right:<?= $bgtop ?> 70px solid;border-left:none;}
    .head-statis-title h1{color:#c4d36f;}
    .pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus {color: #fff !important;background-color: <?= $gradient_left ?>;border-color: <?= $gradient_left ?>;}
    .pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {background-color: <?= $gradient_right ?>;border-color: <?= $gradient_right ?>;}
    .runningpage-info{background:<?= $gradient_right ?>;}
    .runningpage-info:after{border-left:<?= $gradient_right ?> 15px solid;}
    .petastyle .bg-navy {background-color: #154317 !important;color: #fff !important;}
    .statistik-title svg{fill:<?= $bgcenter ?>;}
    .stat-active, a.stat-active{color:<?= $gradient_left ?>;}
    .stat-active:after{border-left:<?= $bgtop ?> 12px solid;}
    .widget-height .highcharts-color-0 {fill: #62a0b2;stroke: #62a0b2;}
    .widget-height .highcharts-color-1 {fill: #89c363;stroke: #89c363;}
    .widget-height .highcharts-color-2 {fill: #a585a2;stroke: #a585a2;}
    .widget-height .highcharts-color-3 {fill: #EB9486;stroke: #EB9486;}
    .widget-height .highcharts-color-4 {fill: #ff0000;stroke: #ff0000;}
    .head-jadwalshalat a::after {border: solid <?= $gradient_left ?>;border-width: 0 2px 2px 0;}

    .loading span{background: <?= $gradient_left ?>;}
    .loading span:nth-of-type(2) {background: #991161;animation-delay: 0.2s;}
    .loading span:nth-of-type(3) {background: #bd337e;animation-delay: 0.4s;}
    .loading span:nth-of-type(4) {background: #ca4489;animation-delay: 0.6s;}
    .loading span:nth-of-type(5) {background: #23a2bd;animation-delay: 0.8s;}
    .loading span:nth-of-type(6) {background: #117899;animation-delay: 1.0s;}
    .loading span:nth-of-type(7) {background: <?= $gradient_right ?>;animation-delay: 1.2s;}
    #ScrollToTop{background:<?= $gradient_left ?>;}
    .mosque1 svg, .mosque2 svg{fill:#fff;stroke:<?= $bgtop ?>;}
    .withscroll, .widgetscroll, .withscrollbig {overflow-y: scroll;scrollbar-color:transparent transparent;scrollbar-width: thin;}
    .withscroll, .widgetscroll, .withscrollbig, .modal-dialog {scrollbar-color: <?= $withscroll ?> transparent;}
    .withscroll::-webkit-scrollbar-thumb, .widgetscroll::-webkit-scrollbar-thumb{background:<?= $withscroll ?>;}
    .widget-height .withscroll::-webkit-scrollbar-thumb, .box-anggaran .withscroll::-webkit-scrollbar-thumb, .widget-height .widgetscroll::-webkit-scrollbar-thumb, .box-anggaran .widgetscroll::-webkit-scrollbar-thumb{background:transparent;}
    .widget-height:hover .withscroll::-webkit-scrollbar-thumb, .box-anggaran:hover .withscroll::-webkit-scrollbar-thumb, .widget-height:hover .widgetscroll::-webkit-scrollbar-thumb, .box-anggaran:hover .widgetscroll::-webkit-scrollbar-thumb{background:<?= $withscroll ?>;}
    .withscrollbig::-webkit-scrollbar-thumb{border-radius:10px;background-image: linear-gradient(180deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 99%);}
    .articlehome .modalscroll::-webkit-scrollbar-thumb{border-radius:10px;background:<?= $withscroll ?>;}
    .modalscroll, .articlehome .modalscroll  {scrollbar-color: <?= $withscroll ?> transparent;}
    .nav-wrapper ul li a:hover, .nav-wrapper ul li a:focus{color:#feb5ff !important;}
    .articlehome .modal-backdrop.in {filter: alpha(opacity=0);background: <?= $gradient_left ?>;background: -moz-linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);background: -webkit-linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);background: linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="<?= $gradient_left ?>",endColorstr="<?= $gradient_right ?>",GradientType=1);opacity: .8;}
    .article-link svg{fill:<?= $gradient_left ?>;}

    @media (max-width: 992px) {
    .runningpage{background: <?= $gradient_left ?>;background: -moz-linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);background: -webkit-linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);background: linear-gradient(90deg, <?= $gradient_left ?> 0%, <?= $gradient_right ?> 80%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="<?= $gradient_left ?>",endColorstr="<?= $gradient_right ?>",GradientType=1);}
    .stat-active:after{border-bottom:<?= $bgtop ?> 10px solid;border-left:transparent 10px solid;border-right:transparent 10px solid;}
    .aparatur-top.bg-color5{background-color:<?= $bgtop ?>;}
    }
</style>