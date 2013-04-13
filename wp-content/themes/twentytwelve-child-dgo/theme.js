
jQuery(document).ready(function($) {
	
	// bind click events to articles
    DGOBindEventHandlersToArticles();

	// init ajax load posts
	pdb_ajax_load.init();
	
	// send comments through ajax
	DGO_ajaxify_comments();

});


/**
 * Expand jQuery object with a "flash" method to draw attention to it
 */
jQuery.fn.flash = function(highlightColor, duration) {
	  var highlightBg = highlightColor || "#FFFF9C";
	  var animateMs = duration || 1000;
	  var originalBg = this.css("backgroundColor");
	  $this = jQuery(this);
//	  this.stop().css("background-color", highlightBg).animate({backgroundColor: originalBg}, animateMs,
      this.css("background-color", highlightBg).animate({backgroundColor: originalBg}, animateMs,
			function(){
			    $this.css('background-color', originalBg);
			}
	  );
};


var pdb_ajax_load = {
    processing: false,
    pageNum: null,
    max: null,
    nextLink: (typeof pbd_alp !='undefined')? pbd_alp.nextLink : '',
    msgId : 'pbd-alp-load-posts',
    placeholderBaseClass : 'pbd-alp-placeholder-',
    load : function(){
        if (this.processing)
            return false;

        // Are there more posts to load?
        if(this.pageNum <= this.max) {
            // prevent concurrent posts
            this.processing = true;
            // Show that we're working.
            jQuery('#'+this.msgId).text('Loading more posts...');
            // load next page
            jQuery('.'+pdb_ajax_load.placeholderBaseClass + this.pageNum).load(this.nextLink + ' article', function(response, status, xhr) {
                if (status == "error") {
                    if (xhr.status == '404' ){
                        // page not found: no more pages to show
                        jQuery('#'+pdb_ajax_load.msgId).html('');
                    }else{
                        jQuery('#'+pdb_ajax_load.msgId).html(xhr.status + ' ' + xhr.statusText);
                    }
                }else{
                    // Update page number and nextLink.
                    pdb_ajax_load.pageNum++;
                    pdb_ajax_load.nextLink = pdb_ajax_load.nextLink.replace(/\/page\/[0-9]+/, '/page/'+ pdb_ajax_load.pageNum);
                    // Add a new placeholder, for when user clicks again.
                    jQuery('#'+pdb_ajax_load.msgId).before('<div class="' + pdb_ajax_load.placeholderBaseClass + pdb_ajax_load.pageNum +'"></div>');
                    // Update the message.
                    jQuery('#'+pdb_ajax_load.msgId).html('');
                    // allow next request
                    pdb_ajax_load.processing = false;
                    
                    // (re)bind eventhandlers to articles
                    DGOBindEventHandlersToArticles();
                }
            });
        }
        return false;
    },
    init: function(){
    	

		if(typeof pbd_alp !='undefined'){ // Should be set by an action defined in functions.php 
			
			// The number of the next page to load (/page/x/).
			pdb_ajax_load.pageNum = parseInt(pbd_alp.startPage) + 1;
			
			// The maximum number of pages the current query can return.
			pdb_ajax_load.max = parseInt(pbd_alp.maxPages);
			
			// The link of the next page of posts.
			pdb_ajax_load.nextLink = pbd_alp.nextLink;
			
			/**
			 * Replace the traditional navigation with our own,
			 * but only if there is at least one page of new posts to load.
			 */
			if(pdb_ajax_load.pageNum <= pdb_ajax_load.max) {
				// Insert the "More Posts" link.
				jQuery('#content')
					.append('<div class="' + pdb_ajax_load.placeholderBaseClass + pdb_ajax_load.pageNum +'"></div>')
					.append('<p id="' + pdb_ajax_load.msgId +'"></p>');
					
				// Remove the traditional navigation.
				jQuery('.navigation').remove();
			}
			
		    /**
		    * Load new posts when the page is scrolled to the bottom.
		    */
		    jQuery(document).scroll(function(e){
		        if (jQuery(window).scrollTop() >= jQuery(document).height() - jQuery(window).height() - 200){
		           pdb_ajax_load.load();
		        }
		    });
		    
			/**
			 * Load more posts if initial page is heiger than num posts, withouth the need to scroll first
			 */		    
	        if (jQuery(window).scrollTop() >= jQuery(document).height() - jQuery(window).height() - 200){
	           pdb_ajax_load.load();
	        }
		    
		}
    } 
};


