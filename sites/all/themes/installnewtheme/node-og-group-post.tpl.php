<div class="art-post">
    <div class="art-post-body">
<div class="art-post-inner art-article">
<h2 class="art-postheader"<?php print $title_attributes; ?>><?php print render($title_prefix); ?>
<?php echo art_node_title_output($title, $node_url, $page); ?>
<?php print render($title_suffix); ?>
</h2>
<?php if ($display_submitted): ?>
<div class="art-postheadericons art-metadata-icons">
<?php echo art_submitted_worker($date, $name); ?>

</div>
<?php endif; ?>
<div class="art-postcontent">
<?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      $terms = get_terms_D7($content);
      hide($content[$terms['#field_name']]);
      print render($content);
    ?>

</div>
<div class="cleared"></div>
<?php print $user_picture; ?>
<?php if (isset($content['links']) || isset($content['comments'])):
$output = art_links_woker_D7($content);
if (!empty($output)):	?>
<div class="art-postfootericons art-metadata-icons">
<?php echo $output; ?>

</div>
<?php endif; endif; ?>

</div>

		<div class="cleared"></div>
    </div>
</div>
