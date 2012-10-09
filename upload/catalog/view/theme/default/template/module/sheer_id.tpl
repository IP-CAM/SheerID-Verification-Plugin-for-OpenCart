<?php
if ($config) {
	if (isset($this->session->data['verify_error'])) {
		$err = $this->session->data['verify_error'];
		unset($this->session->data['verify_error']);
		echo "<p>$err</p>";
	}
	
	$org_type = 'university';
	$MONTHS = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	
	$fields = array("FIRST_NAME", "LAST_NAME", "BIRTH_DATE");
	
	function renderField($field, $type="text") {
		$MONTHS = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	?>
	<p>
		<label for="<?php echo $field; ?>"><?php echo $field; ?></label>
		<?php if ($type == "date") { ?>
			<select id="<?php echo $field; ?>_month" name="<?php echo $field; ?>_month">
				<option value="">--</option>
				<?php for ($i=0; $i<count($MONTHS); $i++) { ?><option value="<?php echo $i+1; ?>"><?php echo $MONTHS[$i]; ?></option><?php } ?>
			</select>
			<select id="<?php echo $field; ?>_day" name="<?php echo $field; ?>_day">
				<option value="">--</option>
				<?php for ($i=1; $i<=31; $i++) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
			</select>
			<select id="<?php echo $field; ?>_year" name="<?php echo $field; ?>_year">
				<option value="">--</option>
				<?php for ($i=date("Y")/1; $i >= 1900; $i--) { ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?>
			</select>
		<?php } else { ?>
			<input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="" />
		<?php } ?>
	</p>
	<?php }
?>
<form method="POST" action="index.php?route=common/sheer_id">
	<?php if ($org_type) { ?>
		<p>
			<label for="organization">Organization:</label>
			<input type="text" id="organization" name="organizationId" />
		</p>
	<?php } ?>
	
	<?php foreach ($fields as $f) {
		renderField($f, strpos($f, "_DATE") !== false ? "date" : "text");
	} ?>
	
	<input type="hidden" name="coupon_id" value="<?php echo $config['coupon_id'] ?>" />
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