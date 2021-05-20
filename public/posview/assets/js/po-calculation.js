// Reusable helper functions
const calculatePPinGst = (ppExGst, gst) => {
  ppExGst = parseFloat(ppExGst);
  gst  = parseFloat(gst);
  return (ppExGst + ( ppExGst * gst / 100 )).toFixed(2); 
}
const calculatePPExGst = (ppInGst, gst) => {
  ppInGst = parseFloat(ppInGst);
  gst = parseFloat(gst);
  //return (ppInGst * ( gst / 100)).toFixed(2);
  return ((ppInGst/(gst+100))*100).toFixed(2);
}

/*************SELLING PRICE*************/
const calculateSPinGst = (spExGst, spgst) => {
  spExGst = parseFloat(spExGst);
  spgst  = parseFloat(spgst);
  return (spExGst + ( spExGst * spgst / 100 )).toFixed(2); 
}

const calculateSPExGst = (spInGst, spgst) => {
  spInGst = parseFloat(spInGst);
  spgst = parseFloat(spgst);
  //return (ppInGst * ( gst / 100)).toFixed(2);
  return ((spInGst/(spgst+100))*100).toFixed(2);
}
/*************SELLING PRICE*************/

/*************Checking PP and SP **************/
const checkmarkup = (sp, pp, markup) => {
  sp = parseFloat(sp);
  pp = parseFloat(pp);
  markup= parseFloat(markup);
  //return (ppInGst * ( gst / 100)).toFixed(2);
  markupcalc= (pp + markup).toFixed(2);
  
  if(markupcalc <= sp)
  {
	  /*msg = 'sp is greater';
	  return [markup, msg];*/
	   return markup;
  	
  }
  else
  {
	  /*msg = 'sp is less';
	  return [markup, msg];*/
	  return markup;
  	
  }
  
}
/*************Checking PP and SP **************/

// Our use case
/*const $ppexgst = $('input[name="ppexgst[]"]'),
      $ppgst = $('input[name="ppgst[]"]'), 
      $ppingst = $('input[name="ppingst[]"]'),
	  $spexgst = $('input[name="spexgst[]"]'),
	  $spgst = $('input[name="spgst[]"]'),
	  $spingst = $('input[name="spingst[]"]'),
	  $markup= $('input[name="markup[]"]');*/
const $ppexgst = $('.ppexgst'),
      $ppgst = $('.ppgst'), 
      $ppingst = $('.ppingst'),
    $spexgst = $('.spexgst'),
    $spgst = $('.spgst'),
    $spingst = $('.spingst'),
    $markup= $('.markup'); 
    
$ppexgst.add( $ppgst ).on('input', () => { // List and Discount inputs events
  let gst = $ppgst.val();              // Default to List price
  if ( $ppexgst.val().length ) {          // if value is entered- calculate sale price
    ppingst = calculatePPinGst($ppexgst.val(), $ppgst.val());
  }
  $ppingst.val( ppingst );
});

$ppingst.add( $ppgst ).on('input', () => { // List and Discount inputs events
  let gst = $ppgst.val();              // Default to List price
  if ( $ppingst.val().length ) {          // if value is entered- calculate sale price
    ppexgst = calculatePPExGst($ppingst.val(), $ppgst.val());
  }
  $ppexgst.val( ppexgst );
});
/*****************/
$spexgst.add( $spgst ).on('input', () => { // List and Discount inputs events
  let spgst = $spgst.val();              // Default to List price
  if ( $spexgst.val().length ) {          // if value is entered- calculate sale price
    spingst = calculateSPinGst($spexgst.val(), $spgst.val());
  }
  $spingst.val( spingst );
});

$spingst.add( $spgst ).on('input', () => { // List and Discount inputs events
  let spgst = $spgst.val();              // Default to List price
  if ( $spingst.val().length ) {          // if value is entered- calculate sale price
    spexgst = calculateSPExGst($spingst.val(), $spgst.val());
  }
  $spexgst.val( spexgst );
});

$spingst.add( $ppingst ).on('input', () => { // List and Discount inputs events
  let ppingst = $ppingst.val(); 
  let spingst = $spingst.val();
  let markup = $markup.val();             // Default to List price
  if ( $markup.val().length ) {          // if value is entered- calculate sale price
    markup = checkmarkup($spingst.val(), $ppingst.val(), $markup.val());
  }
  $markup.val( markup );
  $msg.val( msg );
});
  

/*$ppingst.on('input', () => {      // Sale input events
  let gst = $ppgst.val();                // Default to 0%
  if ( $ppingst.val().length ) {  // if value is entered- calculate the discount
    ppexgst = calculatePPExGst($ppgst.val(), $ppexgst.val());
  }
  $ppexgst.val( ppexgst );
});*/

// Init!
$ppingst.trigger('.ppingst');
$ppexgst.trigger('.ppexgst');
$spingst.trigger('.spingst');
$spexgst.trigger('.spexgst');