<div class="<?php if (isset($classes)) print $classes; ?>" id="<?php print $block_html_id; ?>"<?php print $attributes; ?>>
<?php print render($title_prefix); ?>
<?php if (!empty($block->subject)): ?>
<h2><?php print $block->subject ?></h2>
<?php endif;?>
<?php print render($title_suffix); ?>
<div class="content">
<?php print $content ?>
</div>
</div>
