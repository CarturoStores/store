<?php

/* Common Drupal methods definitons using in Artisteer theme export */

function untitled_art_node_worker($node) {
  $links_output = untitled_art_links_woker($node->links);
  $terms_output = untitled_art_terms_worker($node->taxonomy);

  $output = $links_output;
  if (!empty($links_output) && !empty($terms_output)) {
    $output .= '&nbsp;|&nbsp;';
  }
  $output .= $terms_output;
  return $output;
}

/*
 * Split out taxonomy terms by vocabulary.
 *
 * @param $terms
 *   An object providing all relevant information for displaying terms:
 *
 * @ingroup themeable
 */
function untitled_art_terms_worker($terms) {
  $result = '';
  
  return $result;
}

/**
 * Return a themed set of links.
 *
 * @param $links
 *   A keyed array of links to be themed.
 * @param $attributes
 *   A keyed array of attributes
 * @return
 *   A string containing an unordered list of links.
 */
function untitled_art_links_woker($links, $attributes = array('class' => 'links')) {
  $output = '';

  if (!empty($links)) {
    $output = '';

    $num_links = count($links);
    $index = 0;

    foreach ($links as $key => $link) {
      $class = $key;
      if (strpos ($class, "read_more") !== FALSE) {
        continue;
      }

      // Automatically add a class to each link and also to each LI
      if (isset($link['attributes']) && isset($link['attributes']['class'])) {
        $link['attributes']['class'] .= ' ' . $key;
      }
      else {
        $link['attributes']['class'] = $key;
      }

      // Add first and last classes to the list of links to help out themers.
      $extra_class = '';
      if ($index == 1) {
        $extra_class .= 'first ';
      }
      if ($index == $num_links) {
        $extra_class .= 'last ';
      }

      $link_output = untitled_art_html_link_output($link);
      if (!empty($class)) {
        
        
      }
      else {
        $output .= '&nbsp;|&nbsp;' . $link_output;
        $index++;
      }
    }
  }

  return $output;
}

function untitled_art_html_link_output($link) {
  $output = '';
  // Is the title HTML?
  $html = isset($link['html']) ? $link['html'] : NULL;

  // Initialize fragment and query variables.
  $link['query'] = isset($link['query']) ? $link['query'] : NULL;
  $link['fragment'] = isset($link['fragment']) ? $link['fragment'] : NULL;

  if (isset($link['href'])) {
    if (untitled_art_get_drupal_major_version() == 5) {
      $output = l($link['title'], $link['href'], $link['attributes'], $link['query'], $link['fragment'], FALSE, $html);
    }
    else {
      $output = l($link['title'], $link['href'], array('language' => $link['language'], 'attributes'=>$link['attributes'], 'query'=>$link['query'], 'fragment'=>$link['fragment'], 'absolute'=>FALSE, 'html'=>$html));
    }
  }
  else if ($link['title']) {
    if (!$html) {
      $link['title'] = check_plain($link['title']);
    }
    $output = $link['title'];
  }

  return $output;
}

function untitled_art_content_replace($content) {
  $first_time_str = '<div id="first-time"';
  $article_str = ' class="art-article"';
  $pos = strpos($content, $first_time_str);
  if($pos !== FALSE)
  {
    $output = str_replace($first_time_str, $first_time_str . $article_str, $content);
    $output = <<< EOT
    <div class="art-box art-post">
      <div class="art-box-body art-post-body">
  <article class="art-post-inner art-article">
   <div class="art-postcontent">
      $output
    </div>
  <div class="cleared"></div>
    </article>
  <div class="cleared"></div>
  </div>
  </div>
EOT;
  }
  else 
  {
    $output = $content;
  }
  return $output;
}

