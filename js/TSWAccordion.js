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



var tswAccordionMap = new Object(); //maps rotating content id to TSWRotatingContent object;

//Returns the TSWRotatingContent object for an id, creating the object
//if necessary.
function tswAccordionGetForId(id)
{
	var accordion = tswAccordionMap[id];
	if(accordion == null)
	{
		accordion = new TSWAccordion(id);
		tswAccordionMap[id] = accordion;
	}
	return accordion;
}

//TSWAccordion is a javascript object that represents
//the accordion component in the HTML document. The
//constructor takes the id of the object.
function TSWAccordion(id)
{
	this.id = id;
	this.selectedSection = 0; //The section that is currently selected
	this.animationDuration = 700; //Duration of animation in milliseconds
	
	//The total number of panels 
	this.sections = new Array();
	
	var accordionElement = document.getElementById(this.id);
	var sectionElements = TSWDomUtils.getChildrenWithTagName(this.getAccordionElement(), 'div'); 
	for(var i=0; i<sectionElements.length; i++)
	{
		var section = new TSWAccordionSection(this, sectionElements[i], i);
		this.sections.push(section);
		
		section.element.className = (this.selectedSection == i) ? 'tswAccordionActiveSection' : 'tswAccordionInactiveSection';
		section.element.style.zIndex = (i+1);
		this.generateOnClick(section, this, i);
	}
	
	this.positionSections(false);
	
	this.animationIntervalId = null; //Identifies the interval timer being used for the animation
};

TSWAccordion.prototype.setMouseOver = function(bool)
{
	for(var i=0; i<this.sections.length; i++)
	{
		var section = this.sections[i];
		if(bool)
		{
			this.generateMouseOver(section, this, i);
		}
		else
		{
			section.getTitleElement().onmouseover = null;
		}
	}
};

TSWAccordion.prototype.getAccordionElement = function()
{
	return document.getElementById(this.id);
};

TSWAccordion.prototype.generateOnClick = function(section, accordion, index)
{
	section.getTitleElement().onclick = function() { accordion.selectSection(index); };
};

TSWAccordion.prototype.generateMouseOver = function(section, accordion, index)
{
	section.getTitleElement().onmouseover = function() { accordion.selectSection(index); };
};

TSWAccordion.prototype.getAccordionHeight = function()
{
	return document.getElementById(this.id).offsetHeight;
};

TSWAccordion.prototype.selectSection = function(sectionIndex)
{
	this.sections[this.selectedSection].element.className = 'tswAccordionInactiveSection';
	this.selectedSection = sectionIndex;
	this.sections[this.selectedSection].element.className = 'tswAccordionActiveSection';
	this.positionSections(true);
};

TSWAccordion.prototype.positionSections = function(isAnimated)
{
	var currentDate = new Date();
	var pos = 0;
	for(var i=0; i<=this.selectedSection; i++)
	{
		var section = this.sections[i];
		if(isAnimated)
			section.animateToPosition(pos, currentDate);
		else
			section.moveToPosition(pos);
		pos += (section.getTitleElement().offsetHeight - section.getTitleBottomBorderWidth());
	}
	
	pos = this.getAccordionHeight();
	for(var i=this.sections.length-1; i>this.selectedSection; i--)
	{
		var section = this.sections[i];
		
		if(i == this.sections.length - 1)
			pos -= section.getTitleElement().offsetHeight;
		else
			pos -= (section.getTitleElement().offsetHeight - section.getTitleBottomBorderWidth());
		
		
		if(isAnimated)
			section.animateToPosition(pos, currentDate);
		else
			section.moveToPosition(pos);
	}
	
	if(isAnimated)
		this.startAnimation();
};

TSWAccordion.prototype.startAnimation = function()
{
	if(this.animationIntervalId == null)
	{
		this.animationIntervalId = setInterval("_tswAccordionAnimate('"+this.id+"')", 25);
	}
};

function _tswAccordionAnimate(accordionId)
{
	tswAccordionGetForId(accordionId).continueAnimation();
}

TSWAccordion.prototype.continueAnimation = function()
{
	var isAnimating = false; //Is there a section currently animating
	var currentDate = new Date();
	
	for(var i=0; i<this.sections.length; i++)
	{
		var section = this.sections[i];
		if(section.isAnimating)
		{
			isAnimating = true;
			section.continueAnimation(currentDate);
		}
	}
	
	if(!isAnimating)
	{
		this.stopAnimation();
	}
};

TSWAccordion.prototype.stopAnimation = function()
{
	if(this.animationIntervalId != null)
	{
		clearInterval(this.animationIntervalId);
		this.animationIntervalId = null;
	}
};

//A JavaScript object representing a section in the accordion.
function TSWAccordionSection(accordion, element, index)
{
	this.accordion = accordion;
	this.element = element;
	this.index = index;
	
	this.animationStartPos = null; //start position
	this.animationDestPos = null; //destination position
	this.animationStartDate = null; //date when the animation began
	this.isAnimating = false; //Is this section currently animating
};

TSWAccordionSection.prototype.getTitleElement = function()
{
	return TSWDomUtils.getChildrenWithTagName(this.element, 'div')[0];
};

TSWAccordionSection.prototype.getTitleBottomBorderWidth = function()
{
	var width = null;
	try{
		width = getComputedStyle(this.getTitleElement(), '').getPropertyValue('border-bottom-width');
	} 
	catch(e)
	{
		width = this.getTitleElement().currentStyle.borderBottomWidth;
	}
	return parseInt(width);
};

TSWAccordionSection.prototype.moveToPosition = function(pos)
{
	this.stopAnimation();
	this.element.style.top = pos.toString() + 'px';
};

TSWAccordionSection.prototype.animateToPosition = function(pos, startDate)
{
	if(this.isAnimating)
	{
		if(pos == this.animationDestPos)
		{
			//already animating this
			return;
		}
		else
		{
			this.stopAnimation();
		}
	}
	
	this.animationStartPos = this.element.offsetTop;
	this.animationDestPos = pos;
	this.animationStartDate = startDate;
	this.isAnimating = true;
};

TSWAccordionSection.prototype.continueAnimation = function(animationDate)
{
	if(!this.isAnimating)
		return;
	var delta = (animationDate.getTime() - this.animationStartDate.getTime()) / this.accordion.animationDuration;
	
	if(delta >= 1.0)
	{
		//complete the animation
		this.stopAnimation();
		delta = 1.0
	}
	
	var movementProgress = Math.sin(delta*Math.PI/2.0);
	var pos = movementProgress * this.animationDestPos + (1 - movementProgress) * this.animationStartPos;
	this.element.style.top = pos.toString() + 'px';
};

TSWAccordionSection.prototype.stopAnimation = function()
{
	this.isAnimating = false;
};

/* The checksum below is for internal use by Taco HTML Edit, 
   to detect if a component file has been modified.
   TacoHTMLEditChecksum: 8D64E031 */