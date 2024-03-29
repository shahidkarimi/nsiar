<?php

$options = [];

$options[] = [
	'id'          => 'jnews_option[split_loader]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => 'dot',
	'type'        => 'jnews-select',
	'section'     => 'jnews_global_loader_section',
	'label'       => esc_html__( 'Post Split Loader Style', 'jnews-split' ),
	'description' => esc_html__( 'Choose loader style for post split.', 'jnews-split' ),
	'choices'     => [
		'dot'    => esc_html__( 'Dot', 'jnews-split' ),
		'circle' => esc_html__( 'Circle', 'jnews-split' ),
		'square' => esc_html__( 'Square', 'jnews-split' ),
	],
	'output'      => [
		[
			'method'   => 'class-masking',
			'element'  => '.split-overlay  .preloader_type',
			'property' => [
				'dot'    => 'preloader_dot',
				'circle' => 'preloader_circle',
				'square' => 'preloader_square',
			],
		],
	],
];

return $options;