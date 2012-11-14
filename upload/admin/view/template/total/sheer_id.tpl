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
	padding: 0.3em 0.3em 0;
}
.offers-table tr.odd td {
	background-color: #efefef;
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
            <td>
				<?php if ($sheer_id_access_token) { ?>
					<span class="token-display">
						<?php echo substr($sheer_id_access_token, 0, 4) . str_repeat("*", strlen($sheer_id_access_token) - 4); ?>
						<a class="link-token-edit" href="javascript:">Edit</a>
					</span>
					<span style="display: none;" class="token-edit">
				<?php } ?>
				<input type="text" name="sheer_id_access_token" value="<?php echo $sheer_id_access_token; ?>" size="40" />
				<?php if ($sheer_id_access_token) { ?>
					</span>
					<script>
					jQuery(function($){
						$('.link-token-edit').click(function(){
							$('.token-edit').show();
							$('.token-display').hide();
						});
					});
					</script>
				<?php } ?>
			</td>
          </tr>
	      <tr>
            <td><?php echo $entry_mode; ?></td>
            <td><select name="sheer_id_base_url">
                <option value="https://services.sheerid.com" <?php echo $sheer_id_base_url == 'https://services.sheerid.com' ? "selected" : ""; ?>><?php echo $text_mode_production; ?></option>
                <option value="https://services-sandbox.sheerid.com" <?php echo $sheer_id_base_url == 'https://services-sandbox.sheerid.com' ? "selected" : ""; ?>><?php echo $text_mode_sandbox; ?></option>
              </select>
			  <small style="margin-left: 1em">Sandbox mode is used for testing purposes.  More information is available by visiting the <a href="http://developer.sheerid.com">SheerID Developer Center</a>.</small>
			</td>
          </tr>
		  <tr>
            <td><?php echo $entry_allow_uploads; ?></td>
            <td><select name="sheer_id_allow_uploads">
                <option value="true" <?php echo $sheer_id_allow_uploads ? "selected" : ""; ?>>Yes</option>
                <option value="" <?php echo !$sheer_id_allow_uploads ? "selected" : ""; ?>>No</option>
              </select>
			  <small style="margin-left: 1em">If uploads are allowed, your users will be prompted to upload proof of affiliation if automated verification fails.</small>
			</td>
          </tr>
		  <tr>
			<td><?php echo $entry_coupons; ?></td>
			<td>
				<table class="offers-table">
					<tr>
						<th style="width: 20%">Coupon</th>
						<th style="width: 80%">Verification Required?</th>
					</tr>
					<?php
					$i = 0;
					foreach ($coupons as $coupon) {
						$coupon_code = $coupon['code'];
						$offer = isset($offers["offer-$coupon_code"]) ? $offers["offer-$coupon_code"] : array();
						$types = isset($offer["affiliation_types"]) ? $offer["affiliation_types"] : array();
					?>
					<tr class=<?php echo $i++ % 2 == 0 ? "even" : "odd"; ?>>
						<td><?php printf("%s (Code: %s)", $coupon["name"], $coupon["code"]) ?></td>
						<td>
							<label style="font-weight: bold;"><input type="checkbox" class="qualified" <?php if (count($types)) echo "checked" ?>> Require verification</label>
							<div class="checkboxes">
								<?php foreach ($affiliation_types as $affiliation_type) {
									$checked = false !== array_search($affiliation_type, $types);
								?>
									<label class="checkbox"><input type="checkbox" name="offer[offer-<?php echo $coupon["code"]; ?>][affiliation_types][]" value="<?php echo $affiliation_type ?>" <?php echo $checked ? "checked" : "" ?> /> <?php echo ${"label_$affiliation_type"}; ?></label>
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