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

/*
TSWDateAndTime.js
Defines the tswDateFormat function, which can be used to format a date object as 
a user-readable date string. The pattern used for formatting the date string is 
based of the SimpleDateFormat class from the Java API:
http://java.sun.com/j2se/1.4.2/docs/api/java/text/SimpleDateFormat.html

*/

function tswDateFormat(date, pattern)
{
	var formattedDate = "";
	
	_tswDateParsePattern(
		pattern,
		function(token){
			formattedDate += _tswDateFormatReplaceTokenValue(date, token);
		},
		function(literal){
			formattedDate += literal;
		}
	);
	
	return formattedDate;
}

//Parses the date string according to the specified pattern. Returns a Date object, or null
//if dateStr cannot be parsed.
//Currently, parsing supports only numeric year, month, and date.
function tswDateParse(dateStr, pattern)
{
	//Holds the year/month/day of date
	var result = new Object();
	result.fullYear = 1;
	result.month = 1;
	result.date = 1;
	
	//The current position in dateStr
	var parseIndex = 0;
	
	_tswDateParsePattern(
		pattern,
		function(token){
			if(result != null)
			{
				var parsedLength = _tswDateParseToken(result, token, dateStr, parseIndex);
				if(parsedLength == 0)
					result = null;
				parseIndex += parsedLength;
			}
		},
		function(literal){
			if(result != null)
			{
				if(parseIndex + 1 <= dateStr.length
					&& dateStr.substr(parseIndex,1) == literal)
				{
					parseIndex += 1;
				}
				else
				{
					result = null;
				}
			}
		}
	);
	
	if(result == null || result.month < 0 || result.month > 11 || result.date < 0 || result.date > tswDateDaysInMonth(result.fullYear, result.month))
		return null;
	var d = new Date(result.fullYear, result.month, result.date);
	return d;
}

//Parses pattern; invokes tokenParsedFunct with the token pattern string as a parameter
//when a token is encountered; invokes literalParsedFunct with the literal character
//as a parameter when a literal is encountered.
function _tswDateParsePattern(pattern, tokenParsedFunct, literalParsedFunct)
{
	//Track sequences surrounded by single quotes
	var quoteOpen = false;
	var quoteOpenIndex;
	
	//Track date format tokens like "yyyy" and "dd"
	var tokenOpen = false;
	var tokenOpenIndex;
	
	for(var i=0; i<pattern.length; i++)
	{
		var ch = pattern.charAt(i);
		if(quoteOpen)
		{
			if(ch == "'")
			{
				if(quoteOpenIndex == i - 1)
				{
					//interpret as a literal single quote when pattern has 
					//two consecutive single quotes
					literalParsedFunct("'");
				}
				quoteOpen = false;
			}
			else
			{
				literalParsedFunct(ch);
			}
		}
		else
		{
			if(tokenOpen)
			{
				//if token closed then determine the correct date
				//values to append to formattedDate; otherwise,
				//do nothing
				if(pattern.charAt(tokenOpenIndex) != ch)
				{
					//token
					tokenParsedFunct(pattern.substr(tokenOpenIndex, i - tokenOpenIndex));
					tokenOpen = false;
				}
			}
			
			if(!tokenOpen)
			{
				if(ch == "'")
				{
					//begin a quoted sequence which will be copied
					//into formattedDate verbatim
					quoteOpen = true;
					quoteOpenIndex = i;
				}
				else if((ch >= 'a' && ch <= 'z') || (ch >= 'A' && ch <= 'Z'))
				{
					//Check for the start of a new token including the case
					//where one was just closed.
					tokenOpen = true;
					tokenOpenIndex = i;
				}
				else
				{
					//literal
					literalParsedFunct(ch);
				}
			}
		}
	}
	
	if(tokenOpen)
	{
		//token may be terminated by end of string
		tokenParsedFunct(pattern.substr(tokenOpenIndex, pattern.length - tokenOpenIndex));
		tokenOpen = false;
	}
}

// Internationalization strings
tswDateFormat.nameConstants = {
	shortDayNames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
	longDayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
	shortMonthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
	longMonthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
	ampm: {am: "AM", pm: "PM"}
};

