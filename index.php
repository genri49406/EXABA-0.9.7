<?php
mb_internal_encoding("UTF-8");
ob_start("ob_gzhandler");
include 'config.php';

$page_title = 'Главная';
$page_description = 'Свободный Два.ч';

include 'functions/html.php';

echo'
<div class="post">
	<img src="styles/default/logo.png" style="width: 200px; height: 200px;" class="text_image" alt="">
	<h2>Свободный Два.ч. Твой Два.ч</h2>
	<p>
		Устали от анальной модерации на Сосаче? Хотите борду без правил и запретов? 
		Добро пожаловать на Ваш Два.ч<br />
		Здесь нету никаких правил (влетит вам, разве что, за вайп). Это территория свободы!
		Вы можете постить и делать что хотите. Да, даже процессор. <br />
		<a href="admin/">Marosii</a>
	</p>
	<div class="info">Слава свободным чанам.</div>
</div>
';

footer();
?>