<?php 
/* 
  #file: admin/view/template/total/sheer_id.tpl
  #name: SheerID
  #version: v1.0 free
  #tested: opencart Version 1.5.1.3
*/
?>
<?php echo $header; ?>

<style type="text/css">
label.checkbox {
	margin-left: 1em;
	white-space: nowrap;
	width: 200px;
	display: block;
	float: left;
}
.offers-table {
	width: 100%;
}
.offers-table td {
	vertical-align: top;
}
</style>

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
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="sheer_id_status">
                <?php if ($sheer_id_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sheer_id_sort_order" value="<?php echo $sheer_id_sort_order; ?>" size="1" /></td>
          </tr>
	      <tr>
            <td><?php echo $entry_access_token; ?></td>
            <td><input type="text" name="sheer_id_access_token" value="<?php echo $sheer_id_access_token; ?>" size="40" /></td>
          </tr>
	      <tr>
            <td><?php echo $entry_mode; ?></td>
            <td><select name="sheer_id_base_url">
                <option value="https://services.sheerid.com" <?php echo $sheer_id_base_url == 'https://services.sheerid.com' ? "selected" : ""; ?>><?php echo $text_mode_production; ?></option>
                <option value="https://services-sandbox.sheerid.com" <?php echo $sheer_id_base_url == 'https://services-sandbox.sheerid.com' ? "selected" : ""; ?>><?php echo $text_mode_sandbox; ?></option>
              </select></td>
          </tr>
		  <tr>
			<td><?php echo $entry_coupons; ?></td>
			<td>
				<table class="offers-table">
					<tr>
						<th style="width: 20%">Coupon</th>
						<th style="width: 80%">Verification Required?</th>
					</tr>
					<?php foreach ($coupons as $coupon) {
						$coupon_id = $coupon['coupon_id'];
						$types = isset($affiliation_type_mappings["affiliation_types-$coupon_id"]) ? $affiliation_type_mappings["affiliation_types-$coupon_id"] : array();
					?>
					<tr>
						<td><?php printf("%s (Code: %s)", $coupon["name"], $coupon["code"]) ?></td>
						<td>
							<label style="font-weight: bold;"><input type="checkbox" class="qualified" <?php if (count($types)) echo "checked" ?>> Require verification</label>
							<div class="checkboxes">
							<?php foreach ($affiliation_types as $affiliation_type) {
								$checked = false !== array_search($affiliation_type, $types);
							?>
								<label class="checkbox"><input type="checkbox" name="affiliation_types-<?php echo $coupon["coupon_id"]; ?>[]" value="<?php echo $affiliation_type ?>" <?php echo $checked ? "checked" : "" ?> /> <?php echo ${"label_$affiliation_type"}; ?></label>
							<?php } ?>
							</div>
						</td>
					</tr>
					<?php } ?>
				</table>
			</td>
		  </tr>
        </table>
      </form>
    </div>
  </div>
</div>

<script>
jQuery(function($){
	$(':checkbox.qualified').change(function(){
		var on = $(this).is(':checked');
		var cboxWrap = $(this).closest('td').find('.checkboxes');
		cboxWrap[on ? 'show' : 'hide']();
		if (!on) {
			cboxWrap.find(':checkbox').removeAttr('checked');
		}
	}).change();
});
</script>

<?php echo $footer; ?>