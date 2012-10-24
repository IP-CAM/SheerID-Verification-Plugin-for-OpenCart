<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_coupon; ?></td>
              <td class="left"><?php echo $entry_information; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { if (array_key_exists('information_id', $module)) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="sheer_id_module[<?php echo $module_row; ?>][coupon_code]">
                  <?php foreach ($coupons as $coupon) { ?>
                  <?php if ($coupon['code'] == $module['coupon_code']) { ?>
                  <option value="<?php echo $coupon['code']; ?>" selected="selected"><?php echo $coupon['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $coupon['code']; ?>"><?php echo $coupon['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
			  <td class="left"><select name="sheer_id_module[<?php echo $module_row; ?>][information_id]">
					<?php foreach ($information as $info) { ?>
						<option value="<?php echo $info["information_id"]; ?>" <?php if($module["information_id"] == $info["information_id"]) echo "selected"; ?>><?php echo $info["title"]; ?></option>
					<?php } ?>
				</select>
				<input type="hidden" name="sheer_id_module[<?php echo $module_row; ?>][position]" value="column_right" />
			  	<input type="hidden" name="sheer_id_module[<?php echo $module_row; ?>][layout_id]" value="11" />
				</td>
              <td class="left"><select name="sheer_id_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="sheer_id_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } } ?>
          <tfoot>
            <tr>
              <td colspan="6"></td>
              <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="sheer_id_module[' + module_row + '][coupon_code]">';
	<?php foreach ($coupons as $coupon) { ?>
	html += '      <option value="<?php echo addslashes($coupon['id']); ?>"><?php echo addslashes($coupon['title']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="sheer_id_module[' + module_row + '][information_id]">';
	<?php foreach ($information as $i) { ?>
	html += '      <option value="<?php echo $i['information_id']; ?>"><?php echo addslashes($i['title']); ?></option>';
	<?php } ?>
	html += '    </select>';
	html += '	 <input type="hidden" name="sheer_id_module[' + module_row + '][position]" value="column_right" />';
  	html += '	 <input type="hidden" name="sheer_id_module[' + module_row + '][layout_id]" value="11" />';
	html += '	 </td>';
	html += '    <td class="left"><select name="sheer_id_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="sheer_id_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script> 
<?php echo $footer; ?>