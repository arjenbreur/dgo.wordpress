<?php
// get reguested skin, or set default
$skin = (isset($_REQUEST['ver']) && $_REQUEST['ver']!='default' ) ? $_REQUEST['ver'] : 'default	';


// DEFINE THE SKINS ////////////////////////////////////////////////////////////////////////////////////////////
$bgcolors = array();
switch($skin){
	case 'nightly':
		$bgcolors['colors'][1]						= '#000000'; // main text color
		$bgcolors['colors'][2]						= '#0F3647'; // secondairy color, should be readable against the same background
		$bgcolors['colors'][3]						= '#21759B'; // link color, should be readable against the same background
		$bgcolors['colors'][4]						= '#0F3647'; // link hover color;

		$bgcolors['borders']['border-style']		= 'solid';
		$bgcolors['borders']['border-color']		= '#bbbbbb';
		$bgcolors['borders']['border-width']		= '1px';
		$bgcolors['borders']['border-radius']		= '5px';
	
		$bgcolors['rules']['BODY']['background-color']	= '#B5E2F5 !important';

		$hex = '#FFFFFF';
		$bgcolors['boxfaces']['posttypes']['page']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'50%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.50', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#EBBDAA';
		$bgcolors['boxfaces']['posttypes']['post']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'50%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.50', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#B8EBAA';
		$bgcolors['boxfaces']['posttypes']['dgoforumtopic']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'50%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.50', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#E8B4F5';
		$bgcolors['boxfaces']['posttypes']['dgoquote']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'50%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.50', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#E6C347';
		$bgcolors['boxfaces']['posttypes']['dgotransaction']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'50%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.50', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#35AFE3';
		$bgcolors['boxfaces']['posttypes']['dgoalbum']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'50%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.50', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));		
		break;
	case 'tiger':
		$bgcolors['colors'][1]						= '#000000 !important'; // main text color
		$bgcolors['colors'][2]						= '#333333 !important'; // secondairy color, should be readable against the same background
		$bgcolors['colors'][3]						= '#000000'; // link color, should be readable against the same background
		$bgcolors['colors'][4]						= '#333333'; // link hover color;

		$bgcolors['borders']['border-style']		= 'solid';
		$bgcolors['borders']['border-color']		= '#000000';
		$bgcolors['borders']['border-width']		= '2px';
		$bgcolors['borders']['border-radius']		= '5px';
		
		$bgcolors['rules']['BODY']								['background-color']	= '#4D6432 !important';
		$bgcolors['rules']['BODY.home ARTICLE.boxcontainer']	['margin'] 				= '0 0 .5em 0 !important';	
		$bgcolors['rules']['BODY ARTICLE.open .box .boxface']	['box-shadow']			= '8px 8px 24px rgba(0, 0, 0, 0.4) !important';	

		$rndA = array( mt_rand(30,100)/100, mt_rand(30,100)/100); $rndSo = array(mt_rand(0,50)); array_push($rndSo, mt_rand($rndSo[0]+20,85)); $rndSe = array(mt_rand(0,50)); array_push($rndSe, mt_rand($rndSe[0]+20,85));
		$bgcolors['boxfaces']['posttypes']['page']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#ECDFD6', 'a'=>$rndA[0], 'stop' =>$rndSo[0].'%' ), 2=>array('hex'=>'#ECDFD6', 'a'=>'1.0', 'stop' =>$rndSo[1].'%' ) ),
			'even'=>array(1=>array('hex'=>'#ECDFD6', 'a'=>$rndA[1], 'stop' =>$rndSe[0].'%' ), 2=>array('hex'=>'#ECDFD6', 'a'=>'1.0', 'stop' =>$rndSe[1].'%' ) ),
		));
		$rndA = array( mt_rand(30,100)/100, mt_rand(30,100)/100); $rndSo = array(mt_rand(0,50)); array_push($rndSo, mt_rand($rndSo[0]+20,85)); $rndSe = array(mt_rand(0,50)); array_push($rndSe, mt_rand($rndSe[0]+20,85));
		$bgcolors['boxfaces']['posttypes']['post']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#E58537', 'a'=>$rndA[0], 'stop' =>$rndSo[0].'%' ), 2=>array('hex'=>'#E58537', 'a'=>'1.0', 'stop' =>$rndSo[1].'%' ) ),
			'even'=>array(1=>array('hex'=>'#E58537', 'a'=>$rndA[1], 'stop' =>$rndSe[0].'%' ), 2=>array('hex'=>'#E58537', 'a'=>'1.0', 'stop' =>$rndSe[1].'%' ) ),
		));
		$rndA = array( mt_rand(30,100)/100, mt_rand(30,100)/100); $rndSo = array(mt_rand(0,50)); array_push($rndSo, mt_rand($rndSo[0]+20,85)); $rndSe = array(mt_rand(0,50)); array_push($rndSe, mt_rand($rndSe[0]+20,85));
		$bgcolors['boxfaces']['posttypes']['dgoforumtopic']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#F5B87E', 'a'=>$rndA[0], 'stop' =>$rndSo[0].'%' ), 2=>array('hex'=>'#F5B87E', 'a'=>'1.0', 'stop' =>$rndSo[1].'%' ) ),
			'even'=>array(1=>array('hex'=>'#F5B87E', 'a'=>$rndA[1], 'stop' =>$rndSe[0].'%' ), 2=>array('hex'=>'#F5B87E', 'a'=>'1.0', 'stop' =>$rndSe[1].'%') ),
		));
		$rndA = array( mt_rand(30,100)/100, mt_rand(30,100)/100); $rndSo = array(mt_rand(0,50)); array_push($rndSo, mt_rand($rndSo[0]+20,85)); $rndSe = array(mt_rand(0,50)); array_push($rndSe, mt_rand($rndSe[0]+20,85));
		$bgcolors['boxfaces']['posttypes']['dgoquote']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#6E4D35', 'a'=>$rndA[0], 'stop' =>$rndSo[0].'%' ), 2=>array('hex'=>'#6E4D35', 'a'=>'1.0', 'stop' =>$rndSo[1].'%' ) ),
			'even'=>array(1=>array('hex'=>'#6E4D35', 'a'=>$rndA[1], 'stop' =>$rndSe[0].'%' ), 2=>array('hex'=>'#6E4D35', 'a'=>'1.0', 'stop' =>$rndSe[1].'%' ) ),
		));
		$rndA = array( mt_rand(30,100)/100, mt_rand(30,100)/100); $rndSo = array(mt_rand(0,50)); array_push($rndSo, mt_rand($rndSo[0]+20,85)); $rndSe = array(mt_rand(0,50)); array_push($rndSe, mt_rand($rndSe[0]+20,85));
		$bgcolors['boxfaces']['posttypes']['dgotransaction']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#E77139', 'a'=>$rndA[0], 'stop' =>$rndSo[0].'%' ), 2=>array('hex'=>'#E77139', 'a'=>'1.0', 'stop' =>$rndSo[1].'%' ) ),
			'even'=>array(1=>array('hex'=>'#E77139', 'a'=>$rndA[1], 'stop' =>$rndSe[0].'%' ), 2=>array('hex'=>'#E77139', 'a'=>'1.0', 'stop' =>$rndSe[1].'%' ) ),
		));
		$rndA = array( mt_rand(30,100)/100, mt_rand(30,100)/100); $rndSo = array(mt_rand(0,50)); array_push($rndSo, mt_rand($rndSo[0]+20,85)); $rndSe = array(mt_rand(0,50)); array_push($rndSe, mt_rand($rndSe[0]+20,85));
		$bgcolors['boxfaces']['posttypes']['dgoalbum']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#F1E6E1', 'a'=>$rndA[0], 'stop' =>$rndSo[0].'%' ), 2=>array('hex'=>'#F1E6E1', 'a'=>'1.0', 'stop' =>$rndSo[1].'%' ) ),
			'even'=>array(1=>array('hex'=>'#F1E6E1', 'a'=>$rndA[1], 'stop' =>$rndSe[0].'%' ), 2=>array('hex'=>'#F1E6E1', 'a'=>'1.0', 'stop' =>$rndSe[1].'%' ) ),
		));
		
		// posttype icons
		// - base styling
		$bgcolors['rules']['ARTICLE H1.entry-title']['background-repeat'] 					= 'no-repeat';
		$bgcolors['rules']['ARTICLE H1.entry-title']['padding-left'] 						= '30px';
		$bgcolors['rules']['ARTICLE H1.entry-title']['width'] 								= '0';
		$bgcolors['rules']['ARTICLE H1.entry-title']['overflow'] 							= '0';
		$bgcolors['rules']['ARTICLE H1.entry-title']['white-space'] 						= 'nowrap';
		$bgcolors['rules']['ARTICLE H1.entry-title']['background-position'] 				= '.2em .2em'; //default
		// - per post type
		$bgcolors['rules']['ARTICLE.post H1.entry-title']['background-image'] 				= 'url("/wp-admin/images/menu-vs.png?ver=20121105")';
		$bgcolors['rules']['ARTICLE.post H1.entry-title']['background-position'] 			= '-272px -37px';
		$bgcolors['rules']['ARTICLE.page H1.entry-title']['background-image'] 				= 'url("/wp-admin/images/menu-vs.png?ver=20121105")';
		$bgcolors['rules']['ARTICLE.page H1.entry-title']['background-position'] 			= '-152px -37px';
		$bgcolors['rules']['ARTICLE.dgoforumtopic H1.entry-title']['background-image'] 		= 'url("/wp-content/plugins/dgo-forum/dgoforumtopic_icon.png")';
		$bgcolors['rules']['ARTICLE.dgoquote H1.entry-title']['background-image'] 			= 'url("/wp-content/plugins/dgo-quotes/dgoquote_icon.png")';
		$bgcolors['rules']['ARTICLE.dgotransaction H1.entry-title']['background-image'] 	= 'url("/wp-content/plugins/dgo-transactions/currency-euro.png")';
		
		break;
		
	case 'madagascar':
		$bgcolors['colors'][1]						= '#000000'; // main text color
		$bgcolors['colors'][2]						= '#006633'; // secondairy color, should be readable against the same background
		$bgcolors['colors'][3]						= '#151714'; // link color, should be readable against the same background
		$bgcolors['colors'][4]						= '#006633'; // link hover color;

		$bgcolors['borders']['border-style']		= 'solid';
		$bgcolors['borders']['border-color']		= '#FF9700';
		$bgcolors['borders']['border-width']		= '1px';
		$bgcolors['borders']['border-radius']		= '3px';
	
		$bgcolors['rules']['BODY']								['background-color']	= '#B5E2F5 !important';

		$hex = '#F8FEFD';
		$bgcolors['boxfaces']['posttypes']['page']				['border-color']		= $hex;
		$bgcolors['boxfaces']['posttypes']['page']				['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.55', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#7E5632';
		$bgcolors['boxfaces']['posttypes']['post']				['border-color']		= $hex;
		$bgcolors['boxfaces']['posttypes']['post']				['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.55', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#FF9700';
		$bgcolors['boxfaces']['posttypes']['dgoforumtopic']		['border-color'] 		= $hex;
		$bgcolors['boxfaces']['posttypes']['dgoforumtopic']		['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.55', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#BC5E20';
		$bgcolors['boxfaces']['posttypes']['dgoquote']			['border-color']		= $hex;
		$bgcolors['boxfaces']['posttypes']['dgoquote']			['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.55', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#77823A';
		$bgcolors['boxfaces']['posttypes']['dgotransaction']	['border-color']		= $hex;
		$bgcolors['boxfaces']['posttypes']['dgotransaction']	['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.55', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));
		$hex = '#BD906B';
		$bgcolors['boxfaces']['posttypes']['dgoalbum']			['border-color']		= $hex;
		$bgcolors['boxfaces']['posttypes']['dgoalbum']			['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>$hex, 'a'=>'.75', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
			'even'=>array(1=>array('hex'=>$hex, 'a'=>'.55', 'stop' =>'0%' ), 2=>array('hex'=>$hex, 'a'=>'1.0', 'stop' =>'75%' ) ),
		));

		break;
	case 'dark':
		$bgcolors['colors'][1]						= '#FFFFFF'; // main text color
		$bgcolors['colors'][2]						= '#DDDDDD'; // secondairy color, should be readable against the same background
		$bgcolors['colors'][3]						= '#FFFFFF'; // link color, should be readable against the same background
		$bgcolors['colors'][4]						= '#DDDDDD'; // link hover color;

		$bgcolors['borders']['border-style']		= 'solid';
		$bgcolors['borders']['border-color']		= '#000000';
		$bgcolors['borders']['border-width']		= '0px';
		$bgcolors['borders']['border-radius']		= '4px';
	
		$bgcolors['rules']['BODY']								['background-color']	= '#464646 !important';

		$bgcolors['boxfaces']['posttypes']['page']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#111111', 'a'=>'.5', 'stop' =>'50%' ), 2=>array('hex'=>'#111111', 'a'=>'1.0', 'stop' =>'100%' ) ),
			'even'=>array(1=>array('hex'=>'#111111', 'a'=>'.25', 'stop' =>'0%' ), 2=>array('hex'=>'#111111', 'a'=>'1.0', 'stop' =>'50%' ) ),
		));
		$bgcolors['boxfaces']['posttypes']['post']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#333333', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#333333', 'a'=>'1.0', 'stop' =>'100%' ) ),
			'even'=>array(1=>array('hex'=>'#333333', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#333333', 'a'=>'1.0', 'stop' =>'100%' ) ),
		));
		$bgcolors['boxfaces']['posttypes']['dgoforumtopic']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#000000', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#000000', 'a'=>'1.0', 'stop' =>'100%' ) ),
			'even'=>array(1=>array('hex'=>'#000000', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#000000', 'a'=>'1.0', 'stop' =>'100%' ) ),
		));
		$bgcolors['boxfaces']['posttypes']['dgoquote']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#666666', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#666666', 'a'=>'1.0', 'stop' =>'100%' ) ),
			'even'=>array(1=>array('hex'=>'#666666', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#666666', 'a'=>'1.0', 'stop' =>'100%' ) ),
		));
		$bgcolors['boxfaces']['posttypes']['dgotransaction']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#999999', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#999999', 'a'=>'1.0', 'stop' =>'100%' ) ),
			'even'=>array(1=>array('hex'=>'#999999', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#999999', 'a'=>'1.0', 'stop' =>'100%' ) ),
		));
		$bgcolors['boxfaces']['posttypes']['dgoalbum']['background-image'] = array('linear-gradient' => array(
			'odd' =>array(1=>array('hex'=>'#bbbbbb', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#bbbbbb', 'a'=>'1.0', 'stop' =>'100%' ) ),
			'even'=>array(1=>array('hex'=>'#bbbbbb', 'a'=>'1', 'stop' =>'0%' ), 2=>array('hex'=>'#bbbbbb', 'a'=>'1.0', 'stop' =>'100%' ) ),
		));
		break;
		
	case 'dgo':
		$bgcolors['colors'][1]						= '#000000'; // main text color
		$bgcolors['colors'][2]						= '#A4A4A4'; // secondairy color, should be readable against the same background
		$bgcolors['colors'][3]						= '#333333'; // link color, should be readable against the same background
		$bgcolors['colors'][4]						= '#000000'; // link hover color;

		$bgcolors['borders']['border-style']		= 'solid';
		$bgcolors['borders']['border-color']		= '#000000';
		$bgcolors['borders']['border-width']		= '1px';
		$bgcolors['borders']['border-radius']		= '0';
	
		$bgcolors['rules']['BODY']								['background-color']	= '#FFFFFF !important';

		$bgcolors['boxfaces']['posttypes']['page']				['background-color']	= '#FFFFCC';
		$bgcolors['boxfaces']['posttypes']['post']				['background-color']	= '#FFFFCC';
		$bgcolors['boxfaces']['posttypes']['dgoforumtopic']		['background-color'] 	= '#FFFFCC';
		$bgcolors['boxfaces']['posttypes']['dgoquote']			['background-color']	= '#FFFFCC';
		$bgcolors['boxfaces']['posttypes']['dgotransaction']	['background-color']	= '#FFFFCC';
		$bgcolors['boxfaces']['posttypes']['dgoalbum']			['background-color']	= '#FFFFCC';

		$bgcolors['rules']['BODY']											['font-family'] 		= 'Verdana,Geneva,Arial,Helvetica,sans-serif !important';
		$bgcolors['rules']['BODY.home ARTICLE.boxcontainer']				['font-size'] 			= '70% !important';
		$bgcolors['rules']['BODY.home ARTICLE.boxcontainer']				['height']				= '26px !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER']			['background-color']	= '#CC9933 !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER']			['border-color']		= '#000 !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER']			['border-style']		= 'solid !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER']			['border-width']		= '1px !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER']			['font-size']			= '11px !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER']			['font-weight']			= 'bold !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER']			['padding']				= '10px !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER']			['margin']				= '-1.2em -1.2em 1em -1.2em !important';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer .box HEADER H1']		['font-size']			= '1em';
		$bgcolors['rules']['BODY ARTICLE.boxcontainer.open .box HEADER H1']	['font-size']			= '1.3em';
		$bgcolors['rules']['BODY OL.commentlist']							['background-color']	= '#660033 !important';

		$bgcolors['rules']['#page.site']									['margin']				= '3.5em 0';
		$bgcolors['rules']['#page.site']									['padding']				= '0';
		$bgcolors['rules']['#page.site']									['width']				= '768px';
		$bgcolors['rules']['#page.site']									['overflow']			= 'visible';
		$bgcolors['rules']['#wpadminbar']									['background']			= 'none #660033';
		$bgcolors['rules']['#wpadminbar']									['border-bottom']		= '18px solid #CC9933';
		$bgcolors['rules']['#wpadminbar']									['border-top']			= '26px solid black';
		$bgcolors['rules']['#primary.site-content']							['margin']				= '1.2em 0 0';
		$bgcolors['rules']['#primary.site-content']							['width']				= '570px';
		$bgcolors['rules']['#primary.site-content']							['float']				= 'right';
		$bgcolors['rules']['#secondary.widget-area']						['float']				= 'left';
		$bgcolors['rules']['#secondary.widget-area']						['margin']				= '0';
		$bgcolors['rules']['#secondary.widget-area']						['width']				= '180px';
		$bgcolors['rules']['#secondary.widget-area']						['background-color']	= '#FFFFCC';
		$bgcolors['rules']['#secondary.widget-area']						['border']				= '1px solid black';
		$bgcolors['rules']['#secondary.widget-area LI']						['font-size']			= '10px';
		$bgcolors['rules']['#secondary.widget-area LI']						['border-bottom']		= '1px solid black';
		$bgcolors['rules']['#secondary.widget-area LI']						['padding']				= '2px 5px';
		$bgcolors['rules']['#secondary.widget-area H3']						['padding']				= '2px 5px';
		$bgcolors['rules']['#secondary.widget-area H3']						['border-bottom']		= '1px solid';
		$bgcolors['rules']['#secondary.widget-area H3']						['margin']				= '0 !important';

		break;
		
	default:
		$bgcolors['colors'][1]						= '#000000'; // main text color
		$bgcolors['colors'][2]						= '#bbbbbb'; // secondairy color, should be readable against the same background
		$bgcolors['colors'][3]						= '#373737'; // link color, should be readable against the same background
		$bgcolors['colors'][4]						= '#bbbbbb'; // link hover color;

		$bgcolors['borders']['border-style']		= 'solid';
		$bgcolors['borders']['border-color']		= '#FFFFFF';
		$bgcolors['borders']['border-width']		= '0 0 0 4px';
		$bgcolors['borders']['border-radius']		= '0';
	
		$bgcolors['rules']['BODY']								['background-color']	= '#FFFFFF !important';

		$bgcolors['rules']['BODY #wp-admin-bar-dgohighlightunseen .ab-submenu > LI, BODY #wp-admin-bar-new-content .ab-submenu > LI'] 	= $bgcolors['borders'];// copy border style to unseen menu 

		$bgcolors['rules']['BODY.home ARTICLE.boxcontainer']	['margin'] 				= '0 0 0 0 !important';		
		$bgcolors['rules']['BODY ARTICLE.boxcontainer:hover']	['box-shadow'] 			= '1px 1px 5px 0px rgba(100, 100, 100, 0.2)';

		$bgcolors['rules']['BODY ARTICLE.open .box .boxface, BODY ARTICLE.dgohighlightunseen .box .boxface']	['background-color']	= $bgcolors['rules']['BODY']['background-color'];		

		$bgcolors['rules']['BODY ARTICLE.boxcontainer.open, BODY ARTICLE.boxcontainer.dgohighlightunseen']	['background-color']	= $bgcolors['rules']['BODY']['background-color'];		
		$bgcolors['rules']['BODY ARTICLE.boxcontainer.open, BODY ARTICLE.boxcontainer.dgohighlightunseen']	['box-shadow'] 			= 'none';
		$bgcolors['rules']['BODY ARTICLE.comment']				['border']	 			= 'none !important';
		$bgcolors['rules']['BODY ARTICLE.comment']				['border-bottom']		= '1px solid #bbb !important';

		$bgcolors['rules']['BODY ARTICLE.sticky .box .boxface']	['border-width']	= '2px';

		$bgcolors['rules']['.entry-content LI SPAN.more']['background-image']		= '-webkit-linear-gradient(left center , rgba(255, 255, 255, 0) 0%, #FFFFFF 100%);';
		$bgcolors['rules']['.entry-content LI SPAN.more']['background-image ']		= '   -moz-linear-gradient(left center , rgba(255, 255, 255, 0) 0%, #FFFFFF 100%);';
		$bgcolors['rules']['.entry-content LI SPAN.more']['background-image  ']		= '    -ms-linear-gradient(left center , rgba(255, 255, 255, 0) 0%, #FFFFFF 100%);';
		$bgcolors['rules']['.entry-content LI SPAN.more']['background-image   ']	= '     -o-linear-gradient(left center , rgba(255, 255, 255, 0) 0%, #FFFFFF 100%);';
		$bgcolors['rules']['.entry-content LI SPAN.more']['background-image    ']	= '        linear-gradient(left center , rgba(255, 255, 255, 0) 0%, #FFFFFF 100%);';
		$bgcolors['rules']['.entry-content LI SPAN.more']['background']				= '       -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(255,255,255,0)), color-stop(100%,rgba(255,255,255,1)));'; /* Chrome,Safari4+ */

		$bgcolors['boxfaces']['posttypes']['page']			['border-color'] = 'rgba('.hexdec('46').','.hexdec('46').','.hexdec('46').',1)';//'#464646';
		$bgcolors['boxfaces']['posttypes']['post']			['border-color'] = 'rgba('.hexdec('F9').','.hexdec('01').','.hexdec('01').',1)';//'#F90101';
		$bgcolors['boxfaces']['posttypes']['dgoforumtopic']	['border-color'] = 'rgba('.hexdec('02').','.hexdec('66').','.hexdec('C8').',1)';//'#0266C8';
		$bgcolors['boxfaces']['posttypes']['dgoquote']		['border-color'] = 'rgba('.hexdec('F2').','.hexdec('B5').','.hexdec('0F').',1)';//'#F2B50F';
		$bgcolors['boxfaces']['posttypes']['dgotransaction']['border-color'] = 'rgba('.hexdec('00').','.hexdec('93').','.hexdec('3B').',1)';//'#00933B';
		$bgcolors['boxfaces']['posttypes']['dgoalbum']		['border-color'] = 'rgba('.hexdec('FF').','.hexdec('00').','.hexdec('FF').',1)';//'#FF00FF';
		
		break;
}

