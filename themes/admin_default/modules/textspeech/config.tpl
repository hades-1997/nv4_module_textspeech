<!-- BEGIN: main -->
<div id="users">
	<form action="{FORM_ACTION}" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col style="width: 260px" />
					<col/>
				</colgroup>
				<tfoot>
					<tr>
						<td colspan="2"><input type="submit" name="submit" value="{LANG.config_save}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
                        <td>{LANG.voice}</td>
                        <td>
							 <div class="col-sm-8">
								 <select class="form-control" name="voice">
									<!-- BEGIN: loop -->
									<option value="{VOICE.key}" {VOICE.selected}>{VOICE.title}</option>
									<!-- END: loop -->
								</select>
								</div>
                        </td>
                    </tr>
					
					<tr>
                        <td>{LANG.speed}</td>
                        <td>
							 <div class="col-sm-3">
								 <select class="form-control" name="speed">
									<!-- BEGIN: speed -->
									<option value="{SPEED.key}" {SPEED.selected}>{SPEED.title}</option>
									<!-- END: speed -->
								</select>
								</div>
                        </td>
                    </tr>
					<tr>
						<td>{LANG.token}</td>
						<td><input class="form-control w200" name="token" value="{DATA.token}" /><span class="help-block">{LANG.config_tokenviettel_note}</span></td>
					</tr>
					<tr>
						<td>{LANG.config_facebookapi}</td>
						<td><input class="form-control w200" name="facebookapi" value="{DATA.facebookapi}" /><span class="help-block">{LANG.config_facebookapi_note}</span></td>
					</tr>
                    <tr>
                        <td>{LANG.socialbutton}</td>
                        <td>
                            <!-- BEGIN: socialbutton -->
                            <div><label><input type="checkbox" name="socialbutton[]" value="{SOCIALBUTTON.key}"{SOCIALBUTTON.checked}> {SOCIALBUTTON.title}</label></div>
                            <!-- END: socialbutton -->
                        </td>
                    </tr>
					<tr>
						<td>{LANG.setting_copy_page}</td>
						<td><input type="checkbox" value="1" name="copy_page"{COPY_PAGE}/></td>
					</tr>
					<tr>
						<td>{LANG.config_alias_lower}</td>
						<td><input type="checkbox" value="1" name="alias_lower"{ALIAS_LOWER}/></td>
					</tr>

				</tbody>
			</table>
		</div>
	</form>
</div>
<!-- END: main -->