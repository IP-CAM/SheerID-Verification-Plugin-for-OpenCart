<?php
if ($config) {
	echo "You better be a " . implode(" or ", $config['affiliation_types']);
	
	if (isset($this->session->data['verify_error'])) {
		$err = $this->session->data['verify_error'];
		unset($this->session->data['verify_error']);
		echo "<p>$err</p>";
	}
?>
<form method="POST" action="index.php?route=common/sheer_id">
	<p>
		<label><input type="radio" name="verified" value="1" /> Verified</label>
		<label><input type="radio" name="verified" value="0" /> Not Verified</label>
		
		<input type="text" name="coupon_id" value="<?php echo $config['coupon_id'] ?>" />
	</p>
	<button type="submit">Save</button>
</form>


<?php }