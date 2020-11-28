<#
data = _.defaults( data, {
	label: '',
	description: '',
	inputAttrs: '',
	'data-id': '',
	choices: {},
	multiple: 1,
    img:'',
	value: ( 1 < data.multiple ) ? [] : ''
} );

if ( 1 < data.multiple && data.value && _.isString( data.value ) ) {
	data.value = [ data.value ];
}
#>
<div class="kirki-input-container" data-id="{{ data.id }}">
	<label>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{ data.description }}</span>
		<# } #>
        <# if ( data.img ) { #>
            <img class="wpp-kirki-img-desk" src="{{ data.img }}" alt="">
        <# } #>
		<select
			data-id="{{ data['data-id'] }}"
			{{{ data.inputAttrs }}}
			<# if ( 1 < data.multiple ) { #>
				data-multiple="{{ data.multiple }}" multiple="multiple"
			<# } #>
			>
			<# _.each( data.choices, function( optionLabel, optionKey ) { #>
				<#
				selected = ( data.value === optionKey );
				if ( 1 < data.multiple && data.value ) {
					selected = _.contains( data.value, optionKey );
				}
				if ( _.isObject( optionLabel ) ) {
					#>
					<optgroup label="{{ optionLabel[0] }}">
						<# _.each( optionLabel[1], function( optgroupOptionLabel, optgroupOptionKey ) { #>
							<#
							selected = ( data.value === optgroupOptionKey );
							if ( 1 < data.multiple && data.value ) {
								selected = _.contains( data.value, optgroupOptionKey );
							}
							#>
							<option value="{{ optgroupOptionKey }}"<# if ( selected ) { #> selected<# } #>>{{{ optgroupOptionLabel }}}</option>
						<# } ); #>
					</optgroup>
				<# } else { #>
					<option value="{{ optionKey }}"<# if ( selected ) { #> selected<# } #>>{{{ optionLabel }}}</option>
				<# } #>
			<# } ); #>
		</select>
	</label>
</div>