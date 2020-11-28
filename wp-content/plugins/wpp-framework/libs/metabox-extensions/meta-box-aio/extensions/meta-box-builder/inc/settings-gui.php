<h2 class="title"><?php esc_html_e( 'General', 'meta-box-builder' ); ?></h2>
<table class="form-table" ng-show="tabExists">
	<tr>
		<th><label for="meta-box-tabs-style"><?php esc_html_e('Tabs', 'meta-box-builder'); ?></label></th>
		<td>
			<label>
				<?php esc_html_e( 'Style', 'meta-box-builder' ); ?>
				<select ng-model="meta.tab_style">
					<option value="default"><?php esc_html_e( 'Default', 'meta-box-builder' ); ?></option>
					<option value="box"><?php esc_html_e( 'Box', 'meta-box-builder' ); ?></option>
					<option value="left"><?php esc_html_e( 'Left', 'meta-box-builder' ); ?></option>
				</select>
			</label>
			<label>
				<?php esc_html_e( 'Wrapper', 'meta-box-builder' ); ?>
				<input id="meta-box-tabs-wrapper" type="checkbox" ng-model="meta.tab_wrapper" ng-true-value="'true'" ng-false-value="'false'"/>
			</label>
		</td>
	</tr>
</table>
<table class="form-table form-table-for">
	<tr>
		<th><label><?php esc_html_e( 'Show for', 'meta-box-builder' ); ?></label></th>
		<td>
			<select id="select-for" name="forobject" ng-model="meta.for">
				<option value="post_types" selected="selected"><?php esc_html_e('Posts', 'meta-box-builder'); ?></option>
				<option value="taxonomies"><?php esc_html_e('Terms', 'meta-box-builder'); ?></option>
				<option value="settings_pages"><?php esc_html_e('Setting Pages', 'meta-box-builder'); ?></option>
				<option value="user"><?php esc_html_e('Users', 'meta-box-builder'); ?></option>
				<option value="comment"><?php esc_html_e('Comments', 'meta-box-builder'); ?></option>
				<option value="attachments"><?php esc_html_e('Attachments', 'meta-box-builder'); ?></option>
			</select>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'post_types'">
		<th><label><?php esc_html_e('Post types', 'meta-box-builder'); ?></label></th>
		<td>
			<select id="select-post-types" multiple="multiple" ng-model="meta.post_types" ng-options="post_type as post_type.name for (key, post_type) in post_types track by post_type.slug"></select>
		</td>
	</tr>
	 <tr class="meta-box-for" ng-show="meta.for == 'post_types'">
		<th><h2 class="title"><?php esc_html_e( 'Options', 'meta-box-builder' ); ?></h2></th>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'post_types'">
		<th>
			<label for="meta-box-context"><?php esc_html_e( 'Position', 'meta-box-builder' ); ?>
		</th>
		<td>
			<select name="context" id="meta-box-context" ng-model="meta.context">
				<option value="normal"><?php esc_html_e( 'Normal (after content)', 'meta-box-builder' ); ?></option>
				<option value="advanced"><?php esc_html_e( 'Advanced (after normal)', 'meta-box-builder' ); ?></option>
				<option value="side"><?php esc_html_e( 'Side', 'meta-box-builder' ); ?></option>
				<option value="form_top"><?php esc_html_e( 'Before post title', 'meta-box-builder' ); ?></option>
				<option value="after_title"><?php esc_html_e( 'After post title', 'meta-box-builder' ); ?></option>
				<option value="after_editor"><?php esc_html_e( 'After the post content editor', 'meta-box-builder' ); ?></option>
				<option value="before_permalink"><?php esc_html_e( 'Before permalink', 'meta-box-builder' ); ?></option>
			</select>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'post_types'">
		<th><label><?php esc_html_e('Priority', 'meta-box-builder'); ?></label></th>
		<td>
			<ul class="builder-grid">
				<li class="builder-col">
					<label><input type="radio" ng-model="meta.priority" name="priority" value="high"> <?php _e('High', 'meta-box-builder'); ?></label>
				</li>
				<li class="builder-col">
					<label><input type="radio" ng-model="meta.priority" name="priority" value="low"> <?php _e('Low', 'meta-box-builder'); ?></label>
				</li>
			</ul>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'post_types'">
		<th><label for="meta-box-style"><?php esc_html_e( 'Style', 'meta-box-builder' ); ?></th>
		<td>
			<select name="style" id="meta-box-style" ng-model="meta.style">
				<option value=""><?php esc_html_e( 'Standard (WordPress meta box)', 'meta-box-builder' ); ?></option>
				<option value="seamless"><?php esc_html_e( 'Seamless (no meta box)', 'meta-box-builder' ); ?></option>
			</select>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'post_types'">
		<th><label for="meta-box-default-hidden"><?php esc_html_e( 'Hidden by default.', 'meta-box-builder' ); ?></th>
		<td>
			<label>
				<input id="meta-box-default-hidden" type="checkbox" ng-model="meta.default_hidden" ng-true-value="'true'" ng-false-value="'false'"/>
				<?php esc_html_e( 'The meta box is hidden by default and requires users to select the corresponding checkbox in Screen Options to show it', 'meta-box-builder' ); ?>
			</label>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'post_types'">
		<th><label for="metabox-auto-save"><?php esc_html_e('Autosave', 'meta-box-builder'); ?></label></th>
		<td>
			<input id="metabox-auto-save" ng-true-value="'true'" ng-false-value="'false'" type="checkbox" ng-model="meta.autosave"/>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'taxonomies'">
		<th><label><?php esc_html_e( 'Taxonomies', 'meta-box-builder' ); ?></label></th>
		<td>
			<select id="select-term" multiple="multiple" ng-model="meta.taxonomies" ng-options="taxonomy as taxonomy.name for (key, taxonomy) in taxonomies track by taxonomy.slug"></select>
			<?php if ( ! mbb_is_extension_active( 'mb-term-meta' ) ) : ?>
				<p><?php printf( esc_html__( 'Requires %s.', 'meta-box-builder' ), '<a target="_blank" href="https://metabox.io/plugins/mb-term-meta/">MB Term Meta</a>' ); ?></p>
			<?php endif; ?>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'settings_pages'">
		<th><label><?php esc_html_e('Setting Pages', 'meta-box-builder'); ?></label></th>
		<td>
			<select id="select-page" multiple="multiple" ng-model="meta.settings_pages" ng-options="page as page for (key, page) in settings_pages"></select>
			<?php if ( ! mbb_is_extension_active( 'mb-settings-page' ) ) : ?>
				<p><?php printf( esc_html__( 'Requires %s.', 'meta-box-builder' ), '<a target="_blank" href="https://metabox.io/plugins/mb-settings-page/">MB Settings Page</a>' ); ?></p>
			<?php endif; ?>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'user'">
		<th><label></label></th>
		<td>
			<?php if ( ! mbb_is_extension_active( 'mb-user-meta' ) ) : ?>
				<p><?php printf( esc_html__( 'Requires %s.', 'meta-box-builder' ), '<a target="_blank" href="https://metabox.io/plugins/mb-user-meta/">MB User Meta</a>' ); ?></p>
			<?php endif; ?>
		</td>
	</tr>
	<tr class="meta-box-for" ng-show="meta.for == 'comment'">
		<th><label></label></th>
		<td>
			<?php if ( ! mbb_is_extension_active( 'mb-comment-meta' ) ) : ?>
				<p><?php printf( esc_html__( 'Requires %s.', 'meta-box-builder' ), '<a target="_blank" href="https://metabox.io/plugins/mb-comment-meta/">MB Comment Meta</a>' ); ?></p>
			<?php endif; ?>
		</td>
	</tr>

	<tr class="meta-box-for" ng-show="meta.for == 'attachments'">
		<th><label><?php esc_html_e( 'Show in media modal?', 'meta-box-builder' ); ?></th>
		<td>
			<input id="meta-box-default-hidden" type="checkbox" ng-model="meta.media_modal" ng-true-value="'true'" />
		</td>
	</tr>
