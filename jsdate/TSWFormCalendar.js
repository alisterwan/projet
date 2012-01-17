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

var tswFormCalendarMap = new Object(); //maps form calendar id to TSWFormCalendar object;

//The last displayed TSWFormCalendar object. Only one can be showing at a time.
var tswFormCalendarLastDisplayed = null;

//Returns the TSWFormCalendar object for an id, creating the object
//if necessary.
function tswFormCalendarGetForId(id)
{
	var formCalendar = tswFormCalendarMap[id];
	if(formCalendar == null)
	{
		formCalendar = new TSWFormCalendar(id);
		tswFormCalendarMap[id] = formCalendar;
	}
	return formCalendar;
}

//TSWFormCalendar is a javascript object that represents
//the form calendar component in the HTML document. The
//constructor takes the id of the form text input field.
function TSWFormCalendar(id)
{
	var d = new Date;
	this.id = id;
	
	//The div element of the popup calendar
	this.popUpDiv = null;
	
	//The current year and month being displayed on the popup calendar
	this.currentYear = d.getFullYear();
	this.currentMonth = d.getMonth();
	this.dateFormat = 'MM/dd/yyyy';
	
	//Start week on Monday;
	this.startWeekOnMonday = false;
	
	//Array of names to show for days of week
	this.dayNames = null;
	
	//A Date object that represents the currently selected date in the calendar;
	//null if there is no selected date.
	this.selectedDate = null;
	
	//initialization code
	this.init(this);
};

//formCalendar is passed in for closure
TSWFormCalendar.prototype.init = function(formCalendar)
{
	tswUtilsAddEventHandler(document, 'click', 
		function(e) {
			var evt = tswUtilsGetInitializedEvent(e);
			if(evt != null && 
				evt.target != formCalendar.getTextInputElement() &&
				evt.target != formCalendar.getImageButtonElement())
			{
				formCalendar.hide();
			}
		}
	);
	
	var funct = function(e) {
		var evt = tswUtilsGetInitializedEvent(e);
		if(evt.target != formCalendar.getTextInputElement()
			&& evt.target != window
			&& evt.target != document
			&& evt.target != document.body)
		{
			//In IE, don't hide if element is a descendant of popUpDiv
			var element = evt.target;
			while(element != document && element != null)
			{
				if(element == formCalendar.popUpDiv)
					return;
				element = element.parentNode;
			}
			formCalendar.hide();
		}
	};
	if(window.addEventListener)
	{
		//Capture event for standards compliant browsers
		window.addEventListener('focus', funct, true);
	}
	else
	{
		//focusin event for IE
		tswUtilsAddEventHandler(document, 'focusin', funct);
	}
	tswUtilsAddEventHandler(window, 'resize', function(){ formCalendar.updatePopUpLocation(); });
	
	//For IE6
	if(TSWBrowserDetect.browserMatches('Explorer', 6, 6))
	{
		this.getInputContainerElement().className += ' tswFormCalendarIE6';
	}
};

TSWFormCalendar.prototype.hide = function()
{
	if(this.popUpDiv != null)
	{
		document.body.removeChild(this.popUpDiv);
		this.popUpDiv = null;
	}
	
	var button = this.getImageButtonElement();
	if(button != null)
	{
		button.className = 'tswFormCalendarButton';
	}
};

//Update pop up calendar dates if the calendar is showing
TSWFormCalendar.prototype.updateDates = function()
{
	if(tswFormCalendarLastDisplayed == this && this.popUpDiv != null)
	{
		if(this.updateVisibleAndSelectedDates(false))
			this.updatePopUpDiv(this);
	}
};

TSWFormCalendar.prototype.showPopUp = function()
{
	tswUtilsCancelBubble(null);

	if(tswFormCalendarLastDisplayed != null)
	{
		if(tswFormCalendarLastDisplayed == this)
		{
			if(this.popUpDiv != null)
			{
				this.updatePopUpDiv(this);
				return;
			}
		}
		else
		{
			tswFormCalendarLastDisplayed.hide();
		}
	}
	
	this.updateVisibleAndSelectedDates(true);
	
	this.createPopUpDiv();
	document.body.appendChild(this.popUpDiv);
	tswFormCalendarLastDisplayed = this;
	
	var button = this.getImageButtonElement();
	if(button != null)
	{
		button.className = 'tswFormCalendarButtonPressed';
	}
};