// START OUTPUT ///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Cache control
//get the last-modified-date of this very file
$lastModified=filemtime(__FILE__);
//get a unique hash of this file (etag)
$etagFile = md5_file(__FILE__);
//get the HTTP_IF_MODIFIED_SINCE header if set
$ifModifiedSince=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
//get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
$etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

//set last-modified header
header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
//set etag-header
header("Etag: $etagFile");
//make sure caching is turned on
header('Cache-Control: public');

//check if page has changed. If not, send 304 and exit
if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==$lastModified || $etagHeader == $etagFile)
{
       header("HTTP/1.1 304 Not Modified");
       exit;
}
// set content type
header('Content-Type: text/css');
?>
/**
 * Twentytwelve DGO Child Theme 
 * Skin: <?php echo $skin; ?>	
 * Version: 0.2
 */

/* SET COLORS */
<?php 
	foreach($bgcolors['colors'] AS $num=>$color){
		printf(".color%s{\n",$num);
		printf("\tcolor: %s;\n", $color);
		printf("}\n");
	}
?>
/* SET TEST AND LINK COLORS */
BODY #main{
	<?php printf("color: %s\n", $bgcolors['colors'][1]); ?>	
}
BODY #main A,
BODY #wpadminbar .quicklinks .menupop ul li a,
BODY #wpadminbar .quicklinks .menupop.hover ul li a{
	<?php printf("color: %s\n", $bgcolors['colors'][3]); ?>	
}
BODY #main A:hover{
	<?php printf("color: %s\n", $bgcolors['colors'][4]); ?>	
}

