<?php


$page_title = 'Add new' ;




include_once "_partials/header.php";

?>
	<section class="box">
		<form action="<?= BASE_URL ?>/_admin/add-item.php" method="post" class="post">
			<header class="post-header">
				<h1 class="box-heading">
					Add new post
				</h1>
			</header>

			<div class="form-group">
				<input type="text" name="title" class="form-control" value="" placeholder="title your shit">
			</div>

			<div class="form-group">
				<textarea class="form-control" name="text" rows="16" placeholder="write your shit"></textarea>
			</div>

			<div class="form-group form-inline">
				<?php foreach (get_all_tags() as $tag) : ?>
					<label class="checkbox" >
						<input type="checkbox" name="tags[]" value="<?= $tag -> id ?>"
							<?= isset($tag ->checked) && $tag -> checked ? 'checked' : '' ?>>
						<?= plain($tag->tag) ?>
					</label>
				<?php endforeach;?>
			</div>




			<div class="form-group">
				<button type="submit" class="btn btn-primary">Add post</button>
				<span class="or">
					&nbsp;or&nbsp; <a href="<?= BASE_URL ?>"> &nbsp;cancel&nbsp; </a>
				</span>
			</div>
		</form>
	</section>


<?php include_once "_partials/footer.php" ?>