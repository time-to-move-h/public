<!DOCTYPE html>
<html>
<head>
	<meta name="robots" content="noindex,nofollow" />
	<link rel="shortcut icon" href="images/favicon_new.ico"/>
	<title>An error occurred</title>
	<?php 
	echo $this->Html->meta('icon');
	?>
	<style>
	*{
	padding:5;
	margin:5;	
	font-family:Tahoma, Verdana;
	}
	body{
	padding:5;
	margin:5;	
	}
	h2 {
		padding:2px;
		margin:2px;
	}
	h3 {
		font-weight:normal;
		padding:2px;
		margin:2px;
	}
	.link:hover {
		text-decoration:underline;
	}
	</style>
</head>
<body>
	<div id="container">
		<div id="content">
			<table width="100%" align="center"><tr><td align="center">
			<table cellpadding="8" cellspacing="8" style="border:1px solid #999999;color:#000000" align="center" width="520px">
				<tr>
					<td align="center" style="border-bottom:1px solid #999999">
						<h2 style="color:#245271"><?php echo __("404 - PAGE NOT FOUND"); ?></h2>
					</td>
				</tr>
				<tr>
					<td align="center" style="padding-top:10px">
						<h3><?php echo __("We are sorry, but the page you requested was not found"); ?></h3>
					</td>
				</tr>
			</table>
			</td></tr></table>
		</div>
	</div>
</body>
</html>
