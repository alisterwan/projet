/* Copyright 2009-2010 Taco Software. All rights reserved.
 * http://tacosw.com
 *
 * This file is part of the Component Library included in Taco HTML Edit.
 * Licensed users of Taco HTML Edit may modify and use this source code 
 * for their web development (including commercial projects), as long as 
 * this copyright notice is retained.
 *
 * The contents of this file may not be published in a format intended
 * for access by other humans, so you may not put code examples on a
 * web site with all or part of the contents of this file, and you may
 * not publish the contents of this file in a printed format.
 */

//Constant for identifying Image Zoom links
var TSW_IMAGE_ZOOM = 'tswImageZoom';
var TSW_IMAGE_ZOOM_MARGIN = 50;

//Initialization vars
var tswImageZoomHasInited = false;
var tswImageZoomAnimatedDiv;
var tswImageZoomImg;
var tswImageZoomCloseBoxImg;
var tswImageZoomDropShadowDiv;
var tswImageZoomSpinnerDiv;
var tswImageZoomSpinnerImg;

//Track which images have been loaded
//These variables are maps from URLS to Image objects.
//These can include spinner images
var tswImageZoomLoadedImages = new Object();
var tswImageZoomLoadingImages = new Object();

//Animation vars
var tswImageZoomIsAnimating = false;
var tswImageZoomAnimationAnchorElement; //The anchor element that initiated the image zoom
var tswImageZoomAnimationStartDate; //The date that animation of image zoom starts
var tswImageZoomAnimationStartRect; //The initial rect of image zoom animation
var tswImageZoomAnimationEndRect; //The final rect of image zoom animation
var tswImageZoomAnimationIntervalId; //The id returned by the setInterval call
var tswImageZoomMovementComplete = false; //Is the movement portion of the animation complete
var tswImageZoomSpinnerFrame = 1;

//An Image object for the currently animating image
var tswImageZoomAnimatingImage;

/* Initializes the Image Zoom, by adding a div to the document for
 * showing the animation (hidden by default). Also, adds an onclick
 * event for anchors with rel="tswImageZoom".
 */
function _tswImageZoomSetup()
{
	if(tswImageZoomHasInited)
		return;
	
	tswImageZoomHasInited = true;
	if(document.getElementsByTagName('body').length == 0)
	{
		var errorMsg = 'For Taco HTML Edit Image Zoom to function correctly, your HTML document '
		+ 'must have a body tag.';
		document.write(errorMsg);
		alert(errorMsg);
		return;
	}
	
	//Setup div for the zoom animation
	var bodyElement = document.getElementsByTagName('body').item(0);
	tswImageZoomAnimatedDiv = document.createElement('div');
	tswImageZoomAnimatedDiv.style.display = 'none';
	tswImageZoomAnimatedDiv.style.position = 'absolute';
	tswImageZoomAnimatedDiv.style.cursor = 'pointer';
	tswImageZoomAnimatedDiv.style.zIndex = 16000;
	tswImageZoomAnimatedDiv.style.padding = '16px';
	bodyElement.appendChild(tswImageZoomAnimatedDiv);
	
	tswImageZoomImg = document.createElement('img');
	tswImageZoomImg.setAttribute('border', '0');
	tswImageZoomImg.style.display = 'block';
	tswImageZoomAnimatedDiv.appendChild(tswImageZoomImg);
	
	//Close box image
	tswImageZoomCloseBoxImg = document.createElement('div');
	tswImageZoomCloseBoxImg.style.position = 'absolute';
	
	if(TSWBrowserDetect.browserMatches('Explorer', null, 6))
	{
		//This works in IE6
		var image = document.createElement('span');
		image.style.display = 'inline-block';
		image.style.width = '30px';
		image.style.height = '30px';
		image.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='http://etudiant.univ-mlv.fr/~jwankutk/projet/js/Images/TSWImageZoomCloseBox.png', sizingMethod='crop')";
		tswImageZoomCloseBoxImg.appendChild(image);
	}
	else
	{
		var image = document.createElement('img');
		image.setAttribute('src', 'js/Images/TSWImageZoomCloseBox.png');
		image.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='http://etudiant.univ-mlv.fr/~jwankutk/projet/js/Images/TSWImageZoomCloseBox.png', sizingMethod='crop')";
		image.style.display = 'block';
		tswImageZoomCloseBoxImg.appendChild(image);
	}
	tswUtilsSetDimensions(tswImageZoomCloseBoxImg,
						  [2, 4, 30, 30]);
	
	tswImageZoomCloseBoxImg.style.display = 'none';
	tswImageZoomCloseBoxImg.style.zIndex = 16001;
	tswImageZoomAnimatedDiv.appendChild(tswImageZoomCloseBoxImg);
	
	//Setup div for the spinner animation
	tswImageZoomSpinnerDiv = document.createElement('div');
	tswImageZoomSpinnerDiv.style.display = 'none';
	tswImageZoomSpinnerDiv.style.position = 'absolute';
	tswImageZoomSpinnerDiv.style.zIndex = 15999;
	tswImageZoomSpinnerDiv.style.backgroundColor = '#ffffff';
	tswUtilsSetOpacity(tswImageZoomSpinnerDiv, 0.6);
	bodyElement.appendChild(tswImageZoomSpinnerDiv);
	
	tswImageZoomSpinnerImg = document.createElement('img');
	tswImageZoomSpinnerImg.style.display = 'block';
	tswImageZoomSpinnerImg.setAttribute('border', '0');
	tswImageZoomSpinnerImg.setAttribute('src', tswUtilsGetResourcesDirectory()+'Images/TSWSpinner1.png');
	tswImageZoomSpinnerImg.style.position = 'relative';
	tswImageZoomSpinnerDiv.appendChild(tswImageZoomSpinnerImg);
	
	//load spinner images
	for(var i=0; i<12; i++)
	{
		var imageUrl = tswUtilsGetResourcesDirectory()+'Images/TSWSpinner'+i+'.png';
		var imageObject = new Image;
		tswImageZoomLoadingImages[imageUrl] = imageObject;
		tswUtilsPreloadImage(imageObject, imageUrl, _tswImageZoomSpinnerLoaded, null);
	}
	
}

