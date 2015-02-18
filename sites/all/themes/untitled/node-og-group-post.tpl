<?php 
$vars = get_defined_vars();
$view = untitled_art_get_drupal_view();
$message = $view->get_incorrect_version_message();
if (!empty($message)) {
print $message;
die();
}
$is_blog_page = isset($node->body['und'][0]['summary']) && (strpos($node->body['und'][0]['summary'], 'ART_BLOG_PAGE') !== FALSE);
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
	<?php if (!$is_blog_page): ?>
<article class="art-post art-article">
                                <?php if (!empty($title)): ?>
<?php print render($title_prefix); ?>
<?php echo untitled_art_node_title_output($title, $node_url, $page); ?>
<?php print render($title_suffix); ?>
<?php endif; ?>

                                                <?php if ($submitted): ?>
<div class="art-postheadericons art-metadata-icons"><?php echo untitled_art_submitted_worker($date, $name); ?>
</div><?php endif; ?>

                <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-article">
  <?php endif; ?>
  <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    $terms = untitled_art_terms_D7($content);
    hide($content[$terms['#field_name']]);
    print $user_picture;
    print render($content);
  ?>
  <?php if (!$is_blog_page): ?>
</div>
</div>


<?php print render($content['comments']); ?></article>	<?php endif; ?>
</div>
