<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= isset($page_title) ? "$page_title / " : ""?> this is a blog</title>

	<link rel="stylesheet" href="<?= asset('/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= asset('/css/main.css') ?>">

	<script>
		var baseURL = '<?= BASE_URL ?>';
	</script>
</head>
<body class="<? echo segment(1) ? plain(segment(1)) : 'home'?>">

<header class="container">
	<?php

		flash()->display()

	?>
</header>

<main>
	<div class="container">
