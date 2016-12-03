<?php

$id = segment(2);

if ($id === 'new') {
	include_once 'add.php';
	die();
}

try {
	$post = get_post($id);
} catch (PDOException $e) {
	$post = [];
}

if (!$post) {
	flash()->error("post doesn't exist");
	redirect("/");
}


$page_title = $post->title;

include_once "_partials/header.php";
?>


<section class="box">
	<article class="post full-post">
		<header class="post-header">
			<h1 class="box-heading">
				<a href="<?= $post->link ?> "><?= $post->title ?></a>
				<time datetime="<?= $post->date ?>">
					<small><?= $post->time ?></small>
				</time>
				<a href="<?= get_post_link($post, 'edit') ?>" class="btn btn-xs edit-link">Edit</a>
				<a href="<?= get_post_link($post, 'delete') ?>" class="btn btn-xs edit-link">Delete</a>

			</h1>
		</header>
		<div class="post-content">
			<?= $post->text ?>
		</div>
		<footer class="post-footer">
			<?php if ($post->tags) : ?>
				<p class="tags">
					<?php foreach ($post->links as $tag => $tag_link) : ?>
						<a href="<?= $tag_link ?>" class="btn btn-warning btn-xs">
							<small><?= $tag ?></small>
						</a>
					<?php endforeach ?>
				</p>
			<?php endif ?>
		</footer>
	</article>
</section>

<?php include_once "_partials/footer.php" ?>