/* BORDER STYLES */
HEADER NAV.main-navigation LI.menu-item,
BODY .comments-area ARTICLE section.comment-content,
BODY .comments-area ARTICLE.comment,
BODY ARTICLE.post-content,
BODY ARTICLE .box .boxface{
<?php
	foreach( $bgcolors['borders'] AS $property=>$value ){
		printf_property($property, $value);
	}
?>
}

/* SET COLOR 2 RULES */
BODY DIV.entry-content > UL > LI > SPAN.author,
BODY DIV.entry-content > UL > LI > SPAN.timestamp,
.uam_access_groups LI.noaccess,
ARTICLE.boxcontainer .box .boxface DL DT,
BODY H3.widget-title{
	color: <?php echo $bgcolors['colors'][2];?>;
}

/* SET COLORS FOR DIFFERENT POST TYPES */
<?php
foreach(array_keys($bgcolors['boxfaces']['posttypes']) AS $posttype){
	printf( "/* %s */\n", $posttype );
	if( 'page' == $posttype ){
		printf( "BODY.%s ARTICLE.%s,\n", $posttype, $posttype);
	}
	printf( "BODY #wp-admin-bar-new-content .ab-submenu > LI#wp-admin-bar-new-%s,\n", $posttype);
	printf( "BODY #wp-admin-bar-dgohighlightunseen .ab-submenu > LI.wp-admin-bar-dgohighlightunseen-%s,\n", $posttype);
	printf( "BODY.single-%s ARTICLE.post-content,\n", $posttype);
	printf( "BODY.single-%s .comments-area ARTICLE.comment:nth-of-type(odd),\n", $posttype);
	printf( "BODY ARTICLE.%s:nth-of-type(odd) .box .boxface{\n", $posttype);
		posttype_background_color( $posttype, 'odd' );
		posttype_extras($posttype);
	printf( "}\n");
	printf( "BODY.single-%s .comments-area ARTICLE.comment:nth-of-type(even),\n", $posttype);
	printf( "BODY ARTICLE.%s:nth-of-type(even) .box .boxface{\n", $posttype);
		posttype_background_color( $posttype, 'even' );
		posttype_extras($posttype);
	printf( "}\n");
}
?>

