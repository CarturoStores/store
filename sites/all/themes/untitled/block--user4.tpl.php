<div class="<?php if (isset($classes)) print $classes; ?>" id="<?php print $block_html_id; ?>"<?php print $attributes; ?>>
<div class="art-box art-post">
<div class="art-box-body art-post-body">
<article class="art-post-inner art-article">
<?php print render($title_prefix); ?>
<?php if ($block->subject): ?>
<h2 class="art-postheader"><?php print $block->subject ?></h2>
<?php endif;?>
<?php print render($title_suffix); ?>
<div class="art-postcontent">
<div class="art-article content">
<?php print $content; ?>
</div>
</div>
<div class="cleared"></div>
</article>
<div class="cleared"></div>
</div>
</div>
</div>
