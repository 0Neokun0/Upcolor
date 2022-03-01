<?php
session_start();
require_once('../config.php');
require_once('../function.php');

require_once('../header.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Upcolor</title>

		<!-- meta -->
		<meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <!-- css -->

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"><!-- navigation と被っている。--><

	    <link rel="stylesheet" href="css/animate.css">
	    <link rel="stylesheet" href="css/custom.css">



	</head>

	<body>
        <?php
        require_once('../navbar.php');
        ?>
		<div id="wrapper">
			<div id="overlay-1">
				<section id="starting">
					<div class="text-center starting-text">
						<h1 class="rene">UpColor</h1>
						<h2>ご利用いただき誠にありがとうございます。</h2>
						<h2 class="text-center">お手数ですがお先にアカウント設定をお願いします。</h2>

						<section id="aboutus">
							<div  class="mx-auto wow animated zoomInDown" id="heading-text" style="color :white;">
								<h3> Click</h3>
								<p> ⇩</p>
								
								
							</div>
							<button type="button" class="btn btn-primary btn-lg" onclick="location.href='../profile/profile_edit.php'">アカウント設定</button>
						</section>
					</div>
				</section>


			</div><!-- overlay-1 -->
		</div>	<!-- wrapper -->

		<!-- About Us -->


	</body>
</html>
