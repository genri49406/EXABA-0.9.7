<?php
mb_internal_encoding("UTF-8");
ob_start("ob_gzhandler");
include 'config.php';

$page_title = 'Статистика ссылок';
$page_description = 'Статистика ссылок, по которым приходят на '.$domain;

$limit = 100;
$show_form = 0;

$page = (int)$_GET['page'];
if ($page < 0) {
$page = 0;
}

include 'functions/html.php';
$domain = preg_replace( "#^(\.)#", "", $domain);

// Фильтр нежелательных ссылок.
$my_filter = "
AND user_referer NOT LIKE 'http://$domain%'
AND user_referer NOT LIKE 'http://www.$domain%'
";

$num_rows = mysql_result(mysql_query("SELECT COUNT(*) FROM statistics WHERE user_referer <>'' $my_filter"),0);
$result = mysql_query("SELECT user_referer, user_time FROM statistics WHERE user_referer <>'' $my_filter order by user_time DESC  LIMIT $page, $limit");

while ($row = mysql_fetch_assoc($result)) {
$post_text = $row['user_referer'];
$user_time = $row['user_time'];
$user_time = date('d '.$month_array[date('n',$user_time)].', Y H:i',$user_time);

$google = explode('q=', $post_text);
$google = mb_split('&', $google[1]);

$yandex = explode('text=', $post_text);
$yandex = mb_split('&', $yandex[1]);

if (!empty($google[0])) {
$q = '<p class="quote">Запрос: '.htmlspecialchars(urldecode($google[0])).'</p>';
} elseif(!empty($yandex[0])) {
$q = '<p class="quote">Запрос: '.htmlspecialchars(urldecode($yandex[0])).'</p>';
} else {
$q="";
}

$post_text = preg_replace( '#(https?://|www\.)([-a-z0-9+._%:/?=\#\&amp;]+)#i', '<a href="http://$2">$1$2</a>', $post_text);


echo '
<div class="comment">
<p>'.$post_text.'</p>
'.$q.'
<span class="info">'.$user_time.'</span>
</div>
';
}

mysql_free_result($result);

if($num_rows > $limit) {
	pages($num_rows, $limit, $id, $order);
}
footer();
mysql_close($link);
?>