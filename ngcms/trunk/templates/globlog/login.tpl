
										
										<div class="title">
                                         
                                                <div class="clear"></div>

                                                <div class="side_left_5">
                                                    <div class="side_right_5">
                                                        <div class="side_top_5">
                                                            <div class="side_bot_5">
                                                                <div class="left_top_5">
                                                                    <div class="right_top_5">
                                                                        <div class="left_bot_5">
                                                                            <div class="right_bot_5">
                                                                                <h3><font color="#000000">{l_login.title}</font></h3>

                                                                            </div>
																			
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text_box">
											<form name="login" method="post" action="{form_action}">
				<input type="hidden" name="redirect" value="{redirect}"/>
				<table width="100%">
				[error]<tr><td colspan="2" align="center" style="background-color: red; color: white; font-weight: bold;">{l_login.error}</td></tr>[/error]
				[banned]<tr><td colspan="2" align="center" style="background-color: red; color: white; font-weight: bold;">{l_login.banned}</td></tr>[/banned]
				<tr><td width=70>{l_login.name}:</td><td><input type="text" name="username" /></td></tr>
				<tr><td width=70>{l_login.password}:</td><td><input type="password" name="password" /></td></tr>
				<tr><td colspan="2"><input type="submit" value="{l_login.submit}"/></td></tr>
				</table>
				</form>
																									
<div class="clear"></div>
                                            </div>
