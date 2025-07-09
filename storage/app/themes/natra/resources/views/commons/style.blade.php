@php $warna_dasar = theme_config('warna_dasar', '#e64946'); @endphp

<style type="text/css">
    #jam {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        text-align: center;
        margin: 5px 0;
        background: {{ $warna_dasar }};
        border: 3px double #ffffff;
        padding: 3px;
        width: auto;
        box-sizing: border-box;
        height: auto;
    }

    /* color white */
    .white {
        color: #fff;
    }

    .navbar-default {
        background-color: {{ $warna_dasar }};
        border-color: {{ $warna_dasar }};
    }

    .catg_titile {
        background-color: {{ $warna_dasar }};
    }

    .catgimg2_container2 {
        width: 100%;
    }

    .bold_line span {
        background-color: {{ $warna_dasar }};
    }

    .pagination_area ul li a:hover,
    .scrollToTop {
        background-color: {{ $warna_dasar }};
    }

    .progress-bar-danger {
        background-color: {{ $warna_dasar }};
    }

    .single_bottom_rightbar>h2 {
        border-bottom: 3px solid {{ $warna_dasar }};
    }

    .pagination_area ul li a,
    .scrollToTop:focus,
    .scrollToTop:hover {
        color: {{ $warna_dasar }};
        border-color: {{ $warna_dasar }};
    }

    #footer {
        border-top: 10px solid {{ $warna_dasar }};
    }

    .scrollToTop,
    .pagination_area ul li a:hover {
        background-color: {{ $warna_dasar }};
        color: #fff;
    }

    .scrollToTop:hover,
    .scrollToTop:focus,
    .pagination_area ul li a {
        background-color: #fff;
        color: {{ $warna_dasar }};
        border-color: {{ $warna_dasar }};
    }

    .top_nav li a:hover {
        color: {{ $warna_dasar }};
    }

    .search_form input[type="submit"]:hover {
        background-color: {{ $warna_dasar }};
    }

    .navbar-default {
        background-color: {{ $warna_dasar }};
        border-color: {{ $warna_dasar }};
    }

    .custom_nav li a:hover {
        border-color: #ffae00;
    }

    .navbar-default .navbar-nav>li>a:hover,
    .navbar-default .navbar-nav>li>a:focus {
        background-color: #fff;
        color: {{ $warna_dasar }};
        border-color: #ffae00;
    }

    .navbar-default .navbar-nav>.open>a,
    .navbar-default .navbar-nav>.open>a:hover,
    .navbar-default .navbar-nav>.open>a:focus {
        background-color: #fff;
        color: {{ $warna_dasar }};
        border-color: #ffae00;
    }

    .navbar-nav>li>.dropdown-menu {
        margin-top: 1px;
        background-color: {{ $warna_dasar }};
    }

    .dropdown-menu>li>a:hover,
    .dropdown-menu>li>a:focus {
        background-color: #fff;
        color: {{ $warna_dasar }};
        border-color: #ffae00;
        padding-left: 20px;
    }

    .navbar-default .navbar-nav .open .dropdown-menu>li>a {
        color: #fff;
    }

    .navbar-default .navbar-toggle:hover,
    .navbar-default .navbar-toggle:focus {
        background-color: #fcc259;
    }

    .slick-prev,
    .slick-next {
        background-color: {{ $warna_dasar }};
    }

    .slick-prev:hover,
    .slick-next:hover {
        opacity: 0.80;
    }

    .bold_line span {
        background-color: {{ $warna_dasar }};
    }

    .catg1_nav li .post_titile a:hover {
        color: {{ $warna_dasar }};
    }

    .content_middle_middle:after {
        background-color: {{ $warna_dasar }};
    }

    .content_middle_middle:before {
        background-color: {{ $warna_dasar }};
    }

    .single_featured_slide>h2 a:hover {
        color: {{ $warna_dasar }};
    }

    .catg_titile a:hover {
        color: {{ $warna_dasar }};
    }

    span.meta_date:hover,
    span.meta_comment:hover,
    span.meta_more:hover,
    span.meta_comment a:hover,
    span.meta_more a:hover {
        color: {{ $warna_dasar }};
    }

    .media-heading a:hover {
        color: {{ $warna_dasar }};
    }

    .single_bottom_rightbar>h2 {
        border-bottom: 3px solid {{ $warna_dasar }};
    }

    .nav-tabs {
        border-bottom: 1px solid {{ $warna_dasar }};
    }

    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:focus {
        color: {{ $warna_dasar }};
    }

    .nav-tabs>li.active>a:hover {
        color: {{ $warna_dasar }} !important;
    }

    .nav-tabs>li>a:hover {
        background-color: {{ $warna_dasar }};
        color: #fff !important;
    }

    .single_bottom_rightbar ul li>a:hover {
        color: {{ $warna_dasar }};
    }

    .labels_nav li a:hover {
        background-color: {{ $warna_dasar }};
    }

    .breadcrumb {
        background-color: {{ $warna_dasar }};
        border: 2px solid {{ $warna_dasar }};
    }

    .single_page_area>h2 {
        border-left: 5px solid {{ $warna_dasar }};
    }

    .post_commentbox a:hover,
    .post_commentbox span:hover {
        color: {{ $warna_dasar }};
    }

    .single_page_content blockquote {
        border-color: #eee {{ $warna_dasar }};
        border-left: 5px solid {{ $warna_dasar }};
    }

    .single_page_content ul li:before {
        background: none repeat scroll 0 0 {{ $warna_dasar }};
    }

    .post_pagination {
        border-bottom: 2px solid {{ $warna_dasar }};
        border-top: 2px solid {{ $warna_dasar }};
    }

    .prev {
        border-right: 2px solid {{ $warna_dasar }};
    }

    .angle_left {
        background-color: {{ $warna_dasar }};
    }

    .angle_right {
        background-color: {{ $warna_dasar }};
    }

    .error_page_content h1:after,
    .error_page_content h1:before {
        border: 2px solid {{ $warna_dasar }};
    }

    .error_page_content p {
        border-bottom: 2px solid {{ $warna_dasar }};
        border-top: 2px solid {{ $warna_dasar }};
    }

    .error_page_content p:after {
        border-top: 1px solid {{ $warna_dasar }};
    }

    .error_page_content p:before {
        border-top: 1px solid {{ $warna_dasar }};
    }

    .error_page_content p>a:hover {
        color: {{ $warna_dasar }};
    }

    .our_office {
        border-top: 2px solid #e649
    }

    .contact_us {
        border-top: 2px solid {{ $warna_dasar }};
    }

    .contact_form input[type="submit"]:hover {
        background-color: {{ $warna_dasar }};
        color: #fff;
        border-color: {{ $warna_dasar }};
    }

    .our_office:before {
        border-bottom: 1px solid {{ $warna_dasar }};
    }

    .contact_us:before {
        border-bottom: 1px solid {{ $warna_dasar }};
    }

    .single_footer_top>h2 {
        color: #f6f6f6;
    }

    .similar_post h2 i {
        color: {{ $warna_dasar }};
    }
</style>
