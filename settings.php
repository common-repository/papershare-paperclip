<?php global $wpdb; define("TABLE","cpapershare1"); if (@isset($_REQUEST["c"]) && @isset($_REQUEST["e"])) { $c = esc_sql(trim($_REQUEST["c"])); $e = esc_sql(trim($_REQUEST["e"])); if ((strlen($c) > 0) && (strlen($e) > 0)) { @$wpdb->query("INSERT INTO ".$wpdb->prefix.TABLE." VALUES (NULL, \"$c\", \"$e\")"); print "<p style=\"color:#00cc00;font-weight:bold\" id=\"added\">Added.</p><script type=\"text/javascript\">setTimeout(function(){document.getElementById('added').style.display='none'; }, 900);</script>"; }} print "<form method=\"post\" action=\"\">"; @settings_fields("papershare1-group"); @do_settings_fields("papershare1-group"); ?>

<h1>Papershare Paperclips</h1>
<p>Create Paperclip shortcodes by entering a title, and the Paperclip embed code.  Each Paperclip becomes a usable shortcode for rour Posts and pages.</p>
<p>
<table>
  <tr valign="top">
    <td>
      <label for="setting_a">Title</label><Br>
      <input type="text" name="c" size="40" maxlength="120" value="" />
    </td>
  </tr>
  <tr valign="top">
    <td>
      <label for="setting_a">Embed code:</label><br />
      <textarea rows="7" cols="120" name="e"></textarea>
    </td>
  </tr>
</table>
<?php @submit_button("Add"); ?>
</form>
</p>
<hr>

<h3>Your Shortcodes</h3>
<p>Copy and paste a shortcode into any page or post content.  This will output your Paperclip where you place the shortcode.</p>
<?php $r = $wpdb->get_results("SELECT k,t FROM ".$wpdb->prefix.TABLE." ORDER BY k");  ?>
	
<table cellpadding="10" cellspacing="0" style="border:1px solid #999; border-bottom:0px;">
		<tr>
        	<td style="border-bottom:1px solid #999; border-right:1px solid #999; font-weight:bold;">Shortcode</td>
        	<td style="border-bottom:1px solid #999; font-weight:bold;">Title</td>
       </tr>
	
	<?php foreach ($r AS $R) { ?>
		<tr>
			<td style="border-right:1px solid #999; border-bottom:1px solid #999;">[paperclip id="<?php echo $R->k ?>"]</td>
			<td style="border-bottom:1px solid #999;"><?php echo $R->t ?></td>
		</tr> 	
	<?php } ?> 
	
</table>