/* comment excerpts: create overflow fade effect for text-lines that are too long for the content
 * this element will be positioned over the element that contains the text, and positoined to the right
 */
BODY ARTICLE.post DIV.entry-content > UL > LI > SPAN.more{
<?php 
	// use the color, but overrule the fading percentages		
	posttype_background_color( 'post', 'odd', true );
	?>
}
BODY ARTICLE.dgoforumtopic DIV.entry-content > UL > LI > SPAN.more{
<?php 
	// use the color, but overrule the fading percentages		
	posttype_background_color( 'dgoforumtopic', 'odd', true );
	?>
}

/* CUSTOM RULES */
<?php
if( isset($bgcolors['rules']) ){
	printf_rules($bgcolors['rules']);
}

// end of output
exit;

// FUNCTIONS /////////////////////////////////////////////////////////////////////////////////////////
function printf_rules($rules){
	foreach($rules as $selector=>$properties){
		printf("%s {\n", $selector);
		printf_properties($properties);
		printf("}\n");
	}
}
function printf_properties($properties){
	foreach($properties as $property=>$value){
		printf_property($property, $value);
	}
}
function printf_property($property, $value){
	printf("\t%s: %s;\n", $property, $value);
}


function setRGBValues(&$color){
	global $bgcolors;
	// translate hex codes to rgb
	if(isset($color['hex'])){
		// trim # from the hex string (optional)
		$hex = trim( $color['hex'], " #" );
		// hex color given, translate to rgb values, to use down the road
		$color = array_merge($color, array_combine( str_split('rgb',1), array_map( 'hexdec', str_split($hex,2) ) ) );
	}
}