TSWFormCalendar.prototype.togglePopUp = function()
{
	if(tswFormCalendarLastDisplayed == this && this.popUpDiv != null)
	{
		this.hide();
	}
	else
	{
		this.showPopUp();
	}
}

TSWFormCalendar.prototype.updateVisibleAndSelectedDates = function(allowResetToCurrentDate)
{
	var currentDay = (this.selectedDate == null ? null : this.selectedDate.getDate());
	this.selectedDate = null;
	var input = this.getTextInputElement();
	if(input != null)
	{
		this.selectedDate = tswDateParse(input.value, this.dateFormat);
	}
	if(this.selectedDate != null)
	{
		if(this.currentYear != this.selectedDate.getFullYear() || this.currentMonth != this.selectedDate.getMonth() || currentDay != this.selectedDate.getDate())
		{
			this.currentYear = this.selectedDate.getFullYear();
			this.currentMonth = this.selectedDate.getMonth();
			return true;
		}
		return false;	
	}
	else if(allowResetToCurrentDate || this.currentYear == null || this.currentMonth == null)
	{
		var d = new Date();
		if(this.currentYear != d.getFullYear() || this.currentMonth != d.getMonth() || currentDay != null)
		{
			this.currentYear = d.getFullYear();
			this.currentMonth = d.getMonth();
			return true;
		}
		return false;
	}
	return (currentDay != null);
};

TSWFormCalendar.prototype.getPopUpDivId = function()
{
	return this.id+'_tswCalendarPopUp';
};

TSWFormCalendar.prototype.createPopUpDiv = function()
{
	this.popUpDiv = document.createElement('div');
	this.popUpDiv.id = this.getPopUpDivId();
	this.popUpDiv.className = 'tswFormCalendarVisual';
	this.popUpDiv.onclick = function(e){tswUtilsCancelBubble(e);};
	
	//For IE6
	if(TSWBrowserDetect.browserMatches('Explorer', 6, 6))
	{
		this.popUpDiv.className += ' tswFormCalendarVisualIE6';
	}
	
	this.updatePopUpLocation();
	this.updatePopUpDiv(this);
};

TSWFormCalendar.prototype.updatePopUpLocation = function()
{
	var imageButton = this.getImageButtonElement();
	if(this.popUpDiv != null && imageButton != null)
	{
		var imageButtonPos = tswUtilsGetAbsolutePosition(imageButton);
		this.popUpDiv.style.left = (imageButtonPos[0] + 24 ) + 'px';
		
		//in safari 4, we must use the y position from the input container
		//for the popup to appear correctly
		//var imageButtonPos = tswUtilsGetAbsolutePosition(this.getInputContainerElement());
		this.popUpDiv.style.top = (imageButtonPos[1] - 18) + 'px';
	}
}

TSWFormCalendar.prototype.dayName = function(index)
{
	if(this.startWeekOnMonday)
	{
		index = (index + 1) % 7;
	}
	
	if(this.dayNames && this.dayNames.length >= 7)
	{
		return this.dayNames[index];
	}
	return tswDateFormat.nameConstants.shortDayNames[index].substr(0,1);
}

