<?php
/*
Plugin Name: Girokonto Information
Plugin URI: http://wordpress.org/extend/plugins/girokonto-information/
Description: Adds a widget which displays the latest information by http://www.girokonto-im-vergleich.eu/
Version: 0.1
Author: Ralf
Author URI: http://www.girokonto-im-vergleich.eu/
License: GPL3
*/

function girokontoinformation()
{
  $options = get_option("widget_girokontoinformation");
  if (!is_array($options)){
    $options = array(
      'title' => 'Girokonto Information',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://finanznews.girokonto-im-vergleich.eu/feed/'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale L�nge, auf die ein Titel, falls notwendig, gek�rzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // L�nge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel l�nger als die vorher definierte Maximall�nge ist,
    // wird er gek�rzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_girokontoinformation($args)
{
  extract($args);
  
  $options = get_option("widget_girokontoinformation");
  if (!is_array($options)){
    $options = array(
      'title' => 'Girokonto Information',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  girokontoinformation();
  echo $after_widget;
}

function girokontoinformation_control()
{
  $options = get_option("widget_girokontoinformation");
  if (!is_array($options)){
    $options = array(
      'title' => 'Girokonto Information',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['girokontoinformation-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['girokontoinformation-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['girokontoinformation-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['girokontoinformation-CharCount']);
    update_option("widget_girokontoinformation", $options);
  }
?> 
  <p>
    <label for="girokontoinformation-WidgetTitle">Widget Title: </label>
    <input type="text" id="girokontoinformation-WidgetTitle" name="girokontoinformation-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="girokontoinformation-NewsCount">Max. News: </label>
    <input type="text" id="girokontoinformation-NewsCount" name="girokontoinformation-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="girokontoinformation-CharCount">Max. Characters: </label>
    <input type="text" id="girokontoinformation-CharCount" name="girokontoinformation-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="girokontoinformation-Submit"  name="girokontoinformation-Submit" value="1" />
  </p>
  
<?php
}

function girokontoinformation_init()
{
  register_sidebar_widget(__('Girokonto Information'), 'widget_girokontoinformation');    
  register_widget_control('Girokonto Information', 'girokontoinformation_control', 300, 200);
}
add_action("plugins_loaded", "girokontoinformation_init");
?>