function untitled_art_placeholders_output($var1, $var2, $var3, $id = '') {
  $output = '<div' . (!empty($id) ? " id=\"$id\" " : ' ') . 'class="art-content-layout">';
  $output .= '<div class="art-content-layout-row">';
  if (!empty($var1) && !empty($var2) && !empty($var3)) {
    $output .= <<< EOT
        <div class="art-layout-cell third-width">$var1</div>
        <div class="art-layout-cell third-width">$var2</div>
        <div class="art-layout-cell">$var3</div>
EOT;
  }
  else if (!empty($var1) && !empty($var2)) {
    $output .= <<< EOT
        <div class="art-layout-cell third-width">$var1</div>
        <div class="art-layout-cell">$var2</div>
EOT;
  }
  else if (!empty($var2) && !empty($var3)) {
    $output .= <<< EOT
        <div class="art-layout-cell two-thirds-width">$var2</div>
        <div class="art-layout-cell">$var3</div>
EOT;
  }
  else if (!empty($var1) && !empty($var3)) {
    $output .= <<< EOT
        <div class="art-layout-cell half-width">$var1</div>
        <div class="art-layout-cell">$var3</div>
EOT;
  }
  else {
    if (!empty($var1)) {
      $output .= <<< EOT
        <div class="art-layout-cell">$var1</div>
EOT;
    }
    if (!empty($var2)) {
      $output .= <<< EOT
        <div class="art-layout-cell">$var2</div>
EOT;
    }
    if (!empty($var3)) {
      $output .= <<< EOT
        <div class="art-layout-cell">$var3</div>
EOT;
    }
  }
  $output .= '</div>';
  $output .= '</div>';
  return $output;
}

function untitled_art_get_sidebar($sidebar, $vnavigation, $class) {
  $result = 'art-layout-cell ';
  if (empty($sidebar) && empty($vnavigation)) {
    $result .= 'art-content';
  }
  else {
    $result .= $class;
  }

  $output = '<div class="'.$result.'">'.render($vnavigation) . render($sidebar).'</div>'; 
  return $output;
}

function untitled_art_submitted_worker($date, $author) {
  $output = '';
  if ($date != '') {
    
  }
  if ($author != '') {
    
  }
  return $output;
}

function untitled_art_links_set($links) {
  $size = sizeof($links);
  if ($size == 0) {
    return FALSE;
  }

  //check if there's "Read more" in node links only  
  $read_more_link = $links['node_read_more'];
  if ($read_more_link != NULL && $size == 1) {
    return FALSE;
  }

  return TRUE;
}

/**
 * Method to define node title output.
 *
*/
function untitled_art_node_title_output($title, $node_url, $page) {
  $output = '';
  if (!$page)
    $output = "<h2 class='art-postheader'><span class='art-postheadericon'><a href='$node_url' title='$title'>$title</a></span></h2>";
  else
    $output = "<h1 class='art-postheader'><span class='art-postheadericon'>$title</span></h1>";
  return $output;
}

function untitled_art_process_menu_class($menu, $menu_class) {
  $result = $menu;
  $matches = array();
  $pattern = '~<ul(.*?class=[\'"])(.*?)([\'"])~';
  if (preg_match($pattern, $menu, $matches)) { // Has attribute 'class'
    $class_attr = $matches[2];
    $pattern = '/^menu$|^menu\s|\smenu\s|\smenu$/';
    $new_class_attr = preg_replace($pattern, ' '.$menu_class.' ', $class_attr);
    $str_pos = strpos($menu, $class_attr);
    $result = substr_replace($menu, $new_class_attr, $str_pos, strlen($class_attr));
  } else {
	$start = '<ul';
    $str_pos = strpos($menu, $start);
	if ($str_pos !== FALSE) { // Attribute 'class' doesn't exist
	  $new_str = $start." class='$menu_class'";
	  $result = substr_replace($menu, $new_str, $str_pos, strlen($start));
	}
  }
  
  return $result;
}

function untitled_art_hmenu_output($content) {
  return untitled_art_process_menu_class($content, 'art-hmenu');
}

function untitled_art_vmenu_output($subject, $content) {
  if (empty($content))
    return;

  $result = '';
  $vmenu = untitled_art_process_menu_class($content, 'art-vmenu');
  
  return $result;
}

function untitled_art_replace_image_path($content) {
  $content = preg_replace_callback('/(src=)([\'"])(?:images[\/\\\]?)?(.*?)\2()/', 'untitled_art_real_path', $content);
  $content = preg_replace_callback('/(url\()([\'"])(?:images[\/\\\]?)?(.*?)\2(\))/', 'untitled_art_real_path', $content);
  return $content;
}

function untitled_art_real_path($match) {
  list($str, $start, $quote, $filename, $end) = $match;
  $full_path = untitled_art_get_full_path_to_theme().'/images';
  return $start . $quote . $full_path . '/' . $filename . $quote . $end;
}