tswUtilsAddEventHandler(window, "load", _tswImageZoomSetup);

function _tswImageZoomResetAnimation()
{
	clearInterval(tswImageZoomAnimationIntervalId);
	tswImageZoomAnimatedDiv.onclick = null;
	tswImageZoomIsAnimating = false;
	tswImageZoomMovementComplete = false;
	tswImageZoomAnimatedDiv.style.display = 'none';
	if(tswImageZoomDropShadowDiv != null)
	{
		tswImageZoomAnimatedDiv.removeChild(tswImageZoomDropShadowDiv);
		tswImageZoomDropShadowDiv = null;
	}
	tswImageZoomSpinnerDiv.style.display = 'none';
	tswImageZoomSpinnerFrame = 1;
	tswImageZoomCloseBoxImg.style.display = 'none';
	tswImageZoomCloseBoxImg.style.visibility = 'hidden';
}

function tswImageZoomAnimate(anchorElement)
{
	if(!tswImageZoomHasInited)
	{
		_tswImageZoomSetup();
	}
	
	//cleanup anything from previous animations
	_tswImageZoomResetAnimation();
	
	tswImageZoomAnimationAnchorElement = anchorElement;
	
	tswImageZoomIsAnimating = true;
	var imageUrl = anchorElement.href;
	if(tswImageZoomLoadedImages[imageUrl] != null)
	{
		//image has already loaded; perform animation
		tswImageZoomAnimatingImage = tswImageZoomLoadedImages[imageUrl];
		_tswImageZoomImageLoaded(tswImageZoomAnimatingImage);
	}
	else if(tswImageZoomLoadingImages[imageUrl] != null)
	{
		//animate spinner
		_tswImageZoomShowSpinner(anchorElement);
		
		//image is currently loading; wait for callback to do animation
		tswImageZoomAnimatingImage = tswImageZoomLoadingImages[imageUrl];
	}
	else
	{
		//animate spinner
		_tswImageZoomShowSpinner(anchorElement);
		
		//image needs to be loaded; load image and wait for callback
		tswImageZoomAnimatingImage = new Image;
		tswImageZoomLoadingImages[imageUrl] = tswImageZoomAnimatingImage;
		tswUtilsPreloadImage(tswImageZoomAnimatingImage, imageUrl, _tswImageZoomImageLoaded, null);
	}
	
	return false;
}

function tswImageZoomNumberPreloading()
{
	var count = 0;
	for(var imageUrl in tswImageZoomLoadingImages)
	{
		if(tswImageZoomLoadedImages[imageUrl] == null)
			count++;
	}
	return count;
}

