<?php if ( $settings_form ) : ?>
<?php echo form_open(
		'C=addons_extensions&M=extension_settings&file=mx_cp_notice',
		'',
		array( "file" => "mx_cp_notice" )
	);


$theme_data = array (
		'flat' => 'Flat',
		'future' => 'Future',
		'block' => 'Block',
		'air' => 'Air',
		'ice' => 'Ice'
	);

$location_h = array (
				'' => 'Center',
				'left' => 'Left',
				'right' => 'Right'
);

$location_v = array (
				'bottom' => 'Bottom',
				'top' => 'Top'
);

if ( ! function_exists( 'print_var' ) ) {
	function print_var( $var, $row, $index, $default = '' ) {
		return ( isset( $var[$row][$index] ) ) ? $var[$row][$index] : $default;
	}
}




?>

<table class="mainTable padTable"  border="0" cellpadding="0" cellspacing="0">
<tbody>



<tr>
<th colspan="2"><?=lang( 'notice_settings' )?></th>

</tr>
</tbody> <?php endif; ?>
<tbody id="cond_table">
<tr>
<td class=""><?=lang("theme")?></td>
<td class=""><?=form_dropdown( $input_prefix . '[theme]', $theme_data, (isset( $settings['theme'])) ? $settings['theme'] : 'air')?></td>
</tr>
<tr>
<td class=""><?=lang("location")?></td>
<td class=""><?=form_dropdown( $input_prefix . '[location_h]', $location_h, (isset( $settings['location_h'])) ? $settings['location_h'] : 'top').
form_dropdown( $input_prefix . '[location_v]', $location_v, (isset( $settings['location_v'])) ? $settings['location_v'] : 'right')?></td>
</tr>
<tr>
<td class=""><?=lang("showCloseButton")?></td>
<td class="">
	<select name="<?=$input_prefix ?>[showCloseButton]">
		<option value="true" <?=(isset($settings['showCloseButton'])) ? (($settings['showCloseButton'] == 'true') ? " selected='selected'" : "" ) : "" ?>><?=lang('show') ?></option>
		<option value="false" <?=(isset($settings['showCloseButton'])) ? (($settings['showCloseButton'] == 'false') ? " selected='selected'" : "") : "" ?>><?=lang('hide') ?></option>
	</select>
</td>
</tr>
<tr>
<td class=""><?=lang("hideAfter")?></td>
<td class="">
		<input style="width: 100%;" name="<?=$input_prefix?>[hideAfter]" id="" value="<?=((isset($settings['hideAfter'])) ? $settings['hideAfter'] : '10')?>" size="20" maxlength="120" class="input" type="text">
</td>
</tr>






</tbody></table>
<p class="centerSubmit"><input name="edit_screen_size" value="<?php echo lang( 'save_extension_settings' ); ?>" class="submit" type="submit"></p>
<?php echo form_close(); ?>


