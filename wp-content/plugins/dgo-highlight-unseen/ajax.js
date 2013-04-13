jQuery(document).ready(function($) {
	// init the class, set things in motion
	dgohighlightunseenUpdater.init();
});

var dgohighlightunseenUpdater = {};
dgohighlightunseenUpdater.init = function(){
	dgohighlightunseenUpdater.storedOnclick = null;
	dgohighlightunseenUpdater.notification = null;
	dgohighlightunseenUpdater.titleNotificationTimer = null;
	dgohighlightunseenUpdater.timer = setInterval(function(){dgohighlightunseenUpdater.ajaxUpdate(true);},1*60*1000);
	dgohighlightunseenUpdater.timeout = setTimeout(function(){dgohighlightunseenUpdater.ajaxUpdate(false);},10);
	
	// add item to user action admin bar menu, to request desktop notifications
	if(window.webkitNotifications){

		jQuery('#wp-admin-bar-user-actions')
			.append('<li id="dgohighlightunseen-requestnotificationpermission"><a href="#requestNotificationPermission" class="ab-item" >Desktop Notifications</a></li>');
		jQuery('#dgohighlightunseen-requestnotificationpermission').bind('click.DGOHighlightUnseenNotificationPermissionRequest', function() {
					permissionCode = window.webkitNotifications.checkPermission();
					switch(permissionCode){
						case 0:			//PERMISSION_ALLOWED
							if(confirm('Desktop Notification are turned on, want to turn them off?')){
								window.location.href="http://www.phplivesupport.com/help_desk.php?docid=18";	
							};
							break;
						case 1: 		//PERMISSION_NOT_ALLOWED
							window.webkitNotifications.requestPermission();
							break; 
						case 2:			//PERMISSION_DENIED
							if(confirm('Notification Permission is denied, do you want to allow?')){
								window.location.href="http://www.phplivesupport.com/help_desk.php?docid=18";	
							};
							break;
					}
					return false; // cancel default link action
				});
	}
}

dgohighlightunseenUpdater.showTitleNotification = function(sText){
	var isOldTitle = true;
	var oldTitle = document.title;
	var newTitle = sText;
	function changeTitle() {
	    document.title = isOldTitle ? oldTitle : newTitle;
	    isOldTitle = !isOldTitle;
	}
	function clear(){
		clearInterval(dgohighlightunseenUpdater.titleNotificationTimer);
	    jQuery("title").text(oldTitle);
	}
	dgohighlightunseenUpdater.titleNotificationTimer = setInterval(changeTitle, 700);

	setTimeout(clear,50000);
	jQuery(window).focus(function () {
	    clear();
		console.log('window.focus()');
	});
}
dgohighlightunseenUpdater.showDesktopNotification = function(sTitle, sContent, sHref){
	if (window.webkitNotifications && window.webkitNotifications.checkPermission() == 0) { // 0 is PERMISSION_ALLOWED
		//imageUrl = 'http://dgo.3r13.nl/wp-content/plugins/dgo-highlight-unseen/unseen_icon.png';
	    imageUrl = "";
	    // cancel possible previous notification
	    if(dgohighlightunseenUpdater.notification) dgohighlightunseenUpdater.notification.cancel();
	    // make new notification
	    dgohighlightunseenUpdater.notification = window.webkitNotifications.createNotification(
			imageUrl, sTitle, sContent);
	   	dgohighlightunseenUpdater.notification.ondisplay = function() { console.log('notification ondisplay'); };
	   	dgohighlightunseenUpdater.notification.onclose = function() { console.log('notification onclose'); };
	   	dgohighlightunseenUpdater.notification.onerror = function() { console.log('notification onerror'); };
	   	dgohighlightunseenUpdater.notification.onclick = function() {
	   		window.location.href = sHref;
	   	};
	   	dgohighlightunseenUpdater.notification.show();
	}
}
dgohighlightunseenUpdater.ajaxUpdate = function(bShowNotifications){
	dgohighlightunseenUpdater.ajax('get_unseen', function (response){
	    if( typeof response == 'object'
	       && 'success' in response
	       && response.success
	       ){
	            // show desktop notifications
	       		if(bShowNotifications){
		       		if(response.items.length == 1){
			            dgohighlightunseenUpdater.showDesktopNotification(response.items[0].notificationTitle, response.items[0].notificationContent, response.items[0].notificationHref);
			       		dgohighlightunseenUpdater.showTitleNotification(response.notificationTitle);
		       		}else if(response.items.length > 1){
			            dgohighlightunseenUpdater.showDesktopNotification(response.notificationTitle, response.notificationContent, response.notificationHref );
			       		dgohighlightunseenUpdater.showTitleNotification(response.notificationTitle);
		       		}
	       		}
	       		// update notification menu in admin bar
	       		dgohighlightunseenUpdater.updateNotificationMenu(response);
	    }
	}, null, null);
}

dgohighlightunseenUpdater.updateNotificationMenu = function(response){
    if( typeof response == 'object'
       && 'success' in response
       && response.success
       ){
 			// update notifications menu in admin bar
            jQuery(function($){
                // update root menu
            	if(response.a_label=='0'){
            		$('#wp-admin-bar-dgohighlightunseen span').hide('slow');
            	}else{
            		$('#wp-admin-bar-dgohighlightunseen span').show('slow');
            	}
                $('#wp-admin-bar-dgohighlightunseen a').attr('href', response.a_href);
                $('#dgohighlightunseen-adminbar-count').html(response.a_label);
                // emtpy the submenu
                $('#wp-admin-bar-dgohighlightunseen-default').html( '' );
                // handle unseen items
                for(var i in response.items){
		            // set sub-menu items
                   $('#wp-admin-bar-dgohighlightunseen-default')
                   .append($( '<li></li>' )
                           .attr( { class: 'wp-admin-bar-dgohighlightunseen-'+response.items[i].type } )
                           .append( $('<a></a>')
                                   .attr( { href: response.items[i].a_href } )
                                   .addClass( 'ab-item' )
                                   .html( response.items[i].a_label )
                                   )
                    );
                }
            });
    }else{
        //alert(response);
    }
}
dgohighlightunseenUpdater.ajax = function(sAction, fCallback, sItemType, sItemID){
    jQuery.post(
        dgohighlightunseen.ajaxurl,
        {
            // the handle that is used as part of the hook (wp_ajax_my_action) to assign the callback to
            action : 	sAction,
            item_type:	sItemType,
            item_ID: 	sItemID,
            // send the nonce along with the request
            ajaxnonce : dgohighlightunseen.ajaxnonce
        },
        fCallback
    ); 
}

dgohighlightunseenUpdater.setUnseen = function(item_type, item_ID){
	dgohighlightunseenUpdater.ajax('set_unseen', dgohighlightunseenUpdater.updateNotificationMenu, item_type, item_ID);
}
dgohighlightunseenUpdater.setSeen = function(item_type, item_ID){
	dgohighlightunseenUpdater.ajax('set_seen', dgohighlightunseenUpdater.updateNotificationMenu, item_type, item_ID);
}

