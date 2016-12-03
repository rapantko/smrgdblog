<?php

$id = segment(2);


try {
	$post = get_post($id);
} catch (PDOException $e) {
	$post = [];
}

if (!$post) {
	flash()->error("post doesn't exist");
	redirect("/");
}


$page_title = 'Delete / ' . $post -> title ;




include_once "_partials/header.php";

?>
	<section class="box">
		<form action="<?= BASE_URL ?>/_admin/delete-item.php" method="post" class="post">
			<header class="post-header">
				<h1 class="box-heading">
					Are you sure about this ?
				</h1>
			</header>

			<blockquotes>
				<h3>&ldquo;<?=$post -> title ?>&rdquo;</h3>
				<p class="teaser"><?= $post -> teaser ?></p>
			</blockquotes>



			<div class="form-group">
				<input name="post_id" value="<?= $post->id ?>" type="hidden">
				<button type="submit" class="btn btn-primary">Delete post</button>
				<span class="or">
					&nbsp;or&nbsp; <a href="<?= get_post_link( $post ) ?>"> &nbsp;cancel&nbsp; </a>
				</span>
			</div>
		</form>
	</section>


<?php include_once "_partials/footer.php" ?>