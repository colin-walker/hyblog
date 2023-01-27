<?php if(!defined('APP_RAN')){ die(); } 

require_once('config.php');

$target_dir = $_SERVER['DOCUMENT_ROOT'];
$auth = file_get_contents($target_dir . '/session.php');

?>

<input type="checkbox" style="display: none;" id="menu_tray_checkbox"/>
<label for="menu_tray_checkbox" id="bottom_toggle" class="dict" accesskey="t" style="position: fixed; bottom: 35px; z-index: 9;" >
	<picture style="position: fixed; bottom: 50px; left: calc(50vw - 307px) !important;">
    	<source srcset="<?php echo constant('BASE_URL'); ?>images/feed_list_dark.png" media="(prefers-color-scheme: dark)">
    	<img src="<?php echo constant('BASE_URL'); ?>images/feed_list_light.png" alt="menu"/>
    </picture>
</label>

<div id="menu_tray" style="overflow-y: scroll; transition: transform .4s ease-in-out; position: fixed; top: 0px; bottom: 0px; left: -395px; height: auto; width: 375px; z-index: 99; padding: 20px 20px 20px 0px; line-height: 1.6em; text-align: right; margin-bottom: 0px;">


<div class="nameSpan" style="font-weight: bold;">
  <nav>
	<a href="<?php echo BASE_URL; ?>" style="font-size: 1.2em;">Home</a><br/><br/>
    <a href="<?php echo BASE_URL; ?>feeds/" title="Subscribe to regular & daily RSS feeds">Feeds</a>
    <br/>
    <a href="<?php echo BASE_URL; ?>about/">ABOUT</a>
    <br/>
    <a href="<?php echo BASE_URL; ?>colophon/">COLOPHON</a>
    <br/>
    <br/>
    <a href="https://colinwalker.blog/blog/">(b)log-In</a>
    
<?php
    if (isset($_SESSION['hauth']) && $_SESSION['hauth'] == $auth) {
?>		
        <a id="admin" href="<?php echo BASE_URL; ?>admin/" style="position: absolute; bottom: 20px; left: 345px;">
            <picture>
                <source srcset="<?php echo constant('BASE_URL'); ?>images/admin_dark.png" media="(prefers-color-scheme: dark)">
                <img src="<?php echo constant('BASE_URL'); ?>images/admin_light.png" style="width: 18px;" />
            </picture>
        </a>
<?php
    } else {
?>
        <div style="text-transform: lowercase; font-size: 20px; position: absolute; bottom: 23px; left: 345px;"><a accesskey="l" href="login/">ⓗ</a></div>
<?php
    }
?>
</nav>
</div>
        
</div>

<!-- div class="top_fade" style="position: fixed; top: 0px; left: 0px; z-index: 2; width: 100%; background-image: linear-gradient(0deg, rgba(255,255,255,0), #ffffff); height: 30px;" -->
<!--/div -->

<div class="bottom_fade" style="position: fixed ; bottom: 40px; left: 0px; z-index: 2; width: 100%; background-image: linear-gradient(rgba(255,255,255,0), #ffffff); height: 48px;">
</div>
<div class="linksDiv day-links bottom_solid" style="margin-bottom: 0px; position: fixed ; bottom: 0px; left: 0px; z-index: 2; text-align: center; width: 100%; background: #ffffff; height: 40px;">
    
</div>

<div class="h-card p-author vcard author">
    <img class="u-photo" src="<?php echo constant('AVATAR'); ?>" alt="<?php echo constant("NAME"); ?>"/>
    <a class="u-url u-uid" rel="me" href="<?php echo constant('BASE_URL'); ?>"><?php echo constant("NAME"); ?></a>
    <a rel="me" class="u-email" href="mailto:<?php echo constant("MAILTO"); ?>"><?php echo constant("MAILTO"); ?></a>
    <p class="p-note"></p>
</div>
    
<?php

