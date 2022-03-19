<?php
/**
 * @see \JNews\Util\ValidateLicense::is_license_validated
 * @since 8.0.0
 */
if ( function_exists( 'jnews_is_active' ) && jnews_is_active()::is_license_validated() ) {
	return [
		'id'       => 'jnews_social_meta',
		'types'    => [ 'post', 'page' ],
		'title'    => 'JNews : Social Meta',
		'priority' => 'high',
		'template' => [

			[
				'type'   => 'tab',
				'name'   => 'facebook_social_meta',
				'title'  => esc_html__( 'Facebook Social Meta', 'jnews-meta-header' ),
				'fields' => [
					[
						'type'        => 'textbox',
						'name'        => 'fb_title',
						'label'       => esc_attr__( 'FB Share Title', 'jnews-meta-header' ),
						'description' => esc_attr__( 'Leave this option empty to use this post / page title', 'jnews-meta-header' ),
					],
					[
						'type'        => 'textarea',
						'name'        => 'fb_description',
						'label'       => esc_attr__( 'FB Share Description', 'jnews-meta-header' ),
						'description' => esc_attr__( 'Leave this option empty to use this post / page excerpt', 'jnews-meta-header' ),
					],
					[
						'type'        => 'imageupload',
						'name'        => 'fb_image',
						'label'       => esc_attr__( 'FB Share Image', 'jnews-meta-header' ),
						'description' => esc_attr__( 'Leave this option empty to use default featured image', 'jnews-meta-header' ),
					],
				],
			],


			[
				'type'   => 'tab',
				'name'   => 'twitter_social_meta',
				'title'  => esc_html__( 'Twitter Social Meta', 'jnews-meta-header' ),
				'fields' => [
					[
						'type'        => 'textbox',
						'name'        => 'twitter_title',
						'label'       => esc_attr__( 'Twitter Share Title', 'jnews-meta-header' ),
						'description' => esc_attr__( 'Leave this option empty to use post / page title', 'jnews-meta-header' ),
					],
					[
						'type'        => 'textarea',
						'name'        => 'twitter_description',
						'label'       => esc_attr__( 'Twitter Share Description', 'jnews-meta-header' ),
						'description' => esc_attr__( 'Leave this option empty to use post / page excerpt', 'jnews-meta-header' ),
					],
					[
						'type'        => 'imageupload',
						'name'        => 'twitter_image',
						'label'       => esc_attr__( 'Twitter Share Image', 'jnews-meta-header' ),
						'description' => esc_attr__( 'Leave this option empty to use default featured image', 'jnews-meta-header' ),
					],
				],
			],

		],
	];
} else {
	return [
		'id'       => 'jnews_social_meta',
		'types'    => [ 'post', 'page' ],
		'title'    => 'JNews : Social Meta',
		'priority' => 'high',
		'template' => [
			[
				'type'   => 'tab',
				'name'   => 'facebook_social_meta',
				'title'  => esc_html__( 'Facebook Social Meta', 'jnews-meta-header' ),
				'fields' => [],
			],

			[
				'type'   => 'tab',
				'name'   => 'twitter_social_meta',
				'title'  => esc_html__( 'Twitter Social Meta', 'jnews-meta-header' ),
				'fields' => [],
			],
		],
	];
}
