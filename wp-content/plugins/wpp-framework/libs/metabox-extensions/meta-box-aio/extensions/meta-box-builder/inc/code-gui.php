<?php
require MBB_INC_DIR . 'components/tabs.php';
require MBB_INC_DIR . 'class-meta-box-builder-encoder.php';
list( , $url ) = RWMB_Loader::get_path( dirname( dirname( __FILE__ ) ) );
$encoder = new Meta_Box_Builder_Encoder();
?>

<div id="export-gui" ng-app="Builder">
	<p>
		The snippet below is the generated code of current meta box.<br>
		This helpful when you:
		<ul>
			<li>- Copy or share meta box to other location which doesn't have Meta Box Builder installed.</li>
			<li>- Improve the performance since the meta box is loaded directly from your file.</li>
		</ul>

		To use this, the easiest way is copy whole snippet to your theme <code>functions.php</code>. <br>
		If you wanna take a deeper look inside how to register meta box. See <a href="https://docs.metabox.io/creating-meta-boxes/">Creating Meta Boxes</a> guide.
	</p>

	<div class="render-html" ng-controller="BuilderController" ng-init="init()">
		<pre class="builder-code"><code class="php"><?php echo esc_textarea( $encoder->encode( $this->meta ) ); ?></code></pre>
		<a href="javascript:void(0)" class="mbb-button--copy" title="Click to copy the code"><svg class="mbb-icon--copy" aria-hidden="true" role="img"><use href="#mbb-icon-copy" xlink:href="#icon-copy"></use></svg> Copy</a>

	</div><!--.menu-settings-->

</div>

<svg style="display: none;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
	<symbol id="mbb-icon-copy" viewBox="0 0 1024 896">
		<path d="M128 768h256v64H128v-64z m320-384H128v64h320v-64z m128 192V448L384 640l192 192V704h320V576H576z m-288-64H128v64h160v-64zM128 704h160v-64H128v64z m576 64h64v128c-1 18-7 33-19 45s-27 18-45 19H64c-35 0-64-29-64-64V192c0-35 29-64 64-64h192C256 57 313 0 384 0s128 57 128 128h192c35 0 64 29 64 64v320h-64V320H64v576h640V768zM128 256h512c0-35-29-64-64-64h-64c-35 0-64-29-64-64s-29-64-64-64-64 29-64 64-29 64-64 64h-64c-35 0-64 29-64 64z" />
	</symbol>
</svg>
