
var pdb_ajax_load = {
    processing: false,
    pageNum: null,
    max: null,
    nextLink: pbd_alp.nextLink,
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
                    pdb_ajax_load.nextLink = pdb_ajax_load.nextLink.replace(/\/page\/[0-9]?/, '/page/'+ pdb_ajax_load.pageNum);
                    // Add a new placeholder, for when user clicks again.
                    jQuery('#'+pdb_ajax_load.msgId).before('<div class="' + pdb_ajax_load.placeholderBaseClass + pdb_ajax_load.pageNum +'"></div>')
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
    }
}

jQuery(document).ready(function($) {

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
		$('#content')
			.append('<div class="' + pdb_ajax_load.placeholderBaseClass + pdb_ajax_load.pageNum +'"></div>')
			.append('<p id="' + pdb_ajax_load.msgId +'"></p>');
			
		// Remove the traditional navigation.
		$('.navigation').remove();
	}
	
    /**
    * Load new posts when the page is scrolled to the bottom.
    */
    $(document).scroll(function(e){
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 200){
           pdb_ajax_load.load();
        }
    });
});

jQuery(document).ready(function() {
       DGOBindEventHandlersToArticles();
});

function DGOArticleSingleClick(e){
    // check which element recieved the click
    var event = e || window.event;
    var targ = event.target || event.srcElement;
    if (targ.nodeType == 3) targ = targ.parentNode; // defeat Safari bug
    if(targ.nodeName=="A") return false;// link clicked, abort further actions
    // get jquery reference to this node
    var article = jQuery(this);
    // decide what to do with the click
    if ( article.height() <= 100 ){ // to easily deside the difference between 'open' and 'closed'
        // consider box closed, open it
        article.addClass('open');
//        alert('do ajax call to mark item as "unseen":'+article.attr('id'));
        // if ajax call succeeds, add class
        article.addClass('dgohighlightunseen');
    }else{
        // consider box open, deside what to do
        // depending on the region of the click
        // and the face of the box.

        // get region of the click
        var clickRegion = DGOArticleGetClickRegion.call(this,e);
        if( clickRegion == 'bottom' ){
            // turn the box, show next face
            DGOArticleShowFace(article, 'next');
        }else{
            // clicked in the middle and top region of the box
            if(DGOArticleGetFaces(article)['current'] == 'front'){
                //front is shown, close the box
                // - remove the open class
                article.removeClass('open');
                if(article.hasClass('dgohighlightunseen')){
                    // - do ajax call to mark this item as 'seen'
//                    alert('todo: ajax call to mark this item "seen":'+article.attr('id'));
                    // - remove the dgohighlightunseen class on success
                    article.removeClass('dgohighlightunseen');
                }
            }else{
                // other face is shown, rotate box back
                DGOArticleShowFace(article, 'prev');
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
    var clickRegion = DGOArticleGetClickRegion.call(this, e);
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
    if(relY > jElm.height() - regionHeight){
        return 'bottom';
    }else{
        return 'top';
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
    jQuery('#content ARTICLE').each(function(){
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
}