function tswImageZoomPreloadImage(anchorElement)
{
	//Only preload if there is at most 2 currently loading
	if(tswImageZoomNumberPreloading() > 2)
		return;

	var imageUrl = anchorElement.href;
	if(tswImageZoomLoadingImages[imageUrl] == null && tswImageZoomLoadedImages[imageUrl] == null)
	{
		var imageObject = new Image;
		tswImageZoomLoadingImages[imageUrl] = imageObject;
		tswUtilsPreloadImage(imageObject, imageUrl, _tswImageZoomImageLoaded, null);
	}
}

function _tswImageZoomShowSpinner(anchorElement)
{
	var anchorRect = _tswImageZoomGetAnchorRect(anchorElement);
	tswUtilsSetDimensions(tswImageZoomSpinnerDiv, anchorRect);
	
	var spinnerSize = 32;
	if(anchorRect[2] < spinnerSize*2)
		spinnerSize = anchorRect[2]/2;
	if(anchorRect[3] < spinnerSize*2)
		spinnerSize = anchorRect[3]/2;
	
	tswImageZoomSpinnerImg.style.left = ((anchorRect[2]-spinnerSize)/2)+'px';
	tswImageZoomSpinnerImg.style.top = ((anchorRect[3]-spinnerSize)/2)+'px';
	tswImageZoomSpinnerImg.style.width = spinnerSize+'px';
	tswImageZoomSpinnerImg.style.height = spinnerSize+'px';
	
	tswImageZoomSpinnerDiv.style.display = 'block';
	clearInterval(tswImageZoomAnimationIntervalId);
	tswImageZoomAnimationIntervalId = setInterval('_tswImageZoomDoSpinnerAnimation()', 100);
}

function _tswImageZoomDoSpinnerAnimation()
{
	var imageUrl = tswUtilsGetResourcesDirectory()+'Images/TSWSpinner'+tswImageZoomSpinnerFrame+'.png';
	if(tswImageZoomLoadedImages[imageUrl] != null)
	{
		//image loaded; display it
		tswImageZoomSpinnerImg.setAttribute('src', imageUrl);
		tswImageZoomSpinnerFrame = (tswImageZoomSpinnerFrame + 1) % 12;
	}
}

function _tswImageZoomSpinnerLoaded(image)
{
	//find the loading image that matches the image object, and set loaded image
	for (var imageUrl in tswImageZoomLoadingImages) 
	{
		if(tswImageZoomLoadingImages[imageUrl] == image)
		{
			tswImageZoomLoadedImages[imageUrl] = image;
		}
	}
}

//Gets the coordinates [x,y,width,height] of the anchor element initiating
//a zoom.
function _tswImageZoomGetAnchorRect(anchorElement)
{
	var imgElementArray = anchorElement.getElementsByTagName('img');
	var startBlock = imgElementArray.length > 0 ? imgElementArray.item(0) : anchorElement;
	var startBlockPosition = tswUtilsGetAbsolutePosition(startBlock);
	return [startBlockPosition[0], startBlockPosition[1], startBlock.offsetWidth, startBlock.offsetHeight];
}

