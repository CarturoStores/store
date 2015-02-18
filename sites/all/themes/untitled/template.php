<?php
// $Id

require_once("common_methods.php");


global $language;
if (isset($language)) {
	$language->direction = LANGUAGE_LTR;
}

switch (untitled_art_get_drupal_major_version()) {
	case 5:
	  require_once("drupal5_theme_methods.php");
	  break;
	case 6:
	  require_once("drupal6_theme_methods.php");
	  break;
	case 7:
	  require_once("drupal7_theme_methods.php");
	  break;
    default:
		  break;
}

/* Common methods */

function untitled_art_get_drupal_major_version() {	
	$tok = strtok(VERSION, '.');
	//return first part of version number
	return (int)$tok[0];
}

function untitled_art_get_page_language($language) {
  if (untitled_art_get_drupal_major_version() >= 6) return $language->language;
  return $language;
}

function untitled_art_get_page_direction($language) {
  if (isset($language) && isset($language->dir)) { 
	  return 'dir="'.$language->dir.'"';
  }
  return 'dir="'.ltr.'"';
}

function untitled_art_get_full_path_to_theme() {
  return base_path().path_to_theme();
}

function untitled_art_get_drupal_view() {
	if (untitled_art_get_drupal_major_version() == 7)
		return new untitled_art_view_drupal7();
	return new untitled_art_view_drupal56();
}

function untitled_art_export_version() {
	return 7;
}

if (!function_exists('render'))	{
	function render($var) {
		return $var;
	}
}

class untitled_art_view_drupal56 {
	
	function print_head($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<!DOCTYPE html>
<html lang="<?php echo untitled_art_get_page_language($language); ?>" <?php echo untitled_art_get_page_direction($language); ?> >
<head>
  <?php echo $head; ?>
  <title><?php if (isset($head_title )) { echo $head_title; } ?></title>
  <?php echo $styles ?>
  <?php echo $scripts; ?>
  <!-- Created by Artisteer v4.3.0.60747 -->

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width" />

<!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo base_path() . $directory; ?>/style.ie7.css" media="screen" /><![endif]-->


  
</head>

<body <?php if (!empty($body_classes)) { echo 'class="'.$body_classes.'"'; } ?>>
<?php
	}


	function print_closure($vars) {
	echo $vars['closure'];
?>
</body>
</html>
<?php
	}

	function print_maintenance_head($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<!DOCTYPE html >
<html lang="<?php echo untitled_art_get_page_language($language); ?>" <?php echo untitled_art_get_page_direction($language); ?> >
<head>
  <?php echo $head; ?>
  <title><?php if (isset($head_title )) { echo $head_title; } ?></title>  
  <?php echo $styles ?>
  <?php echo $scripts ?>
  <!-- Created by Artisteer v4.3.0.60747 -->

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width" />

<!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo base_path() . $directory; ?>/style.ie7.css" media="screen" /><![endif]-->



</head>

<body <?php if (!empty($body_classes)) { echo 'class="'.$body_classes.'"'; } ?>>
<?php
	}
	
	function print_comment($vars) {
		foreach (array_keys($vars) as $name)
		$$name = & $vars[$name];
?>
<div id="comments" class="art-comments">
	<div class="art-comment art-postcontent <?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status; print ' '. $zebra; ?>">
		<h3 class="art-postheader">
			<?php print $title ?>
		</h3>
		<div class="art-comment-avatar">
			<?php if (isset($picture) && $picture != '') print $picture;
			else print '<img src="'.untitled_art_get_full_path_to_theme().'/images/no-avatar.jpg" alt="No Avatar">'; ?>
		</div>

		<div class="art-comment-inner">
			<div class="art-comment-header">
				<?php print $submitted; ?>
				<?php if ($comment->new) : ?>
					<span class="new"><?php print drupal_ucfirst($new) ?></span>
				<?php endif; ?>
			</div>
			<div class="art-comment-content content">
				<?php print $content ?>
				<?php if ($signature): ?>
					<div class="clear-block">
						<div>-</div>
						<?php print $signature ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ($links): ?>
				<div class="art-comment-footer">
					<?php print str_replace('<a href="', '<a class="art-button" href="', $links); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
	}

	function print_comment_wrapper($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<div id="comments">
  <?php print $content; ?>
</div>
	<?php
	}

	function get_incorrect_version_message() {
		if (untitled_art_export_version() > 6) {
			return t('This version is not compatible with Drupal 5.x or 6.x and should be replaced.');
		}
		return '';
	}
}


class untitled_art_view_drupal7 {

	function print_head($vars) {
		print render($vars['page']['header']);
	}
	
	function print_closure($vars) {
		return;
	}

	function print_maintenance_head($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<!DOCTYPE html>
<html lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <!-- Created by Artisteer v4.3.0.60747 -->

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width" />

<!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo base_path() . $directory; ?>/style.ie7.css" media="screen" /><![endif]-->



</head>
<body class="<?php print $classes; ?>">
<?php
	}
	
	function print_comment($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<div class="art-comment art-postcontent <?php print $classes; ?>" <?php print $attributes; ?>>
  <?php print render($title_prefix); ?>
  <h3 class="art-postheader">
    <?php print $title ?>
  </h3>
  <?php print render($title_suffix); ?>

  <div class="art-comment-avatar">
  <?php if (isset($picture) && $picture != '') print $picture;
		else print '<img src="'.untitled_art_get_full_path_to_theme().'/images/no-avatar.jpg" alt="No Avatar">'; ?>
  </div>
  <div class="art-comment-inner">
    <div class="art-comment-header">
      <?php
			print t('!username on !datetime',
				array('!username' => $author, '!datetime' => $created));
			?>
      <?php if ($new): ?>
      <span class="new">
        <?php print $new ?>
      </span>
      <?php endif; ?>
    </div>
    <div class="art-comment-content content" <?php print $content_attributes; ?>>
		<?php
				// We hide the comments and links now so that we can render them later.
				hide($content['links']);
				print render($content);
		?>
      <?php if ($signature): ?>
      <div class="user-signature clearfix">
        <?php print $signature ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="art-comment-footer">
      <?php $links = render($content['links']);
				print str_replace('<a href="', '<a class="art-button" href="', $links);
			?>
    </div>
  </div>
</div>
<?php
	}

	function print_comment_wrapper($vars)	{
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<?php if ($content['comments']): ?>
<div id="comments" class="art-comments <?php print $classes; ?>" <?php print $attributes; ?>>
  <?php if ($content['comments'] && $node->type != 'forum'): ?>
  <?php print render($title_prefix); ?>
  <h2 class="art-postheader">
    <?php print t('Comments'); ?>
  </h2>
  <?php print render($title_suffix); ?>
  <?php endif; ?>
  <?php print render($content['comments']); ?>
</div>
<?php endif; ?>
<?php if ($content['comment_form']): ?>
<div id="comments" class="art-commentsform">
  <h3>
    <?php print t('Add new comment'); ?>
  </h3>
  <?php print render($content['comment_form']); ?>
</div>
<?php endif; ?>
	<?php
	}

	function get_incorrect_version_message() {
		if (untitled_art_export_version() < untitled_art_get_drupal_major_version()) {
			return t('This version is not compatible with Drupal 7.x. and should be replaced.');
		}
		return '';
	}
}

