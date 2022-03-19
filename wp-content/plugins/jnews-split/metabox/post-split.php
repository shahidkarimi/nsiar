<?php
/**
 * @see \JNews\Util\ValidateLicense::is_license_validated
 * @since 8.0.0
 */
if ( function_exists( 'jnews_is_active' ) && jnews_is_active()::is_license_validated() ) {
	return [
		'id'       => 'jnews_post_split',
		'types'    => [ 'post' ],
		'title'    => 'JNews : Split Post',
		'priority' => 'high',
		'template' => [

			[
				'type'        => 'toggle',
				'name'        => 'enable_post_split',
				'label'       => esc_html__( 'Enable Post Split on this Post', 'jnews-split' ),
				'description' => esc_html__( 'Enable post split on this page. Please don\'t use this feature with WordPress <!--nextpage-->. This plugin will only get first matched tag criteria (on same wrapper). If you have multiple row / column or child with tag that build by page builder, it will be ignored.', 'jnews-split' ),
			],

			[
				'type'            => 'group',
				'repeating'       => false,
				'length'          => 1,
				'name'            => 'post_split',
				'title'           => esc_html__( 'Post Split Option', 'jnews-split' ),
				'description'     => esc_html__( 'Post Split Option', 'jnews-split' ),
				'active_callback' => [
					[
						'field'    => 'enable_post_split',
						'operator' => '==',
						'value'    => true,
					],
				],
				'fields'          => [
					[
						'type'            => 'radioimage',
						'name'            => 'template',
						'label'           => esc_html__( 'Post Split Template', 'jnews-split' ),
						'description'     => esc_html__( 'Choose post split template', 'jnews-split' ),
						'item_max_width'  => '118',
						'item_max_height' => '93',
						'items'           => [
							[
								'value' => '1',
								'label' => esc_html__( 'Split Post Template 1', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-1.png',
							],
							[
								'value' => '2',
								'label' => esc_html__( 'Split Post Template 2', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-2.png',
							],
							[
								'value' => '3',
								'label' => esc_html__( 'Split Post Template 3', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-3.png',
							],
							[
								'value' => '4',
								'label' => esc_html__( 'Split Post Template 4', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-4.png',
							],
							[
								'value' => '5',
								'label' => esc_html__( 'Split Post Template 5', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-5.png',
							],
							[
								'value' => '6',
								'label' => esc_html__( 'Split Post Template 6', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-6.png',
							],
							[
								'value' => '7',
								'label' => esc_html__( 'Split Post Template 7', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-7.png',
							],
							[
								'value' => '8',
								'label' => esc_html__( 'Split Post Template 8', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-8.png',
							],
							[
								'value' => '9',
								'label' => esc_html__( 'Split Post Template 9', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-9.png',
							],
							[
								'value' => '10',
								'label' => esc_html__( 'Split Post Template 10', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-10.png',
							],
							[
								'value' => '11',
								'label' => esc_html__( 'Split Post Template 11', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-11.png',
							],
							[
								'value' => '12',
								'label' => esc_html__( 'Split Post Template 12', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-12.png',
							],
							[
								'value' => '13',
								'label' => esc_html__( 'Split Post Template 13', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-13.png',
							],
							[
								'value' => '14',
								'label' => esc_html__( 'Split Post Template 14', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-14.png',
							],
							[
								'value' => '15',
								'label' => esc_html__( 'Split Post Template 15', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-15.png',
							],
							[
								'value' => '16',
								'label' => esc_html__( 'Split Post Template 16', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-16.png',
							],
							[
								'value' => '17',
								'label' => esc_html__( 'Split Post Template 17', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-17.png',
							],
							[
								'value' => '18',
								'label' => esc_html__( 'Split Post Template 18', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-18.png',
							],
							[
								'value' => '19',
								'label' => esc_html__( 'Split Post Template 19', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-19.png',
							],
							[
								'value' => '20',
								'label' => esc_html__( 'Split Post Template 20', 'jnews-split' ),
								'img'   => JNEWS_SPLIT_URL . '/assets/img/post-split-20.png',
							],
						],
						'default'         => [
							'1',
						],
					],

					[
						'type'        => 'select',
						'name'        => 'tag',
						'label'       => esc_html__( 'Split Post by using this Tag', 'jnews-split' ),
						'description' => esc_html__( 'Your post will be split with this header as their mark', 'jnews-split' ),
						'default'     => 'h2',
						'items'       => [
							[
								'value' => 'h1',
								'label' => esc_html__( 'Heading 1 ( H1 Tag )', 'jnews-split' ),
							],
							[
								'value' => 'h2',
								'label' => esc_html__( 'Heading 2 ( H2 Tag )', 'jnews-split' ),
							],
							[
								'value' => 'h3',
								'label' => esc_html__( 'Heading 3 ( H3 Tag )', 'jnews-split' ),
							],
							[
								'value' => 'h4',
								'label' => esc_html__( 'Heading 4 ( H4 Tag )', 'jnews-split' ),
							],
							[
								'value' => 'h5',
								'label' => esc_html__( 'Heading 5 ( H5 Tag )', 'jnews-split' ),
							],
							[
								'value' => 'h6',
								'label' => esc_html__( 'Heading 6 ( H6 Tag )', 'jnews-split' ),
							],
						],
					],

					[
						'type'        => 'select',
						'name'        => 'numbering',
						'label'       => esc_html__( 'Split Header Numbering', 'jnews-split' ),
						'description' => esc_html__( 'Choose how your split page number used', 'jnews-split' ),
						'default'     => 'asc',
						'items'       => [
							[
								'value' => 'desc',
								'label' => esc_html__( 'Descending (ex : 3, 2, 1)', 'jnews-split' ),
							],
							[
								'value' => 'asc',
								'label' => esc_html__( 'Ascending (ex : 1, 2, 3)', 'jnews-split' ),
							],
						],
					],

					[
						'type'            => 'select',
						'name'            => 'mode',
						'label'           => esc_html__( 'Load Mode for Post Split', 'jnews-split' ),
						'description'     => esc_html__( 'Choose your how your post split will load', 'jnews-split' ),
						'default'         => 'normal',
						'items'           => [
							[
								'value' => 'normal',
								'label' => esc_html__( 'Normal Load', 'jnews-split' ),
							],
							[
								'value' => 'all',
								'label' => esc_html__( 'Load All Content', 'jnews-split' ),
							],
							[
								'value' => 'ajax',
								'label' => esc_html__( 'Load Content with Ajax', 'jnews-split' ),
							],
						],
						'active_callback' => [
							[
								'field'    => 'template',
								'operator' => 'in',
								'value'    => [ '1', '2', '3', '4', '5' ],
							],
						],
					],

					[
						'type'            => 'toggle',
						'name'            => 'enable_toc',
						'label'           => esc_html__( 'Table of Contents', 'jnews-split' ),
						'description'     => esc_html__( 'Activate table of contents on top of content when using this split post type', 'jnews-split' ),
						'default'         => false,
						'active_callback' => [
							[
								'field'    => 'template',
								'operator' => 'in',
								'value'    => [ '6', '7', '8', '9', '10', '11', '12', '13', '14' ],
							],
						],
					],

					[
						'type'            => 'select',
						'name'            => 'toc_type',
						'label'           => esc_html__( 'Table of Contents Type', 'jnews-split' ),
						'description'     => esc_html__( 'Choose table of contents type', 'jnews-split' ),
						'default'         => 'normal',
						'items'           => [
							[
								'value' => 'normal',
								'label' => esc_html__( 'Normal', 'jnews-split' ),
							],
							[
								'value' => 'floating',
								'label' => esc_html__( 'Floating', 'jnews-split' ),
							],
							[
								'value' => 'both',
								'label' => esc_html__( 'Both', 'jnews-split' ),
							],
						],
						'active_callback' => [
							[
								'field'    => 'template',
								'operator' => 'in',
								'value'    => [ '6', '7', '8', '9', '10', '11', '12', '13', '14' ],
							],
							[
								'field'    => 'enable_toc',
								'operator' => '==',
								'value'    => true,
							],
						],
					],
				],
			],


		],
	];
} else {
	return [
		'id'       => 'jnews_post_split',
		'types'    => [ 'post' ],
		'title'    => 'JNews : Split Post',
		'priority' => 'high',
		'template' => [
			[
				'type'        => 'notebox',
				'name'        => 'activate_license',
				'status'      => 'error',
				'label'       => esc_html__( 'Activate License', 'jnews-split' ),
				'description' => sprintf(
					wp_kses(
						__(
							'<span style="display: block;">Please activate your copy of JNews to unlock this feature. Click button bellow to activate:</span>
						<span class="jnews-notice-button">
							<a href="%s" class="button-primary jnews_customizer_activate_license">Activate Now</a>
						</span>',
							'jnews-split'
						),
						[
							'strong' => [],
							'span'   => [
								'style' => true,
								'class' => true,
							],
							'a'      => [
								'href'  => true,
								'class' => true,
							],
						]
					),
					get_admin_url()
				),
			],
		],
	];
}