</table>

<h2 class="title"><?php esc_html_e( 'Custom Table', 'meta-box-builder' ); ?> </h2>
<?php if ( ! mbb_is_extension_active( 'mb-custom-table' ) ) : ?>
	<p><?php printf( esc_html__( 'Requires %s.', 'meta-box-builder' ), '<a target="_blank" href="https://metabox.io/plugins/mb-custom-table/">MB Custom Table</a>' ); ?></p>
<?php endif; ?>
<?php if ( mbb_is_extension_active( 'mb-custom-table' ) ) : ?>
<table class="form-table form-table-for">
	<tr>
		<th><label for="meta-box-table"><?php esc_html_e( 'Use custom table', 'meta-box-builder' ); ?></label></th>
		<td>
			<input id="meta-box-table" type="checkbox" ng-model="meta.checktable" ng-true-value="'true'" />
		</td>
	</tr>
	<tr ng-show="meta.checktable">
		<th><label for="metabox-auto-save"><?php esc_html_e( 'Table name', 'meta-box-builder' ); ?></label></th>
		<td>
			<input id="metabox-table-name" type="text" ng-model="meta.table"/>
		</td>
	</tr>
</table>
<?php endif; ?>

<h2 class="title"><?php esc_html_e( 'Custom Attributes', 'meta-box-builder' ); ?></h2>
(<a class="meta-box-documentation" href="https://docs.metabox.io/extensions/meta-box-builder/#custom-attributes" target="_blank"><?php esc_html_e( 'See documentation', 'meta-box-builder' ); ?></a>)
<table class="form-table">
	<tr>
		<td>
			<table style="max-width: 690px" ng-show="meta.attrs.length > 0">
				<tr ng-repeat="attr in meta.attrs track by $index">
					<td class="col-xs-5" width="45%">
						<input ng-keydown="navigate($event, active.id, $index, 'key')"
							   ng-enter="addMetaBoxAttribute()" focus-on="metabox_key_{{$index}}"
							   type="text" class="form-control col-sm-6 input-sm" ng-model="attr.key"
							   placeholder="Enter key"/>
					</td>

					<td class="col-xs-6" width="45%">
						<input style="width: 100%" type="text" class="form-control col-sm-6 input-sm" ng-model="attr.value" placeholder="Enter value">
					</td>

					<td class="col-xs-1" width="5%">
						<button type="button" class="button" ng-click="removeMetaBoxAttribute($index);"><span class="dashicons dashicons-trash"></span></button>
					</td>
				</tr>
			</table>
			<button type="button" class="button custom-attributes__button" ng-click="addMetaBoxAttribute();"><?php esc_html_e( '+ Attribute', 'meta-box-builder' ); ?></button>
		</td>
	</tr>
