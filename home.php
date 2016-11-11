<?php 


$results = get_posts();



include_once "_partials/header.php" ?>

	<div class="page-header">
		<h1>VERY MUCH HOMEPAGE</h1>
	</div>
	<section class = "box post-list">
		<?php if (count($results)) : foreach ($results as $post) : $post= format_post($post)  ?>

			<article id="post-<?= $post->id ?>" class="post">
				<header class="post-header">
					<h2>
						<a href="<?= $post -> link ?>/<?= $post->slug ?>">
							<?= plain($post->title) ?>
						</a>
						<time datetime="<?= $post -> date ?>">
							<small> / <?= $post -> time ?></small>
						</time>
					</h2>
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


