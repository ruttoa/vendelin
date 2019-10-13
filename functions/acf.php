<?php 
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
  'key' => 'group_584983468eb50',
  'title' => 'Sivuasetukset',
  'fields' => array (
    array (
      'default_value' => 0,
      'message' => 'Piilota otsikko',
      'ui' => 0,
      'ui_on_text' => '',
      'ui_off_text' => '',
      'key' => 'field_5849897c95b43',
      'label' => 'Hide title',
      'name' => 'hide_title',
      'type' => 'true_false',
      'instructions' => 'Näytetäänkö sivun nimi headerissa.',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
    ),
    array (
      'tabs' => 'all',
      'toolbar' => 'full',
      'media_upload' => 1,
      'default_value' => '',
      'delay' => 1,
      'key' => 'field_58498a2c89f1a',
      'label' => 'Alaotsikko',
      'name' => 'alaotsikko',
      'type' => 'wysiwyg',
      'instructions' => 'Sivuotsikon alapuolelle tuleva tekstialue',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
    ),
    array (
      'tabs' => 'all',
      'toolbar' => 'full',
      'media_upload' => 1,
      'default_value' => '',
      'delay' => 1,
      'key' => 'field_58498a8389f1b',
      'label' => 'Above footer content area',
      'name' => 'above_footer_content_area',
      'type' => 'wysiwyg',
      'instructions' => 'Sivun alalaidassa ennen footeria oleva osio esim. yhteydenottolomaketta varten',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
    ),
  ),
  'location' => array (
    array (
      array (
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'page',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => 1,
  'description' => '',
));

endif;
?>