function DGOArticleSingleClick(e){
    // check which element recieved the click
    var event = e || window.event;
    var targ = event.target || event.srcElement;
    if (targ.nodeType == 3) targ = targ.parentNode; // defeat Safari bug
    if(targ.nodeName=="A") return false;// link clicked, abort further actions
    if(targ.nodeName=="INPUT") return false;// link clicked, abort further actions
    if(targ.nodeName=="LABEL") return false;// link clicked, abort further actions
    // get jquery reference to this node
    var article = jQuery(this);
    // decide what to do with the click
    if ( article.height() <= 100 ){ // to easily deside the difference between 'open' and 'closed'
        // consider box closed, open it
        article.addClass('open');
        aPostMeta = article.attr('id').split('-');
        dgohighlightunseenUpdater.setUnseen(aPostMeta[0], aPostMeta[1]);
        // asume success, add class
        article.addClass('dgohighlightunseen');
    }else{
        // consider box open, deside what to do
        // depending on the region of the click
        // and the face of the box.
		// set if commentField should be available
		var allowComments = article.hasClass('dgoforumtopic') || article.hasClass('post');
        // get region of the click
        var clickRegion = DGOArticleGetClickRegion.call(this,e);
        switch(clickRegion){
        	case 'bottom':
	            // turn the box, show next face
    	        DGOArticleShowFace(article, 'next');
        		break;
        	case 'top':
	            // clicked in the top region of the box
        		if(allowComments ){
	        		// hide comment box
	        		DGOArticleForumTopicToggleCommentField(article,'hide');
        		}
	            if(DGOArticleGetFaces(article)['current'] == 'front'){
	                //front is shown, close the box
	                // - remove the open class
	                article.removeClass('open');
	                if(article.hasClass('dgohighlightunseen')){
	                    // - do ajax call to mark this item as 'seen'
				        aPostMeta = article.attr('id').split('-');
				        dgohighlightunseenUpdater.setSeen(aPostMeta[0], aPostMeta[1]);
	                    // - remove the dgohighlightunseen class (asume success)
	                    article.removeClass('dgohighlightunseen');
	                }
	            }else{
	                // other face is shown, rotate box back
	                DGOArticleShowFace(article, 'prev');
	            }
        		break;
        	default:
        		// middle region clicked
        		if(allowComments){
	        		// toggle comment box
	        		DGOArticleForumTopicToggleCommentField(article);
        		}
        }
    }
}

function DGOArticleDoubleClick(e){
    // check which element recieved the click
    var event = e || window.event;
    var targ = event.target || event.srcElement;
    if (targ.nodeType == 3) targ = targ.parentNode; // defeat Safari bug
    if(targ.nodeName=="A") return false;// link clicked, abort further actions
    // get jquery reference to this node
    var article = jQuery(this);
    // get click region
    //var clickRegion = DGOArticleGetClickRegion.call(this, e);
    if(DGOArticleGetFaces(article)['current'] == 'front'){
    	document.location.href= article.find('.entry-title a[rel=bookmark]').attr('href');
    }else{
    	DGOArticleShowFace(article, 'front');
    }

}


function DGOArticleGetClickRegion(e){
    var regionHeight = 40;     // height of the region to compare against
    // get relative offset of the click to the dom node
    jElm = jQuery(this);
    var offset = jElm.offset();
    //var relX = e.pageX - offset.left;
    var relY = e.pageY - offset.top;
    if(relY < regionHeight){
        return 'top';
    }else if(relY > jElm.height() - regionHeight){
        return 'bottom';
    }else{
    	return 'middle';
    }
}

function DGOArticleForumTopicToggleCommentField(article, showhidetoggle){
	if(!showhidetoggle) showhidetoggle = 'toggle';
	var commentLi = article.find('LI.writecomment');
	switch(showhidetoggle){
		case 'toggle':
			showhide = (commentLi.hasClass('hidden'))? 'show':'hide';
			DGOArticleForumTopicToggleCommentField(article, showhide);
			break;
		case 'hide':
			commentLi.addClass('hidden');
			commentLi.siblings().last().removeClass('hidden');
			commentLi.find('INPUT.commentfield').blur();
			break;
		case 'show':
			commentLi.removeClass('hidden');
			if(article.find('LI.writecomment').siblings().length>=5){
				commentLi.siblings().last().addClass('hidden');
			}
			commentLi.find('INPUT.commentfield').focus();
			break;	
	}
}
function DGOArticleForumTopicAddComment(article, data){
	// get access to the comment list item
	var commentLi = article.find('LI.writecomment');
	if(data.length>0 && data!='error'){
		// clear comment field
		commentLi.find('INPUT.commentfield').val('');
		var newLi = jQuery(data);
		// add as second list item
		commentLi.after(newLi);
		// hide comment field
		// setting class hidden will animate, which we don't want now, so we remove the element from the dom, add the class, and add the element again
		commentLi.detach();
		commentLi.addClass('hidden'); 
		newLi.before(commentLi);
		newLi.flash(null, 300);
		if(article.find('LI.writecomment').siblings().length > article.find('LI.writecomment').parent().attr('data-maxItems') ){
			// remove last list item
			article.find('LI.writecomment').siblings().last().remove();
		}
	}else{
		DGOArticleForumTopicToggleCommentField(article);
	}
}

function DGOArticleGetFaces(jElm){
    // get jquery reference to the box
    var box = jElm.find('.box');
    // set the order of the box faces
    var faces = ['front', 'top', 'back', 'bottom'];
    // find the current face
    var currentFace = faces[0]; // set default
    for(var i in faces){
        if( box.hasClass('show-'+faces[i]) ) {
        	currentFace = faces[i]; 
        	break;
        }
    }
    // double the array, so every element has a prev/next element
	// start search at second element, so every result will have a prev/next element
    faces = faces.concat(faces);
    var currentIndex = faces.indexOf(currentFace,1);
    // build array of relative face positions
    var result = [];
    result['prev']    = faces[currentIndex-1];
    result['current'] = faces[currentIndex];
    result['next']    = faces[currentIndex+1];
    return result;
}

