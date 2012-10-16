<?php
if ($config) {
	if (isset($this->session->data['verify_error'])) {
		$err = $this->session->data['verify_error'];
		unset($this->session->data['verify_error']);
		echo "<p>$err</p>";
	}
?>
<style type="text/css">
.verify-form label {
	width: 200px;
	display: block;
	float: left;
	margin-top: 0.5em;
}
</style>
<?php
	$MONTHS = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	
	function renderField($field, $type="text", $label=null, $value=null) {
		$MONTHS = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	?>
	<p>
		<label for="<?php echo $field; ?>"><?php echo $label ? $label : $field; ?>:</label>
		<?php if ($type == "date") {
			$month_val = 0;
			$day_val = 0;
			$year_val = 0;
			if ($value == 'now') {
				$month_val = date('m');
				$day_val = date('d');
				$year_val = date('Y');
			}
		?>
			<select id="<?php echo $field; ?>_month" name="<?php echo $field; ?>_month">
				<option value="">Month</option>
				<?php for ($i=0; $i<count($MONTHS); $i++) { ?><option value="<?php echo $i+1; ?>" <?php if ($i+1 == $month_val) echo "selected"; ?>><?php echo $MONTHS[$i]; ?></option><?php } ?>
			</select>
			<select id="<?php echo $field; ?>_day" name="<?php echo $field; ?>_day">
				<option value="">Day</option>
				<?php for ($i=1; $i<=31; $i++) { ?><option value="<?php echo $i; ?>" <?php if ($i == $day_val) echo "selected"; ?>><?php echo $i; ?></option><?php } ?>
			</select>
			<select id="<?php echo $field; ?>_year" name="<?php echo $field; ?>_year">
				<option value="">Year</option>
				<?php for ($i=date("Y")/1; $i >= 1900; $i--) { ?><option value="<?php echo $i; ?>" <?php if ($i == $year_val) echo "selected"; ?>><?php echo $i; ?></option><?php } ?>
			</select>
		<?php } else { ?>
			<input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="" />
		<?php } ?>
	</p>
	<?php }
?>
<form method="POST" action="index.php?route=common/sheer_id" class="verify-form">
	<?php if ($org_type) { ?>
		<p>
			<label for="organization">Organization:</label>
			<input type="text" id="organization" name="organizationId" />
		</p>
	<?php } ?>
	
	<?php foreach ($fields as $f) {
		renderField($f, strpos($f, "_DATE") !== false ? "date" : "text", ${"field_$f"}, $f=="STATUS_START_DATE"?"now":null);
	} ?>
	
	<input type="hidden" name="coupon_code" value="<?php echo $config['coupon_code'] ?>" />
	<button type="submit">Verify</button>
</form>

<?php if ($org_type) { ?>
	<script src="https://services.sheerid.com/jsapi/SheerID.js"></script>
	<script>
	SheerID.load('combobox', '1.0', {
		config: {
			input: document.getElementById('organization'),
			allowName: true,
			proxyName: 'organizationName',
			params: {
				type: '<?php echo $org_type; ?>'
			}
		}
	});
	</script>
	
<?php } // end if $org_type

} // end if $config