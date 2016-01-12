
<div id="nav-top" class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-top">
        <div class="container">
        	<div class="row-fluid">
            	<div class="span2">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div id="nav-logo"><a href="/"><img src="/images/logo_sm.png" width="130" /></a></div>
	            </div>
            	<div class="span6 text-center">
                    <div class="navbar-search">
                        <div class="input-append">
                          <input id="fotoluvSearch" type="text" required="required" class="input-xxlarge" onkeypress="userSearch();" placeholder="Search for people, jobs, location and more....">
                          <button class="btn" id="fotoluvSearch-btn" type="button" onclick="userSearch();"><i class="icon-search"></i></button>
                        </div>
                     </div>
				</div>
                <div class="span4">
                	<div id="logo-tag" class="pull-right slogan"><img src="/images/share-the-love.png" /></div>
                </div>
			</div>
        </div>
    </div>
    <div id="nav-sub" class="navbar-inner" style="z-index: 100; position: absolute;">
        <div class="container">
        	<div class="nav-collapse collapse">
                <ul class="nav">
                    <li id="nav_portfolio"><a href="/portfolio/">Portfolio</a></li>
                    <li id="nav_profile"><a href="/portfolio/profile">Profile</a></li>
                    <li id="nav_network"><a href="/portfolio/network">Network <span id="nav-invite-alert" class="badge badge-important"><?php echo $_SESSION['user_invites'] ?></span></a></li>
                    <li id="nav_profile"><a href="/portfolio/boards">Luvs</a></li>
                </ul>
                <ul class="nav pull-right">
                	<li id="nav_messages"><a href="/portfolio/messages">Messages <span id="messages-updates" class="badge badge-important"></span></a></li>
                    <li id="notification-updates-nav" class="hide"><a href="#" id="notification-updates-link" onclick="showNotifications();">Updates <span id="notifications-updates" class="badge badge-important"></span> <i id="notification-caret" class="icon-caret-up"></i></a></li>
                    <li id="nav_account" class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/portfolio/account">View Account</a></li>
                            <li><a href="/portfolio/invites">Send Invite</a></li>
                            <li><a href="/logout">Log Out</a></li>
                        </ul>
                    </li>
                 </ul>
                
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<div id="QuickSearchResults" class="hide" onclick="hideSearch();">
    <div class="container">
        <div class="quick-search">
            <div id="searchResults">
            </div>
        </div>
    </div>
</div>
<div id="Notifications" class="hide" onclick="hideNotifications();">
    <div class="container">
        <div class="notifications">
            <div id="NotificationList">
            </div>
        </div>
    </div>
</div><!-- Modal -->
<div id="quick-send" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove" style="font-size: 12px;"></i></button>
    <h3 id="myModalLabel">Send <span id="quick-send-fullname"></span> a message</h3>
  </div>
  <div class="modal-body">
  	<textarea id="quick-send-text" style="width: 100%; height: 150px;"></textarea>
  </div>
  <div class="modal-footer">
    <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-danger" onclick="quickSendSubmit();">Send Message</button>
  </div>
</div>