function _tswImageZoomImageLoaded(image)
{
	tswImageZoomLoadedImages[image.src] = image;
	
	//Check that the loaded image is the one that we should be animating
	if(!tswImageZoomIsAnimating || image != tswImageZoomAnimatingImage)
		return;
	
	//clear the spinner
	clearInterval(tswImageZoomAnimationIntervalId);
	tswImageZoomSpinnerDiv.style.display = 'none';
	
	//Image has loaded, so animate the zoom effect.
	tswImageZoomAnimationStartDate = new Date();
	
	//We will first be entering the movement portion of the animation
	tswImageZoomMovementComplete = false;
	
	//Compute the initial rect for animation
	tswImageZoomAnimationStartRect = _tswImageZoomGetAnchorRect(tswImageZoomAnimationAnchorElement);
	
	//set height relative to width to match image dimensions
	if(image.width > 0)
		tswImageZoomAnimationStartRect[3] = tswImageZoomAnimationStartRect[2] * (image.height / image.width);
	
	//Compute the final rect for animation
	//We center the image, scaling it down if needed to fit on screen
	var visibleRect = tswUtilsGetVisibleRect();
	
	//inset rect by margin if possible
	if(visibleRect[2] > 2*TSW_IMAGE_ZOOM_MARGIN)
	{ 
		visibleRect[0] += TSW_IMAGE_ZOOM_MARGIN;
		visibleRect[2] -= (2*TSW_IMAGE_ZOOM_MARGIN);
	}
	if(visibleRect[3] > 2*TSW_IMAGE_ZOOM_MARGIN)
	{ 
		visibleRect[1] += TSW_IMAGE_ZOOM_MARGIN;
		visibleRect[3] -= (2*TSW_IMAGE_ZOOM_MARGIN);
	}
	
	//determine final image width/height, scale if needed
	var endWidth = image.width;
	var endHeight = image.height;
	if((endWidth > visibleRect[2] 
		|| endHeight > visibleRect[3]) 
	   && visibleRect[2] > 0 && visibleRect[3] > 0)
	{
		if(endWidth/visibleRect[2] > endHeight/visibleRect[3])
		{
			//scale down by width
			endWidth = visibleRect[2];
			endHeight = image.height * endWidth/image.width;
		}
		else
		{
			//scale down by height
			endHeight = visibleRect[3];
			endWidth = image.width * endHeight/image.height;
		}
	}
	
	tswImageZoomAnimationEndRect = [
									visibleRect[0] + (visibleRect[2] - endWidth)/2,
									visibleRect[1] + (visibleRect[3] - endHeight)/2,
									endWidth,
									endHeight
									];
	if(tswImageZoomAnimationEndRect[0] < 0)
		tswImageZoomAnimationEndRect[0] = 0;
	if(tswImageZoomAnimationEndRect[1] < 0)
		tswImageZoomAnimationEndRect[1] = 0;
	
	
	tswImageZoomImg.setAttribute('src', image.src);
	
	_tswImageZoomDoAnimation(); //make an initial call to start animation
	tswImageZoomAnimationIntervalId = setInterval('_tswImageZoomDoAnimation()', 25);
}