//formCalendar must be passed to have the correct score for the onclick closures.
TSWFormCalendar.prototype.updatePopUpDiv = function(formCalendar)
{
	while(this.popUpDiv.hasChildNodes())
		this.popUpDiv.removeChild(this.popUpDiv.firstChild);

	var d = new Date(this.currentYear, this.currentMonth, 1);
	
	//For IE6
	if(TSWBrowserDetect.browserMatches('Explorer', 6, 6))
	{
		var div = document.createElement('div');
		div.className = 'tswFormCalendarVisualIE6';
		div.style.top = '0px';
		div.style.left = '0px';
		this.popUpDiv.appendChild(div);
	}
	
	div = document.createElement('div');
	div.className = 'tswFormCalendarVisualPreviousYear';
	div.onclick = function(){formCalendar.previousYear();};
	this.popUpDiv.appendChild(div);
	
	div = document.createElement('div');
	div.className = 'tswFormCalendarVisualPreviousMonth';
	div.onclick = function(){formCalendar.previousMonth();};
	this.popUpDiv.appendChild(div);
	
	div = document.createElement('div');
	div.className = 'tswFormCalendarVisualNextMonth';
	div.onclick = function(){formCalendar.nextMonth();};
	this.popUpDiv.appendChild(div);
	
	div = document.createElement('div');
	div.className = 'tswFormCalendarVisualNextYear';
	div.onclick = function(){formCalendar.nextYear();};
	this.popUpDiv.appendChild(div);
	
	div = document.createElement('div');
	div.className = 'tswFormCalendarVisualMonth';
	div.innerHTML = tswDateFormat(d, 'MMMM');
	this.popUpDiv.appendChild(div);
	
	div = document.createElement('div');
	div.className = 'tswFormCalendarVisualYear';
	div.innerHTML = tswDateFormat(d, 'yyyy');
	this.popUpDiv.appendChild(div);
	
	var daysDiv = document.createElement('div');
	daysDiv.className = 'tswFormCalendarVisualDays';
	this.popUpDiv.appendChild(daysDiv);
	
	for(var i=0; i<7; i++)
	{
		div = document.createElement('div');
		div.className = 'tswFormCalendarVisualDayHeader';
		div.innerHTML = this.dayName(i); 
		daysDiv.appendChild(div);
	}
	
	var firstDayOfMonth = tswDateFirstDayOfMonth(d.getFullYear(), d.getMonth());
	if(this.startWeekOnMonday)
	{
		firstDayOfMonth = (firstDayOfMonth + 6) % 7;
	}
	var daysInMonth = tswDateDaysInMonth(d.getFullYear(), d.getMonth());
	
	for(var i=0; i<42; i++)
	{
		div = document.createElement('div');
		div.className = 'tswFormCalendarVisualDay';
		
		var day = i - firstDayOfMonth + 1;
		if(day >= 1 && day <= daysInMonth)
		{
			div.className = 'tswFormCalendarVisualDay tswFormCalendarEnabled';
			div.innerHTML = new Number(day).toString();
			this.setSelectDateOnClick(div, formCalendar, day);
		
			if(this.selectedDate != null && 
				this.currentYear == this.selectedDate.getFullYear() &&
				this.currentMonth == this.selectedDate.getMonth() &&
				day == this.selectedDate.getDate())
			{
				div.className = 'tswFormCalendarVisualDay tswFormCalendarEnabled tswFormCalendarSelected';
			}
		}
		
		daysDiv.appendChild(div);
	}
};

TSWFormCalendar.prototype.setSelectDateOnClick = function(div, formCalendar, date)
{
	div.onclick = function(){formCalendar.selectDate(date);};
}

TSWFormCalendar.prototype.previousYear = function()
{
	this.currentYear--;
	this.updatePopUpDiv(this);
};

TSWFormCalendar.prototype.nextYear = function()
{
	this.currentYear++;
	this.updatePopUpDiv(this);
};

TSWFormCalendar.prototype.previousMonth = function()
{
	this.currentMonth--;
	if(this.currentMonth < 0)
	{
		this.currentMonth = 11;
		this.currentYear--;	
	}
	this.updatePopUpDiv(this);
};

TSWFormCalendar.prototype.nextMonth = function()
{
	this.currentMonth++;
	if(this.currentMonth > 11)
	{
		this.currentMonth = 0;
		this.currentYear++;	
	}
	this.updatePopUpDiv(this);
};

TSWFormCalendar.prototype.selectDate = function(date)
{
	this.selectedDate = new Date(this.currentYear,this.currentMonth,date);
	var input = this.getTextInputElement();
	if(input != null)
	{
		input.value = tswDateFormat(this.selectedDate, this.dateFormat);
	}
	this.updatePopUpDiv(this);
};

TSWFormCalendar.prototype.getTextInputElement = function()
{
	return document.getElementById(this.id+"_tswInput");
};

TSWFormCalendar.prototype.getInputContainerElement = function()
{
	return document.getElementById(this.id);
};

TSWFormCalendar.prototype.getImageButtonElement = function()
{
	return document.getElementById(this.id+"_tswButton");
};

/* The checksum below is for internal use by Taco HTML Edit, 
   to detect if a component file has been modified.
   TacoHTMLEditChecksum: FAE80459 */