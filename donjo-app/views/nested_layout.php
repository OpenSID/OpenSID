
<html>
<head>
<title>Layout Example</title>

	<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>assets/css/layout-default-latest.css">

	<style type="text/css">
	.container	{ padding: 0; overflow: hidden; }
	.blue		{ background: #EEF; }
	.green		{ background: #EFE; }
	</style>

		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-1.12.4.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-layout.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui.min.js"></script>

<!-- 	<script type="text/javascript" src="/lib/js/jquery-latest.js"></script>
	<script type="text/javascript" src="/lib/js/jquery-ui-latest.js"></script>
	<script type="text/javascript" src="/lib/js/jquery.layout-latest.js"></script>
 -->	<script type="text/javascript">

	$(document).ready(function () {
		$('body').layout({
			center__childOptions: {}
		});
	});

	</script>

</head>
<body>

<div class="ui-layout-north">Outer North</div>

<div class="ui-layout-center">Outer Center
	<div class="ui-layout-north">Center North</div>
	<div class="ui-layout-center">Center Center
		<p><a href="http://layout.jquery-dev.com/demos.html">Go to the Demos page</a></p>
		<p>* Pane-resizing is disabled because ui.draggable.js is not linked</p>
		<p>* Pane-animation is disabled because ui.effects.js is not linked</p>
	</div>
	<div class="ui-layout-south">Center South</div>
</div>

<div class="ui-layout-south">Outer South</div>

</body>
</html>