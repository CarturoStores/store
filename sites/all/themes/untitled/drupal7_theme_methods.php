<?php

/* Drupal 7 methods definitons */
$art_style = '';
$art_head = '';
function untitled_process_html(&$variables) {
    global $art_style, $art_head;
	$view = untitled_art_get_drupal_view();
	$message = $view->get_incorrect_version_message();
	if (!empty($message)) {
		drupal_set_message($message, 'error');
	}
	$variables['styles'] .= $art_style;
	
	$themePath = untitled_art_get_full_path_to_theme();
	$jqueryNoConflict = <<< EOT
<script>if ('undefined' != typeof jQuery) document._artxJQueryBackup = jQuery;</script>
<script type="text/javascript" src="$themePath/jquery.js"></script>
<script>jQuery.noConflict();</script>
<script type="text/javascript" src="$themePath/script.js"></script>
<script type="text/javascript" src="$themePath/script.responsive.js"></script>
$art_head
<script>if (document._artxJQueryBackup) jQuery = document._artxJQueryBackup;</script>
EOT;
	$variables['scripts'] .= $jqueryNoConflict;
}

function untitled_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible art-postheader">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb art-postcontent">' . implode(' | ', $breadcrumb) . '</div>';
    return $output;
  }
}

/**
 * Returns HTML for a button form element.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #attributes, #button_type, #name, #value.
 *
 * @ingroup themeable
 */
function untitled_button($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));

  $element['#attributes']['class'][] = 'form-' . $element['#button_type'] . ' art-button';
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }

  return '<input' . drupal_attributes($element['#attributes']) . ' />';
}

/**
 * Override or insert variables into the page template.
 */
function untitled_preprocess_page(&$vars) {
  global $art_style, $art_head;
  $vars['tabs'] = menu_primary_local_tasks();
  $vars['tabs2'] = menu_secondary_local_tasks();

  if (isset($vars['node'])) {
    if (isset($vars['art_style_'.$vars['node']->nid])) {
      $art_style = $vars['art_style_'.$vars['node']->nid];
    }
    if (isset($vars['art_head_'.$vars['node']->nid])) {
      $art_head = $vars['art_head_'.$vars['node']->nid];
    }
  }
  if (isset($vars['art_blocks_head'])) { 
    $art_head .= $vars['art_blocks_head'];
  }

  $vars['search_box'] = NULL;
  if (function_exists('search_box_form_submit')) {
    $vars['search_box'] = drupal_get_form('search_form');
  }
}

/**
 * Returns HTML for a single local task link.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: A render element containing:
 *     - #link: A menu link array with 'title', 'href', and 'localized_options'
 *       keys.
 *     - #active: A boolean indicating whether the local task is active.
 *
 * @ingroup themeable
 */
function untitled_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];
  
  $active_class = '';
  if (!empty($variables['element']['#active'])) {
    // Add text to indicate active tab for non-visual users.
    $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';
    $active_class = ' active';

    // If the link does not contain HTML already, check_plain() it now.
    // After we set 'html'=TRUE the link will not be sanitized by l().
    if (empty($link['localized_options']['html'])) {
      $link['title'] = check_plain($link['title']);
    }
    $link['localized_options']['html'] = TRUE;
    $link_text = t('!local-task-title!active', array('!local-task-title' => $link['title'], '!active' => $active));
  }

  //added art-class
  $link['localized_options']['attributes']['class'] = array('art-button');

  return "<li>".l($link_text, $link['href'], $link['localized_options'])."</li>\n";
}

/**
 * Returns HTML for a feed icon.
 *
 * @param $variables
 *   An associative array containing:
 *   - url: The url of the feed.
 *   - title: A descriptive title of the feed.
 */
function untitled_feed_icon($variables) {
  $text = t('Subscribe to @feed-title', array('@feed-title' => $variables['title']));
  return l(NULL, $variables['url'], array('html' => TRUE, 'attributes' => array('class' => array('feed-icon', 'art-rss-tag-icon'), 'title' => $text)));
}

