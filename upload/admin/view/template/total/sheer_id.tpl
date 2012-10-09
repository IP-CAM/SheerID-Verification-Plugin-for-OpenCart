<?php 
/* 
  #file: admin/view/template/total/sheer_id.tpl
  #name: SheerID
  #version: v1.0 free
  #tested: opencart Version 1.5.1.3
*/
?>
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
			<td><?php echo $entry_coupons; ?></td>
			<td>
				<table>
					<tr>
						<th>Coupon</th>
						<th>Affiliation Type(s)</th>
					</tr>
					<?php foreach ($coupons as $coupon) {
						$coupon_id = $coupon['coupon_id'];
						$types = isset($affiliation_type_mappings["affiliation_types-$coupon_id"]) ? $affiliation_type_mappings["affiliation_types-$coupon_id"] : array();
					?>
					<tr>
						<td><?php printf("%s (Code: %s)", $coupon["name"], $coupon["code"]) ?></td>
						<td>
							<?php foreach ($affiliation_types as $affiliation_type) {
								$checked = false !== array_search($affiliation_type, $types);
							?>
							<label><input type="checkbox" name="affiliation_types-<?php echo $coupon["coupon_id"]; ?>[]" value="<?php echo $affiliation_type ?>" <?php echo $checked ? "checked" : "" ?> /> <?php echo $affiliation_type; ?></label>
								<?php } ?>
							</select>
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
<?php echo $footer; ?>