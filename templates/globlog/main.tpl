<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{l_langcode}" lang="{l_langcode}" dir="ltr">
<head>
<meta http-equiv="Content-type" content="text/html; charset={l_encoding}" />
<meta http-equiv="Content-language" content="{l_langcode}" />
<meta name="Generator" content="{what} {version}" />
<meta name="Document-State" content="dynamic" />
{htmlvars}
<link href="{tpl_url}/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="{admin_url}/includes/js/functions.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/ajax.js"></script>
<title>{titles}</title>
</head>
<body>
[sitelock]
<div id="loading-layer"><img src="{tpl_url}/images/loading.gif" alt="" /></div>



<div class="min_width">
	<div class="main">
        <!--header -->
        <div id="header">
            <div class="indent">
                <div class="logo">
                    <h1><a href="/">Логотип</a></h1>
                    <div><span>Слоган сайта</span></div>
                </div>
            </div>
            <div class="side_left_menu">
            	<div class="side_right_menu">
                	<div class="side_top_menu">
                    	<div class="left_top_menu">
                        	<div class="right_top_menu">
                            	<div class="block_search">
                                    {search_form}
									
                                </div>
                            	<div class="menu">
                                	
									  {personal_menu}
                                    
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--header end-->
        <!--content -->
        <div class="content">
            <div class="side_left">
            	<div class="side_right">
                	<div class="side_top">
                    	<div class="side_bot">
                        	<div class="left_top">
                            	<div class="right_top">
                                	<div class="left_bot">
                                    	<div class="right_bot">
                                        	<div class="indent">
                                                <div class="w100">
												
												<div class="column_center">
    <div class="indent_center">
        <div class="side_left_2">
            <div class="side_right_2">
                <div class="side_top_2">
                    <div class="side_bot_2">
                        <div class="left_top_2">
                            <div class="right_top_2">
                                <div class="left_bot_2">
                                    <div class="right_bot_2">
                                        
										<div class="indent_center_2">
											                                   
											
											
											
											
										
										
										 {mainblock}
											
											</div>
										
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
</div>

<div class="side_bar">
    <div class="inside">
    <div id="statusbar">
			        </div>
        <div class="widget_style" id="categories">
            <div class="side_left_3">
                <div class="side_right_3">
                    <div class="side_top_3">
                        <div class="side_bot_3">
                            <div class="left_top_3">
                                <div class="right_top_3">
                                    <div class="left_bot_3">
                                        <div class="right_bot_3">
                                            <h2>Категории</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul>
			{categories}
			
            </ul>
		</div>
       
            
			{plugin_favorites}
			{plugin_calendar}
            {plugin_popular}
			{plugin_archive}
			
						<!-- inc begin -->
[isplugin voting]
			<div class="widget_style">
            <div class="side_left_3">
                <div class="side_right_3">
                    <div class="side_top_3">
                        <div class="side_bot_3">
                            <div class="left_top_3">
                                <div class="right_top_3">
                                    <div class="left_bot_3">
                                        <div class="right_bot_3">
                                            <h2>Наш опрос</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           {voting}
		</div>

[/isplugin]
<!-- inc end -->

        
           </div>
</div>                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--content end-->
        <!--footer -->
          <div id="footer">
           Система управления сайтом: <a href="http://ngcms.ru/" target="_blank" title="Next Generation CMS">Next Generation CMS</a> [v0.9.0]<br />
		   Оптимизация: <a href="http://dorogow.info">d7p4x</a>
        </div>
        <!--footer end-->	
    </div>
</div>	
</body></html>
[/sitelock]