function posttype_background_color($posttype, $oddeven, $moremask = false){
	global $bgcolors;
	$verdor_prefixes = array( '-webkit-','-moz-','-ms-','-o-', '');
	
	if(isset($bgcolors['boxfaces']['posttypes'][$posttype])){
		foreach($bgcolors['boxfaces']['posttypes'][$posttype] AS $property=>$value ){
			if(is_array($value)){
				// not a simple property, do special stuff
				if(isset($value['linear-gradient'])){
					// make gradient
					$rgba1 = $value['linear-gradient'][$oddeven][1];
					$rgba2 = $value['linear-gradient'][$oddeven][2];
					// translate hex to rgb, store in self array
					setRGBValues($rgba1);
					setRGBValues($rgba2);
					if($moremask){
						// use the color, but overrule the precentages
						$rgba1['a'] = '0'; 	$rgba1['stop'] = '0%'; 
						$rgba2['a'] = '1'; 	$rgba2['stop'] = '100%'; 
					}
					foreach($verdor_prefixes as $prefix){
						printf_property($property, sprintf('%slinear-gradient(left, rgba(%s,%s,%s, %s) %s, rgba(%s,%s,%s, %s) %s)',$prefix, $rgba1['r'], $rgba1['g'], $rgba1['b'], $rgba1['a'], $rgba1['stop'], $rgba2['r'], $rgba2['g'], $rgba2['b'], $rgba2['a'], $rgba2['stop'] ));
					}
				    printf($property, sprintf('-webkit-gradient(linear, left 50%, right 50%, color-stop(0, rgba(%s,%s,%s, %s) ), color-stop(1, rgba(%s,%s,%s, %s) ) );', $rgba1['r'], $rgba1['g'], $rgba1['b'], $rgba1['a'], $rgba1['stop'], $rgba2['r'], $rgba2['g'], $rgba2['b'], $rgba2['a'], $rgba2['stop'] ));
				}
			}else{
				printf_property($property, $value);
			}
		}
	}
}

function posttype_extras($posttype){
	global $bgcolors;
	if( isset($bgcolors['boxfaces']['posttypes'][$posttype]['extra']) ){
		echo $bgcolors['boxfaces']['posttypes'][$posttype]['extra'];
	}
}
?>