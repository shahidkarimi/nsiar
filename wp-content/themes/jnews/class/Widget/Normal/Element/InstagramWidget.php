<?php
/**
 * @author : Jegtheme
 */

namespace JNews\Widget\Normal\Element;

use JNews\Widget\Normal\NormalWidgetInterface;

class InstagramWidget implements NormalWidgetInterface {

	/**
	 * @var string
	 */
	private $cache_key = 'jnews_instagram_widget_cache';

	/**
	 * @var string
	 */
	private $user_id;

	/**
	 * @var  integer
	 */
	private $count;

	public function get_options() {
		$fields = [];

        if ( ! class_exists( '\JNEWS_INSTAGRAM\Instagram' ) ) {
            $fields['plugin'] = [
                'title'     => esc_html__( 'Info : ', 'jnews' ),
                'desc'      => esc_html__( 'This widget requires the JNews Instagram Feed plugin to be installed.', 'jnews' ),
                'type'      => 'alert',
			];
		}

		$fields['title'] = [
			'title' => esc_html__( 'Title', 'jnews' ),
			'desc'  => esc_html__( 'Title on widget header.', 'jnews' ),
			'type'  => 'text',
		];

		$fields['video'] = [
			'title'   => esc_html__( 'Video Post Option', 'jnews' ),
			'desc'    => esc_html__( 'Display Instagram video post option as thumbnail or video.', 'jnews' ),
			'type'    => 'select',
			'default' => 'thumbnail',
			'options' => [
				'thumbnail'   => esc_attr__( 'Thumbnail', 'jnews' ),
				'video'       => esc_attr__( 'Video', 'jnews' ),
			],
		];

		$fields['column'] = [
			'title'   => esc_html__( 'Set Column', 'jnews' ),
			'desc'    => esc_html__( 'Choose number of column widget.', 'jnews' ),
			'type'    => 'select',
			'default' => 3,
			'options' => [
				2 => esc_html__( '2 Columns', 'jnews' ),
				3 => esc_html__( '3 Columns', 'jnews' ),
				4 => esc_html__( '4 Columns', 'jnews' ),
			],
		];

		$fields['row'] = [
			'title'   => esc_html__( 'Set Row', 'jnews' ),
			'desc'    => esc_html__( 'Choose number of row widget.', 'jnews' ),
			'type'    => 'slider',
			'options' => [
				'min'  => '1',
				'max'  => '10',
				'step' => '1',
			],
			'default' => 3,
		];

		$fields['hover'] = [
			'title'   => esc_html__( 'Hover Style', 'jnews' ),
			'desc'    => esc_html__( 'Choose hover effect style.', 'jnews' ),
			'type'    => 'select',
			'default' => 'normal',
			'options' => [
				'normal'      => esc_html__( 'Normal', 'jnews' ),
				'icon'        => esc_html__( 'Show Icon', 'jnews' ),
				'like'        => esc_html__( 'Show Like Count', 'jnews' ),
				'comment'     => esc_html__( 'Show Comment Count', 'jnews' ),
				'zoom'        => esc_html__( 'Zoom', 'jnews' ),
				'zoom-rotate' => esc_html__( 'Zoom Rotate', 'jnews' ),
				''            => esc_html__( 'No Effect', 'jnews' ),
			],
		];

		$fields['sort'] = [
			'title'   => esc_html__( 'Sort Type', 'jnews' ),
			'desc'    => esc_html__( 'Choose sort type.', 'jnews' ),
			'type'    => 'select',
			'default' => 'most_recent',
			'options' => [
				'most_recent'  => esc_html__( 'Most Recent', 'jnews' ),
				'least_recent' => esc_html__( 'Least Recent', 'jnews' ),
			],
		];

		$fields['button'] = [
			'title' => esc_html__( 'Follow Button Text', 'jnews' ),
			'desc'  => esc_html__( 'Leave it empty if you wont to show it.', 'jnews' ),
			'type'  => 'text',
		];

		$fields['newtab'] = [
			'title' => esc_html__( 'Open New Tab', 'jnews' ),
			'desc'  => esc_html__( 'Open Instagram profile page on new tab.', 'jnews' ),
			'type'  => 'checkbox',
		];

		return $fields;
	}


	public function render_widget( $instance, $text_content = null ) {
		$this->row    = $instance['row'];
		$this->column = $instance['column'];
		$this->video  = $instance['video'];
		$this->count  = $this->row * $this->column;
		$this->hover  = $instance['hover'];
		$this->sort   = $instance['sort'];
		$this->newtab = isset( $instance['newtab'] ) ? 'target=\'_blank\'' : '';
		$this->button = ! empty( $instance['button'] ) ? esc_html( $instance['button'] ) : false;

		$this->render_content();
	}


	protected function render_content() {
		$content = $follow_button = '';

		$param = [
			'row'    => $this->row ?: '3',
			'column' => $this->column ?: '3',
			'video'  => $this->video ?: 'thumbnail',
			'sort'   => $this->sort ?: 'most_recent',
			'hover'  => $this->hover ?: 'normal',
			'newtab' => $this->newtab,
			'follow' => $this->button,
		];

		if ( class_exists( '\JNEWS_INSTAGRAM\Instagram' ) ) {
			$instagram     = new \JNEWS_INSTAGRAM\Instagram( $param );
			$content       = $instagram->generate_content();
			$follow_button = $instagram->follow_button( $param['follow'] );
		} else {
			$response = esc_html__( 'Please install/update and activate JNews Instagram plugin.', 'jnews' );
			$content  = '<div class="alert alert-error alert-compatibility" style="position: relative; opacity: 1; visibility: visible;">' . $response . '</div>';
		}

		$output =
			"<div class='jeg_instagram_widget jeg_grid_thumb_widget clearfix'>
                {$follow_button}
                <ul class='instagram-pics col{$param['column']} {$param['hover']}'>
                    {$content}
                </ul>
            </div>";

		echo jnews_sanitize_output( $output );
	}

}
