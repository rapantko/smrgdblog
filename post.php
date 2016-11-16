<?php

try {
	$post = get_post();
} catch (PDOException $e) {
	$post = [];
}

if (!$post) {
	flash()->error("post doesn't exist");
	redirect("/");
}


$page_title = $post -> title ;

include_once "_partials/header.php";
?>


<section class="box">
	<article class="post full-post">
		<header class="post-header">
			<h1 class="box-heading">
				<a href="<?=$post -> link?> "><?= $post -> title ?></a>
				<time datetime="<?= $post -> date ?>">
					<small><?= $post -> time ?></small>
				</time>
			</h1>
		</header>
		<div class="post-content">
			<?= $post -> text ?>
		</div>
	</article>
</section>

<?php include_once "_partials/footer.php" ?>