</table>

<h2 class="title"><?php esc_html_e( 'Conditional Logic', 'meta-box-builder' ); ?></h2>
<?php if ( ! mbb_is_extension_active( 'meta-box-conditional-logic' ) ) : ?>
	<p><?php printf( esc_html__( 'Requires %s.', 'meta-box-builder' ), '<a target="_blank" href="https://metabox.io/plugins/meta-box-conditional-logic/">Meta Box Conditional Logic</a>' ); ?></p>
<?php endif; ?>
<table class="form-table">
	<tr>
		<td colspan="2">
			<section class="builder-conditional-logic" ng-show="meta.logic">
				<label>
					<?php esc_html_e( 'This conditional logic applies to current Meta Box, for fields conditional logic, please go to each field and set.', 'meta-box-builder' ); ?>
				</label>

				<div class="conditional-logic__description">
					<select ng-model="meta.logic.visibility">
						<option value="visible"><?php _e( 'Visible', 'meta-box-builder' ); ?></option>
						<option value="hidden"><?php _e( 'Hidden', 'meta-box-builder' ); ?></option>
					</select>

					<?php _e( 'when', 'meta-box-builder' ); ?>

					<select ng-model="meta.logic.relation">
						<option value="and"><?php _e( 'All', 'meta-box-builder' ); ?></option>
						<option value="or"><?php _e( 'Any', 'meta-box-builder' ); ?></option>
					</select>

					<?php _e( 'of these conditions match', 'meta-box-builder' ); ?>
				</div>

				<table class="table conditional-logic__table" style="max-width: 690px" ng-show="meta.logic.when.length > 0">
					<tr ng-repeat="item in meta.logic.when track by $index">
						<td width="35%">
							<input type="text" ng-model="meta.logic.when[$index][0]"
								   list="available_fields" placeholder="Select or enter a field...">
						</td>
						<td width="15%">
							<select ng-model="meta.logic.when[$index][1]">
								<option value="=">=</option>
								<option value=">">></option>
								<option value="<">&lt;</option>
								<option value=">=">>=</option>
								<option value="<=">&lt;=</option>
								<option value="!=">!=</option>
								<option value="contains">contains</option>
								<option value="not contains">not contains</option>
								<option value="starts with">starts with</option>
								<option value="not starts with">not starts with</option>
								<option value="ends with">ends with</option>
								<option value="not ends with">not ends with</option>
								<option value="between">between</option>
								<option value="not between">not between</option>
								<option value="in">in</option>
								<option value="not in">not in</option>
								<option value="match">match</option>
								<option value="not match">not match</option>
							</select>
						</td>
						<td width="35%">
							<input type="text" ng-model="meta.logic.when[$index][2]"
								   placeholder="Enter a value...">
						</td>
						<td width="5%">
							<button type="button" class="button"
									ng-click="removeConditionalLogic($index, 'meta');">
								<span class="dashicons dashicons-trash"></span>
							</button>
						</td>
					</tr>
				</table>
			</section>
			<button type="button" class="button conditional-logic__button" ng-click="addConditionalLogic('meta');"><?php esc_html_e( '+ Condition', 'meta-box-builder' ); ?></button>
		</td>
	</tr>
</table>

<div class="extensions" style="margin-top: 30px">
<?php Meta_Box_Show_Hide_Template::show(); ?>
<?php Meta_Box_Include_Exclude_Template::show(); ?>
</div>

<div class="publishing-action">
	<input type="submit" id="bind_submit" name="save_metabox" class="button button-primary menu-save" value="<?php esc_html_e('Save Changes', 'meta-box-builder'); ?>">
</div><!-- END .publishing-action -->