if($pageMobile) {
?>
    <style>
    	@media screen and (max-width: 767px) {

        	#page {
            	min-height: calc(100vh - <?php echo $pageMobile; ?>px) !important;
        	}
    	}
	</style>
<?php
}

if($pageDesktop) {
?>
    <style>
      #menu_tray {
		  	background: rgba(225,225,225,0.95);
	  	}
	  	
	  	#menu_tray a {
	  		color: var(--light-links) !important;
	  		font-weight: bold;
	  		text-decoration: none;
	  	}
	  	
	  	#menu_tray {
			scrollbar-width: thin;
	        scrollbar-color: #999 rgba(225,225,225,0.95);
	  	}
			
		#menu_tray::-webkit-scrollbar {
			width: 1px;
			height: 0px;
		}
		
		#menu_tray::-webkit-scrollbar-track {
		    background: rgba(225,225,225,0.95);
		}
		
		#menu_tray::-webkit-scrollbar-thumb {
		    background: #999;
		}

		#menu_tray_checkbox:checked ~ #menu_tray {
			-webkit-transform: translateX(350px);
			-ms-transform: translateX(350px);
			transform: translateX(350px);
		}
		
		.nameSpan a {
		  margin-right: 0px;
		}
		
		.top_fade, .bottom_fade, .bottom_solid {
         /*pointer-events: none;*/
		}
		
		@media screen and (prefers-color-scheme: dark) {
    		.top_fade {
                background-image: linear-gradient(0deg, rgba(34,34,34,0), #222) !important;
            }
		  
		    .bottom_fade {
                background-image: linear-gradient(rgba(34,34,34,0), #222) !important;
            }
  
            .bottom_solid {
                background: #222 !important;
            }
	    	
		  	#menu_tray {
                background: rgba(75,75,75,0.95);
		  	}
		  	
		  	#menu_tray a {
                color: var(--dark-links) !important;
		  	}
		  	
		  	#menu_tray {
		        scrollbar-color: #999 rgba(75,75,75,0.95);
		  	}
		
			#menu_tray::-webkit-scrollbar-track {
			    background: rgba(75,75,75,0.95);
			}
		}
		
		@media screen and (max-height: 400px) and (orientation: landscape) {
		    #dictionary {
		        top: 23px;
		        left: 178px !important;
		    }
		    
		    #labels {
		        top: 73px;
		        left: 178px !important;
		    }
		    
		    #admin {
		        top: 121px;
		        left: 178px !important;
		    }
		    
		    .dict picture, .dict img {
                left: 23px !important;
            }
		    
    		#menu_tray_checkbox:checked ~ #menu_tray {
    			-webkit-transform: translateX(225px);
    			-ms-transform: translateX(225px);
    			transform: translateX(225px);
    		}
		    
		}
		  
		@media screen and (max-width: 767px) {
            .dict picture, .dict img {
                left: 23px !important;
            }
	      
    		#menu_tray_checkbox:checked ~ #menu_tray {
    			-webkit-transform: translateX(225px);
    			-ms-transform: translateX(225px);
    			transform: translateX(225px);
    		}
		}
		
        @media screen and (min-width: 768px) {

        	#page {
            	min-height: calc(100vh - <?php echo $pageDesktop; ?>px) !important;
        	}
    	}
        
        .paging-navigation a {
            position: relative;
            z-index: 1;   
        }

        .paging-navigation {
            margin: 2.0em auto 6em !important;
        }     
    </style>
<?php
}

?>


<script type="text/javascript">

document.getElementById("page").addEventListener("click", hide_menu);

if (document.body.contains(document.getElementById("wrapper"))) {
    document.getElementById("wrapper").addEventListener("click", hide_menu);
}

function hide_menu() {
  var style = window.getComputedStyle(menu_tray);
  var matrix = style['transform'] || style.webkitTransform || style.mozTransform;
  var posx = matrix.split(',')[4];
  if (document.getElementById('menu_tray_checkbox').checked && posx != 0) {
    document.getElementById('menu_tray_checkbox').checked = false;
  }
}

</script>
</body>
</html>