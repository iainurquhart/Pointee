# Pointee Gieldtype for ExpressionEngine 2.0

### Overview

The Pointee fieldtype logs the X & Y coordinates of a click on an image.

The image can be predefined per field, or set per entry. 

### Documentation

If your pointee field has the shortname of *my_field*, you can access the various attributes using the following tags/methods:

	{my_field:img} 	/ returns the image url
	{my_field:x} 	/ returns the x coordinate
	{my_field:y} 	/ returns the y coordinate

You can offset the x/y coordinates using an offset parameter, for example:

	{my_field:x offset="+8"}
	{my_field:y offset="-22"}

### Support and Feature Requests
Please post on the @devot_ee forums:
http://devot-ee.com/add-ons/pointee/

Copyright (c) 2011 Iain Urquhart
http://iain.co.nz