<?php

try {
	$results = get_posts();
} catch (PDOException $e) {
	$results = [];
}


include_once "_partials/header.php" ?>

	<div class="page-header">
		<h1>SMARAGDOVE SLZY</h1>
	</div>
	<section class = "box post-list">
		<?php if (count($results)) : foreach ($results as $post) :  ?>

			<article id="post-<?= $post->id ?>" class="post">
				<header class="post-header">
					<h2>
						<a href="<?= $post -> link ?>">
							<?= plain($post->title) ?>
						</a>
						<time datetime="<?= $post -> date ?>">
							<small> / <?= $post -> time ?></small>
						</time>
					</h2>
					<?php if ($post -> tags) : ?>
					<p class = "tags">
						<?php foreach ($post -> links as $tag => $tag_link) : ?>
							<a href="<?= $tag_link ?>" class="btn btn-warning btn-xs"><small><?=$tag?></small></a>
						<?php endforeach ?>
					</p>
				<?php endif ?>
				</header>
				<div class="post-content">
					<p>
						<?= $post -> teaser ?>
					</p>
				</div>
				<div class="post-footer">
					<a class="read-more" href="<?= $post -> link?>">
						Read more..
					</a>
				</div>
			</article>
		<?php  endforeach; else: ?>

		<p>We got nothing</p>
		<?php endif ?>
	</section>



	

<?php include_once "_partials/footer.php" ?>


