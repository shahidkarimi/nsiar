<?php
/**
 * @author : Jegtheme
 */
namespace JNews\Widget;

abstract class WidgetAbstract extends \WP_Widget
{
    /**
     * @var WidgetGenerator
     */
    protected $generator;

    public function __construct($id_base = false, $name = null, $widget_options = array(), $control_options = array())
    {
        // apply selective review
        $widget_options['customize_selective_refresh'] = true;
        parent::__construct( $id_base, $name , $widget_options , $control_options );
    }

    public function get_default_group()
    {
    	return esc_html__('General', 'jnews');
    }
}
