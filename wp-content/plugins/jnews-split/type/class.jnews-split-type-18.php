<?php
/**
 * @author : Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme JNews_Post_Split
 */
class JNews_Split_Type_18 extends JNews_Split_Type_Abstract {

	public function render() {
		$output = $this->before_content;
		$output .= $this->render_content();

		return $output;
	}

	public function render_content() {
		$output = null;

		$contents = $this->splitter->get_all_result();
		$contents = $contents['content'];

		foreach ( $contents as $id => $content ) {
			$image         = $this->get_first_image( $content['description'] );
			$number        = '';
			$wrapper_class = 'no-image';

			if ( ! empty( $image ) ) {
				$number        = $this->page_span( $id );
				$wrapper_class = "with-image";
			}

			$output .= "<div class='split-wrapper split-postlist {$wrapper_class} active' data-id='{$id}'>";
			$output .= "<div class='split-image-wrap'>{$number}{$image}</div>";
			$output .= $this->get_split_content( $id, $content['description'] );
			$output .= "</div>";
		}

		$output = "<div class='split-carousel has-nav-content top-split-nav'>{$output}</div>";

		$split_var   = 'jnews_split_' . uniqid();
		$script_json = wp_json_encode( $this->js_variable() );
		$script      = "<script> var {$split_var} = {$script_json} </script>";

		$output =
			"<div class='split-container split-template-{$this->get_split_type()}' data-id='{$split_var}'>
                {$output}
                {$script}
            </div>";

		return $output;
	}

	public function page_span( $index ) {
		$number = $this->get_page_number( $index + 1 );

		return "<span class=\"pagenum\">" . $number . "</span>";
	}

	public function get_split_content( $id, $content ) {
		$output = '';
		$output .= $this->get_current_title( $id );
		$output .= $this->get_parsed_content( $content );

		$output = "<div class='split-content'>{$output}</div>";

		return $output;
	}

	public function get_current_title( $index ) {
		$title   = $this->all_title[ $index ];
		$heading = $this->header_tag;

		return "<" . $heading . " class=\"current_title\"><span>{$title}</span></" . $heading . ">";
	}

	public function get_split_type() {
		return "18";
	}
}