function _tswDateFormatReplaceTokenValue(date, token)
{
	switch(token.charAt(0))
	{
		case 'y':
			if(token.length == 2)
			{
				var yearString = new String(date.getFullYear());
				return yearString.substr(yearString.length - 2, 2);
			}
			else
			{
				return date.getFullYear();
			}
		case 'M':
			if(token.length <= 2)
			{
				//numeric
				return token.length == 2 ? _tswDateFormatPadPrefix(date.getMonth() + 1, '0', 2) : 
					String(date.getMonth() + 1);
			}
			else
			{
				//text
				return token.length <= 3 ? tswDateFormat.nameConstants.shortMonthNames[date.getMonth()] : 
					tswDateFormat.nameConstants.longMonthNames[date.getMonth()];
			}
		case 'd':
			return _tswDateFormatPadPrefix(date.getDate(), '0', token.length);
		case 'F':
			return _tswDateFormatPadPrefix(date.getDay(), '0', token.length);
		case 'E':
			return token.length <= 3 ? tswDateFormat.nameConstants.shortDayNames[date.getDay()] : 
				tswDateFormat.nameConstants.longDayNames[date.getDay()];
		case 'a':
			return date.getHours() < 12 ? tswDateFormat.nameConstants.ampm.am : tswDateFormat.nameConstants.ampm.pm;
		case 'H': return _tswDateFormatPadPrefix(date.getHours(), '0', token.length);
		case 'k': return _tswDateFormatPadPrefix(date.getHours() == 0 ? 24 : date.getHours(), '0', token.length);
		case 'K': return _tswDateFormatPadPrefix(date.getHours() % 12, '0', token.length);
		case 'h': return _tswDateFormatPadPrefix(date.getHours() % 12 == 0 ? 12 : date.getHours() % 12, '0', token.length);
		case 'm': return _tswDateFormatPadPrefix(date.getMinutes(), '0', token.length);
		case 's': return _tswDateFormatPadPrefix(date.getSeconds(), '0', token.length);
		case 'S': return _tswDateFormatPadPrefix(date.getMilliseconds(), '0', token.length);
		case 'Z':
			var offset = -date.getTimezoneOffset();
			var rfc822TimeZone = offset < 0 ? "-" : "+";
			
			if(offset < 0) offset = -offset;
			
			rfc822TimeZone += _tswDateFormatPadPrefix(offset / 60, '0', 2);
			rfc822TimeZone += _tswDateFormatPadPrefix(offset % 60, '0', 2);
			return rfc822TimeZone;
		case 'z':
			//These regular expressions are from http://blog.stevenlevithan.com/archives/date-time-format
			//MIT license
			var	timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
				timezoneClip = /[^-+\dA-Z]/g;
			
			return (String(date).match(timezone) || [""]).pop().replace(timezoneClip, "");
		case 'G':
			return date.getFullYear() > 0 ? "AD" : "BC";
			
	}
	return "";
}

function _tswDateFormatPadPrefix(str, paddingChar, length)
{
	str = String(str);
	while(str.length < length)
	{
		str = paddingChar + str;
	}
	return str;
}

function _tswDateParseToken(dateResult, token, dateStr, parseIndex)
{
	switch(token.charAt(0))
	{
		case 'y':
			if(token.length == 2)
			{
				var result = _tswDateParseNumericToken(dateStr, parseIndex, 2, 2);
				if(result.success)
				{
					dateResult.fullYear = (2000 + result.parsedValue);
				}
				return result.parsedLength;
			}
			else
			{
				var result = _tswDateParseNumericToken(dateStr, parseIndex, 4, 4);
				if(result.success)
				{
					dateResult.fullYear = (result.parsedValue);
				}
				return result.parsedLength;
			}
		case 'M':
			if(token.length <= 2)
			{
				//numeric
				var result = _tswDateParseNumericToken(dateStr, parseIndex, token.length, 2);
				if(result.success)
				{
					dateResult.month = (result.parsedValue - 1);
				}
				return result.parsedLength;
			}
			else
			{
				//text
				return 0;
			}
		case 'd':
			var result = _tswDateParseNumericToken(dateStr, parseIndex, token.length, 2);
			if(result.success)
			{
				dateResult.date = (result.parsedValue);
			}
			return result.parsedLength;
		case 'F':
		case 'E':
		case 'a':
		case 'H': 
		case 'k': 
		case 'K': 
		case 'h': 
		case 'm': 
		case 's': 
		case 'S': 
		case 'Z':
		case 'z':
		case 'G':
			
	}
	return 0;
}

function TSWDateParseResult()
{
	this.success = false;
	this.parsedLength = 0;
	this.parsedValue = null;
}

//Returns TSWDateParseResult object
function _tswDateParseNumericToken(dateStr, parseIndex, minLength, maxLength)
{
	var result = new TSWDateParseResult();
	var length = 0;
	while(parseIndex + length < dateStr.length && length < maxLength)
	{
		var ch = dateStr.charAt(parseIndex + length);
		if(ch >= '0' && ch <= '9')
			length++;
		else
			break;
	}
	
	if(length < minLength)
		return result;
	
	result.success = true;
	result.parsedLength = length;
	result.parsedValue = parseInt(dateStr.substr(parseIndex,length), 10);
	
	return result;
}

//Returns the number of days in a month; month should be a values between 0 and 11.
function tswDateDaysInMonth(year, month) {
	var m = [31,28,31,30,31,30,31,31,30,31,30,31];
	if (month != 1) return m[month];
	if (year%4 != 0) return m[1];
	if (year%100 == 0 && year%400 != 0) return m[1];
	return m[1] + 1;
}

//Returns the day of the week for the first day of the month; Sunday = 0, Monday = 1, etc.
function tswDateFirstDayOfMonth(year, month) {
	var dd = new Date(year, month, 1);
	return dd.getDay();
}



/* The checksum below is for internal use by Taco HTML Edit, 
   to detect if a component file has been modified.
   TacoHTMLEditChecksum: FC250350 */