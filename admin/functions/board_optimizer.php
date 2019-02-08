<?php
mb_internal_encoding("UTF-8");
include '../../config.php';

if (isset($_COOKIE["admin"])) {
	$input_admin_cookies = htmlspecialchars($_COOKIE["admin"], ENT_QUOTES);
	$input_admin_cookies = mysql_real_escape_string($input_admin_cookies);

	$input_admin_ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES);
	$input_admin_ip = mysql_real_escape_string($input_admin_ip);

	$total = mysql_result(mysql_query("SELECT COUNT(*) FROM admin WHERE admin_cookies = '$input_admin_cookies' AND admin_ip = '$input_admin_ip'"),0);

		if ($total == 0) {
			mysql_close($link);
			header('Location: http://'.$_SERVER['SERVER_NAME'].'/'.$web_folder.'admin/login.php');
			exit;
		}

} else {
	mysql_close($link);
	header('Location: http://'.$_SERVER['SERVER_NAME'].'/'.$web_folder.'admin/login.php');
	exit;
}

$result = mysql_query("SELECT * FROM board WHERE post_sec = '-1'", $link);
while ($row = mysql_fetch_assoc($result)) {
	if(!empty($row)) {
		$post_id = $row['post_id'];
		$post_img_dir = $row['img_dir'];
		$post_img = $row['post_img'];
		$img_ext = $row['img_ext'];

			if (!empty($post_img)) {

				$img_del = $root_dir.$post_img_dir.'/'.$post_img.'_small.'.$img_ext;
					if (file_exists($img_del)) {
						unlink($img_del);
					}

				$img_del = $root_dir.$post_img_dir.'/'.$post_img.'_big.'.$img_ext;
					if (file_exists($img_del)) {
						unlink($img_del);
					}
			}

		mysql_query("DELETE FROM post_config WHERE config_post_id = 'post_id'");

		$comment_result = mysql_query("SELECT * FROM board WHERE post_of = '$post_id'", $link);
			while ($row = mysql_fetch_assoc($comment_result)) {
				$comment_id = $row['post_id'];
				$comment_img_dir = $row['img_dir'];
				$post_img = $row['post_img'];
				$img_ext = $row['img_ext'];

					if (!empty($post_img)) {

						$img_del = $root_dir.$comment_img_dir.'/'.$post_img.'_small.'.$img_ext;
							if (file_exists($img_del)) {
								unlink($img_del);
							}
						$img_del = $root_dir.$comment_img_dir.'/'.$post_img.'_big.'.$img_ext;
							if (file_exists($img_del)) {
								unlink($img_del);
							}

					}
				mysql_query("DELETE FROM board WHERE post_id = '$comment_id'");
			}

	}

}

mysql_query("DELETE FROM board WHERE post_sec = '-1'");

mysql_query("TRUNCATE TABLE black_list");
mysql_query("TRUNCATE TABLE img");
mysql_query("TRUNCATE TABLE statistics");
mysql_query("OPTIMIZE TABLE admin, board, board_config, passwords, post_config");

	$handle = opendir($root_dir.'img/tmp_file/');
		while (($file = readdir($handle))!==false) {
			@unlink($root_dir.'img/tmp_file/'.$file);
		}
	closedir($handle);

mysql_close($link);
?>