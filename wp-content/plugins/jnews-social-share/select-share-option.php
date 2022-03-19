<?php

$options = [];

$options[] = [
	'id'          => 'jnews_select_share',
	'section'     => 'jnews_select_share_section',
	'transport'   => 'postMessage',
	'default'     => true,
	'type'        => 'jnews-toggle',
	'label'       => esc_html__( 'Enable JNews Select & Share', 'jnews-select-share' ),
	'description' => esc_html__( 'Use JNews Select & Share on the article.', 'jnews-select-share' ),
	'postvar'     => [
		[
			'redirect' => 'single_post_tag',
			'refresh'  => true,
		],
	],
];

return $options;