function DGOArticleShowFace(jElm, prevnext){
    var faces = DGOArticleGetFaces(jElm);
    var box = jElm.find('.box');
    box.removeClass('show-'+faces['current']);
    box.addClass('show-'+faces[prevnext]);
}

function DGOBindEventHandlersToArticles(){
    jQuery('#content ARTICLE.boxcontainer').each(function(){
        // remove click event from all articles
        jQuery(this).unbind('click.DGOBindEventHandlersToArticles');
        // re-add click events to all articles
        jQuery(this).bind('click.DGOBindEventHandlersToArticles', function(e){
            // distinquish between single and double clicks on the same element
            var that = this;
            setTimeout(function() {
                var dblclick = parseInt(jQuery(that).data('double'), 10);
                if (dblclick > 0) {
                    jQuery(that).data('double', dblclick-1);
                } else {
                    DGOArticleSingleClick.call(that, e);
                }
            }, 300);
        }).dblclick(function(e) {
            jQuery(this).data('double', 2);
            DGOArticleDoubleClick.call(this, e);
        });
    });
    
    // add submit on enter to comment fields
    jQuery('#content INPUT.commentfield').each(function(){
        // remove  event from all articles
        jQuery(this).unbind('keydown.DGOBindEventHandlersToCommentFields');
        // re-add  events to all articles
        jQuery(this).bind('keydown.DGOBindEventHandlersToCommentFields', function(e){
		    if (!e) { var e = window.event; }
		    //e.preventDefault(); // sometimes useful
		 	article = jQuery(this).closest('ARTICLE');
			if (e.keyCode == 13) { 
				// Enter is pressed
			    e.preventDefault(); // submit through code only
				article.find('form').submit();
				//DGOArticleForumTopicAddComment(article); // is now done on succes
			}
			if (e.keyCode == 27) {
				// Esc is pressed
				//article.find('INPUT.commentfield').get().value='';
				article.find('INPUT.commentfield').val('');
				article.find('INPUT.commentfield').blur();
				DGOArticleForumTopicToggleCommentField(article,'hide');
			}
        });
    });
}



function DGO_ajaxify_comments(commentFormSelector){

	commentFormSelector = commentFormSelector || '.commentform'; // default to all commentforms on the page

	jQuery(commentFormSelector).each(function(){
		
		console.log('ajaxifying commentform:'+this.id);
		// ajaxify the form!
		var commentform=jQuery(this); 
		var statusdiv=jQuery('<div class="comment-ajax-status" ></div>'); // define the infopanel
		commentform.prepend(statusdiv); // add info panel before the form to provide feedback or errors
		var isExcerpt = (commentform.parent('span.excerpt').length)?true:false;
		if(isExcerpt){
			commentform.append(jQuery('<input type="hidden" name="return_excerpt" value="true"/>'));
		}
		commentform.submit(function(){
				//serialize and store form data in a variable
				var formdata=commentform.serialize();
				//Extract action URL from commentform
				var formurl=commentform.attr('action');
				// disable input, while posting
				commentform.find('[name=comment]').prop('disabled', true);
				//Post Form with data
				jQuery.ajax({
						type: 'post',
						url: formurl,
						data: formdata})
					// handle the response
					.always(function(juggle, textStatus){
						console.log('Ajaxresult:always():textStats='+textStatus);
						statusdiv.html('');

						if('error'==textStatus){
							var jqXhr = juggle;
							// strip msg from messy response body
							var matches = jqXhr.responseText.match(/<body id="error-page">[\s\S]*<p>([\s\S]*)<\/p>[\s\S]*<\/body>/i);
							if(matches.length>1){
								var msg = matches[1];
							}else{
								var msg = 'Er is iets fout gegaan, sorry!';
							}
							statusdiv.html( '<p class="ajax-error" >' + msg +'</p>' );
							statusdiv.flash(null, 500);
							if(isExcerpt){
								// hide status msg after a while
								setTimeout("jQuery('.comment-ajax-status').html('');",4000);
							}

						}else{
							var data = juggle;
							if(data=="error"){
								statusdiv.html('<p class="ajax-error" >Server returned: Error</p>');
							}else{
								if(isExcerpt){
									var article = commentform.closest('article');
									DGOArticleForumTopicAddComment(article, data);
								}else{
									commentform.find('textarea[name=comment]').val('');
									jQuery('.dgocommentform').toggleClass('closed');
									var newComment = jQuery(data);
									newComment.hide();
									jQuery('#comments .commentlist LI:nth-child(1)').after(newComment);
									newComment.fadeIn(500).flash(null, 300);
								}
							}
						}
						// enable input
						commentform.find('[name=comment]').prop('disabled', false);
					});
					
				return false;
		});// end submit()
	
	});// end each();
}