/**
 * Returns HTML for a node preview for display during node creation and editing.
 *
 * @param $variables
 *   An associative array containing:
 *   - node: The node object which is being previewed.
 *
 * @ingroup themeable
 */
function untitled_node_preview($variables) {
  $node = $variables['node'];

  $output = '<div class="preview">';

  $preview_trimmed_version = FALSE;

  $elements = node_view(clone $node, 'teaser');
  $trimmed = drupal_render($elements);
  $elements = node_view($node, 'full');
  $full = drupal_render($elements);

  // Do we need to preview trimmed version of post as well as full version?
  if ($trimmed != $full) {
    drupal_set_message(t('The trimmed version of your post shows what your post looks like when promoted to the main page or when exported for syndication.<span class="no-js"> You can insert the delimiter "&lt;!--break--&gt;" (without the quotes) to fine-tune where your post gets split.</span>'));
	$preview_trimmed_version = t('Preview trimmed version');
	$output .= <<< EOT
    <div class="art-box art-post">
	<div class="art-box-body art-post-body">
    <article class="art-post-inner art-article">
    <div class="art-postcontent">
      <h3>
	  $preview_trimmed_version
	  </h3>
	</div>
    <div class="cleared"></div>
    </article>
	<div class="cleared"></div>
    </div>
    </div>
EOT;
	$output .= $trimmed;
    
	$preview_full_version = t('Preview full version');
	$output .= <<< EOT
    <div class="art-box art-post">
	<div class="art-box-body art-post-body">
    <article class="art-post-inner art-article">
    <div class="art-postcontent">
      <h3>
	  $preview_full_version
	  </h3>
    </div>
    <div class="cleared"></div>
    </article>
	<div class="cleared"></div>
    </div>
    </div>
EOT;

    $output .= $full;
  }
  else {
    $output .= $full;
  }
  $output .= "</div>\n";

  return $output;
}

/**
 * Return a Artisteer themed set of links.
 *
 * @param $content
 *   An object with node content.
 * @return
 *   A string containing an unordered list of links.
 */
function untitled_art_links_woker_D7($content) {
  $result = '';
  if (!isset($content['links'])) return $result;
  foreach (array_keys($content['links']) as $name) {
	$$name = & $content['links'][$name];
	if (isset($content['links'][$name]['#links'])) {
	  $links = $content['links'][$name]['#links'];
	  if (is_array($links)) {
		$output = untitled_art_links_html_output_D7($links);
		if (!empty($output)) {
			$result .= (empty($result)) ? $output : '&nbsp;|&nbsp;' . $output;
		}
	  }
    }
  }

  
  return $result;  
}

function untitled_art_terms_D7($content) {
	$result = NULL;
	foreach (array_keys($content) as $name)	{
		$$name = & $content[$name];
		$field_type = NULL;
		if (is_array($content[$name])) {
			if (isset($content[$name]['#field_type']))
				$field_type = $content[$name]['#field_type'];
		} else if (is_object($content[$name])) {
			if (isset($content[$name]->field_type))
				$field_type = $content[$name]->field_type;
		}
	    if ($field_type == NULL || $field_type != "taxonomy_term_reference") continue;
	    $result = $content[$name];
	}
	return $result;
}

function untitled_art_links_html_output_D7($links) {
	$output = '';
	$num_links = count($links);
    $index = 0;

	foreach ($links as $key => $link) {
	  $class = array($key);

      // Add first, last and active classes to the list of links to help out themers.
      if ($index == 0) {
        $class[] = 'first';
      }
      if ($index == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
          && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      
	  $link_output = '';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $link_output = l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $link_output = '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }
		
		
        

	}
	return $output;
}

/* Theming Drupal search form 
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form. The arguments
 *   that drupal_get_form() was originally called with are available in the
 *   array $form_state['build_info']['args'].
 * @param $form_id
 *   String representing the name of the form itself. Typically this is the
 *   name of the function that generated the form.
*/
function untitled_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form' || $form_id == 'search_form') {
    $form['#attributes'] = array('class' => array('art-search')); // Add Artisteer class
    $form['actions']['submit']['#attributes'] = array('class' => array('art-search-button')); // Add Artisteer class
  }
}

