<?php 


$results = get_posts();



include_once "_partials/header.php" ?>

	<div class="page-header">
		<h1>VERY MUCH HOMEPAGE</h1>
	</div>
	<section class = "box post-list">
		<?php if (count($results)) : foreach ($results as $post) : ?>

			<article id="post-<?= $post->id ?>" class="post">
				<header class="post-header">
					<h2>
						<a href="<?= BASE_URL ?>/post/<?= $post->id ?>/<?= $post->slug ?>">
							<?= plain($post->title) ?>
						</a>
						<time datetime="<?= date('Y-m-d', strtotime($post->created_at)) ?>">
							<small> / <?= plain(date('j M Y, G:i',strtotime($post->created_at))) ?></small>
						</time>
					</h2>
				</header>
				<div class="post-content">
					<p>
						<?= word_limiter(plain($post->text), 40) ?>
					</p>
				</div>
				<div class="post-footer">
					<a class="read-more" href="<?= BASE_URL ?>/post/<?= $post->id ?>/<?= $post->slug ?>">
						Read more..
					</a>
				</div>
			</article>
		<?php  endforeach; else: ?>

		<p>We got nothing</p>
		<?php endif ?>
	</section>

	

<?php include_once "_partials/footer.php" ?>


