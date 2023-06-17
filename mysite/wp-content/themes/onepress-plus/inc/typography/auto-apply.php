<?php
/**
 * This file help load typography automatically
 *
 * Auto add style for typography settings
 *
 * @see onepress_typography_helper_auto_apply
 */

onepress_typography_helper_auto_apply(
	'onepress_typo_p', // customize setting ID.
	'body, body p', // CSS selector.
	null,
	false,
	'.editor-styles-wrapper *'
);

onepress_typography_helper_auto_apply(
	'onepress_typo_site_title', // customize setting ID.
	'#page .site-branding .site-title, #page .site-branding .site-text-logo' // CSS selector.
);

onepress_typography_helper_auto_apply(
	'onepress_typo_site_tagline', // customize setting ID.
	'#page .site-branding .site-description' // CSS selector.
);

onepress_typography_helper_auto_apply(
	'onepress_typo_menu', // customize setting ID.
	'.onepress-menu a' // CSS selector.
);

onepress_typography_helper_auto_apply(
	'onepress_hero_heading', // customize setting ID.
	'.hero__content .hero-large-text, .hero__content .hcl2-content h1, .hero__content .hcl2-content h2, .hero__content .hcl2-content h3' // CSS selector.
);

onepress_typography_helper_auto_apply(
	'onepress_typo_heading', // customize setting ID.
	'body h1, body h2, body h3, body h4, body h5, body h6,
	.entry-header .entry-title,
	body .section-title-area .section-title, body .section-title-area .section-subtitle, body .hero-content-style1 h2', // CSS selector.
	null,
	false,
	'.edit-post-visual-editor.editor-styles-wrapper .editor-post-title__input, 
	.edit-post-visual-editor.editor-styles-wrapper h1, 
	.edit-post-visual-editor.editor-styles-wrapper h2, 
	.edit-post-visual-editor.editor-styles-wrapper h3, 
	.edit-post-visual-editor.editor-styles-wrapper h4, 
	.edit-post-visual-editor.editor-styles-wrapper h5, 
	.edit-post-visual-editor.editor-styles-wrapper h6'
);

onepress_typography_helper_auto_apply(
	'onepress_slider_slide_typo_title', // customize setting ID.
	'.section-slider .section-op-slider .item--title' // CSS selector.
);

onepress_typography_helper_auto_apply(
	'onepress_slider_slide_typo_content', // customize setting ID.
	'.section-slider .section-op-slider .item--desc' // CSS selector.
);