function untitled_art_label($variables) {
  $element = $variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
    return '';
  }

  // If the element is required, a required marker is appended to the label.
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element['#title']);

  $attributes = array();
  // Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after') {
    $attributes['class'] = 'option';
  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'] = 'element-invisible';
  }
  $art_class = $element['#type'] == 'checkbox' ? ' art-checkbox' : ($element['#type'] == 'radio' ? ' art-radiobutton' : '');
  if (isset($attributes['class']))
	$attributes['class'] .= $art_class;
  else
    $attributes['class'] = $art_class;

  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }

  $result = array('label_head' => ' <label' . drupal_attributes($attributes) . '>',
    'label_title' => $t('!title !required', array('!title' => $title, '!required' => $required)),
    'label_tail' => "</label>\n",);
  return  $result;
}

function untitled_form_element_label($variables) {
  $art_label = untitled_art_label($variables);

  // The leading whitespace helps visually separate fields from inline labels.
  return (isset($art_label['label_head']) && !empty($art_label['label_head']) ? $art_label['label_head'] : '') . 
          ' ' . (isset($art_label['label_title']) && !empty($art_label['label_title']) ? $art_label['label_title'] : '') .
          ' ' . (isset($art_label['label_tail']) && !empty($art_label['label_tail']) ? $art_label['label_tail'] : '');
}

function untitled_form_element($variables) {
  $element = &$variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  //add Artisteer styles to checkbox and radiobutton
  if (($element['#type'] == 'checkbox' || $element['#type'] == 'radio') && ($element['#title_display'] !== 'invisible')) {
    $art_label = untitled_art_label($variables);
    $output .= ' ' . (isset($art_label['label_head']) && !empty($art_label['label_head']) ? $art_label['label_head'] : '') . 
               $prefix . $element['#children'] . $suffix . "\n" . 
               ' ' . (isset($art_label['label_title']) && !empty($art_label['label_title']) ? $art_label['label_title'] : '') .
               ' ' . (isset($art_label['label_tail']) && !empty($art_label['label_tail']) ? $art_label['label_tail'] : '');
  } else {
    switch ($element['#title_display']) {
      case 'before':
      case 'invisible':
        $output .= ' ' . theme('form_element_label', $variables);
        $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
        break;

      case 'after':
        $output .= ' ' . $prefix . $element['#children'] . $suffix;
        $output .= ' ' . theme('form_element_label', $variables) . "\n";
        break;

      case 'none':
      case 'attribute':
        // Output no label and no required marker, only the children.
        $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
        break;
    }
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}

function untitled_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '<span class="more">…</span>',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => '<span class="active">'.$i.'</span>',
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '<span class="more">…</span>',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . untitled_art_item_list(array(
      'items' => $items,
      'attributes' => array('class' => array('art-pager')),
    ));
  }
}

function untitled_art_item_list($variables) {
  $items = $variables['items'];
  $attributes = $variables['attributes'];

  // Only output the list container and title, if there are any list items.
  // Check to see whether the block title exists before adding a header.
  // Empty headers are not semantic and present accessibility challenges.
  $output = '';

  if (!empty($items)) {
    $output .= "<div" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    foreach ($items as $i => $item) {
      $attributes = array();
      $children = array();
      $data = '';
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        // Render nested list.
        $data .= untitled_art_item_list(array('items' => $children, 'title' => NULL, 'type' => NULL, 'attributes' => $attributes));
      }
      if ($i == 0) {
        $attributes['class'][] = 'first';
      }
      if ($i == $num_items - 1) {
        $attributes['class'][] = 'last';
      }
      $output .= $data . "\n";
    }
    $output .= "</div>";
  }
  return $output;
}