function _tswImageZoomDoAnimation()
{
	var currentDate = new Date();
	
	//delta is the percentage progress of the animation
	//Total animation duration is set to 800 milliseconds
	var delta = (currentDate.getTime() - tswImageZoomAnimationStartDate.getTime()) / 800.0;
	if(delta >= 1.0)
	{
		delta = 1.0;
		
		//This is the end of the animation; stop the animation timer
		clearInterval(tswImageZoomAnimationIntervalId);
		tswImageZoomAnimatedDiv.onclick = function() { tswImageZoomHide(); };
		tswImageZoomIsAnimating = false;
	}
	
	//Movement phase of animation
	if(!tswImageZoomMovementComplete)
	{
		var deltaForMovement = delta/0.8;
		if(deltaForMovement >= 1.0)
		{
			//We are completing the movement phase of the animation
			deltaForMovement = 1.0;
			tswImageZoomMovementComplete = true;
		}
		
		var movementProgress = Math.sin(deltaForMovement*Math.PI/2.0);
		
		tswImageZoomAnimatedDiv.style.left = 
		(1.0 - movementProgress)*tswImageZoomAnimationStartRect[0] + movementProgress*tswImageZoomAnimationEndRect[0]+'px';
		tswImageZoomAnimatedDiv.style.top = 
		(1.0 - movementProgress)*tswImageZoomAnimationStartRect[1] + movementProgress*tswImageZoomAnimationEndRect[1]+'px';
		tswImageZoomAnimatedDiv.style.width = 
		(1.0 - movementProgress)*tswImageZoomAnimationStartRect[2] + movementProgress*tswImageZoomAnimationEndRect[2]+'px';
		tswImageZoomAnimatedDiv.style.height = 
		(1.0 - movementProgress)*tswImageZoomAnimationStartRect[3] + movementProgress*tswImageZoomAnimationEndRect[3]+'px';
		tswImageZoomImg.style.width = tswImageZoomAnimatedDiv.style.width;
		tswImageZoomImg.style.height = tswImageZoomAnimatedDiv.style.height;	
		tswUtilsSetOpacity(tswImageZoomAnimatedDiv, deltaForMovement/1.25 + 0.2);
		
		tswImageZoomAnimatedDiv.style.display = 'block';
	}
	
	//Drop Shadow and Close Button animation
	if(tswImageZoomMovementComplete)
	{
		tswImageZoomAnimatedDiv.style.filter = '';
		
		//No drop shadow in IE6,7 or ie8 quirks mode
		var isIE = TSWBrowserDetect.browserMatches('Explorer', null, null);
		if(!TSWBrowserDetect.browserMatches('Explorer', null, 7) && (!isIE || document.compatMode != 'BackCompat'))
		{
			if(tswImageZoomDropShadowDiv == null)
			{
				var boxShadowPropertyName = TSWBrowserDetect.browserPropertyName('WebkitBoxShadow');
				
				if(boxShadowPropertyName != null)
				{
					tswImageZoomDropShadowDiv = document.createElement('div');
					tswImageZoomDropShadowDiv.style.position = 'absolute';
					tswUtilsSetDimensions(tswImageZoomDropShadowDiv,
										  [16, 16, tswImageZoomAnimationEndRect[2], tswImageZoomAnimationEndRect[3]]);
					tswUtilsSetOpacity(tswImageZoomDropShadowDiv, 0.0);
					tswImageZoomDropShadowDiv.style[boxShadowPropertyName] = '0px 5px 30px #000';
					tswImageZoomAnimatedDiv.appendChild(tswImageZoomDropShadowDiv);
				}
				else
				{
					tswImageZoomDropShadowDiv = document.createElement('div');
					tswImageZoomDropShadowDiv.style.position = 'absolute';
					tswUtilsSetDimensions(tswImageZoomDropShadowDiv,
										  [16, 16, tswImageZoomAnimationEndRect[2], tswImageZoomAnimationEndRect[3]]);
					tswUtilsSetOpacity(tswImageZoomDropShadowDiv, 0.0);
					tswImageZoomAnimatedDiv.appendChild(tswImageZoomDropShadowDiv);
					
					var topLeftDiv = document.createElement('div');
					topLeftDiv.style.position = 'absolute';
					if(isIE)
						topLeftDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+tswUtilsGetResourcesDirectory()+"Images/TSWDropShadowTopLeft.png', sizingMethod='crop')";
					else
						topLeftDiv.style.backgroundImage = 'url(\''+tswUtilsGetResourcesDirectory()+'Images/TSWDropShadowTopLeft.png\')';
					tswUtilsSetDimensions(topLeftDiv,
										  [-14, -12, 32, 30]);
					tswImageZoomDropShadowDiv.appendChild(topLeftDiv);
					
					var topDiv = document.createElement('div');
					topDiv.style.position = 'absolute';
					if(isIE)
						topDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+tswUtilsGetResourcesDirectory()+"Images/TSWDropShadowTop.png', sizingMethod='scale')";
					else
						topDiv.style.backgroundImage = 'url(\''+tswUtilsGetResourcesDirectory()+'Images/TSWDropShadowTop.png\')';
					tswUtilsSetDimensions(topDiv,
										  [18, -12, tswImageZoomAnimationEndRect[2] - 36, 30]);
					tswImageZoomDropShadowDiv.appendChild(topDiv);
					
					var topRightDiv = document.createElement('div');
					topRightDiv.style.position = 'absolute';
					if(isIE)
						topRightDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+tswUtilsGetResourcesDirectory()+"Images/TSWDropShadowTopRight.png', sizingMethod='crop')";
					else
						topRightDiv.style.backgroundImage = 'url(\''+tswUtilsGetResourcesDirectory()+'Images/TSWDropShadowTopRight.png\')';
					tswUtilsSetDimensions(topRightDiv,
										  [tswImageZoomAnimationEndRect[2] - 18, -12, 32, 30]);
					tswImageZoomDropShadowDiv.appendChild(topRightDiv);
					
					var leftDiv = document.createElement('div');
					leftDiv.style.position = 'absolute';
					if(isIE)
						leftDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+tswUtilsGetResourcesDirectory()+"Images/TSWDropShadowLeft.png', sizingMethod='scale')";
					else
						leftDiv.style.backgroundImage = 'url(\''+tswUtilsGetResourcesDirectory()+'Images/TSWDropShadowLeft.png\')';
					tswUtilsSetDimensions(leftDiv,
										  [-14, 18, 32, tswImageZoomAnimationEndRect[3] - 30]);
					tswImageZoomDropShadowDiv.appendChild(leftDiv);
					
					var rightDiv = document.createElement('div');
					rightDiv.style.position = 'absolute';
					if(isIE)
						rightDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+tswUtilsGetResourcesDirectory()+"Images/TSWDropShadowRight.png', sizingMethod='scale')";
					else
						rightDiv.style.backgroundImage = 'url(\''+tswUtilsGetResourcesDirectory()+'Images/TSWDropShadowRight.png\')';
					tswUtilsSetDimensions(rightDiv,
										  [tswImageZoomAnimationEndRect[2] - 18, 18, 32, tswImageZoomAnimationEndRect[3] - 30]);
					tswImageZoomDropShadowDiv.appendChild(rightDiv);
					
					var bottomLeftDiv = document.createElement('div');
					bottomLeftDiv.style.position = 'absolute';
					if(isIE)
						bottomLeftDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+tswUtilsGetResourcesDirectory()+"Images/TSWDropShadowBottomLeft.png', sizingMethod='crop')";
					else
						bottomLeftDiv.style.backgroundImage = 'url(\''+tswUtilsGetResourcesDirectory()+'Images/TSWDropShadowBottomLeft.png\')';
					tswUtilsSetDimensions(bottomLeftDiv,
										  [-14, tswImageZoomAnimationEndRect[3] - 12, 32, 30]);
					tswImageZoomDropShadowDiv.appendChild(bottomLeftDiv);
					
					var bottomDiv = document.createElement('div');
					bottomDiv.style.position = 'absolute';
					if(isIE)
						bottomDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+tswUtilsGetResourcesDirectory()+"Images/TSWDropShadowBottom.png', sizingMethod='scale')";
					else
						bottomDiv.style.backgroundImage = 'url(\''+tswUtilsGetResourcesDirectory()+'Images/TSWDropShadowBottom.png\')';
					tswUtilsSetDimensions(bottomDiv,
										  [18, tswImageZoomAnimationEndRect[3] - 12, tswImageZoomAnimationEndRect[2] - 36, 30]);
					tswImageZoomDropShadowDiv.appendChild(bottomDiv);
					
					var bottomRightDiv = document.createElement('div');
					bottomRightDiv.style.position = 'absolute';
					if(isIE)
						bottomRightDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+tswUtilsGetResourcesDirectory()+"Images/TSWDropShadowBottomRight.png', sizingMethod='crop')";
					else
						bottomRightDiv.style.backgroundImage = 'url(\''+tswUtilsGetResourcesDirectory()+'Images/TSWDropShadowBottomRight.png\')';
					tswUtilsSetDimensions(bottomRightDiv,
										  [tswImageZoomAnimationEndRect[2] - 18, tswImageZoomAnimationEndRect[3] - 12, 32, 30]);
					tswImageZoomDropShadowDiv.appendChild(bottomRightDiv);
				}
			}
			
			tswUtilsSetOpacity(tswImageZoomDropShadowDiv, (delta - 0.8)/0.2);
		}
		
		tswImageZoomCloseBoxImg.style.display = 'block';
		tswImageZoomCloseBoxImg.style.visibility = '';
		tswUtilsSetOpacity(tswImageZoomCloseBoxImg, (delta - 0.8)/0.2);
	}
}

function tswImageZoomHide()
{
	if(tswImageZoomIsAnimating)
		return;
	
	if(TSWBrowserDetect.browserMatches('Explorer', null, 7))
		tswImageZoomCloseBoxImg.style.visibility = 'hidden';
	
	tswImageZoomAnimatedDiv.onclick = null;
	tswImageZoomIsAnimating = true;
	
	tswImageZoomAnimationStartDate = new Date();
	tswImageZoomAnimationIntervalId = setInterval('_tswImageZoomDoHideAnimation()', 25);
}

function _tswImageZoomDoHideAnimation()
{
	var currentDate = new Date();
	
	//delta is the percentage progress of the animation
	//Total animation duration is set to 0.3 seconds
	var delta = (currentDate.getTime() - tswImageZoomAnimationStartDate.getTime()) / 300.0;
	if(delta >= 1.0)
	{
		delta = 1.0;
		
		//This is the end of the animation; stop the animation timer
		_tswImageZoomResetAnimation();
	}
	
	tswUtilsSetOpacity(tswImageZoomAnimatedDiv, 1.0 - delta);
}


/* The checksum below is for internal use by Taco HTML Edit, 
   to detect if a component file has been modified.
   TacoHTMLEditChecksum: 462DAAB7 */