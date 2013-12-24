# Rainbow.linenumbers

A plugin for [Rainbow](http://rainbowco.de) to show linenumbers.<br/>
Adds line numbering to the code element by adding a span.line at each newline.

## Instructions

Just include Rainbow.linenumbers.js alongside Rainbow.js

### usage

Set the starting line number by adding ```data-line="234"``` attribute to code element.<br/>
Disable line numbering for a specific code block by setting ```data-line="-1"```

### styling
Be sure to check the example html and theme file.<br/>
Edit styles and colors by overriding the css:<br/>
```pre code.rainbow .line``` <-- the main element<br/>
```pre code.rainbow .line:before``` <-- the actual number<br/>
```pre code.rainbow .line:after``` <-- the underline

#### underlines

A pre element is inline by default so the size of the underline will be the size of that element.<br/>
If you set the display to block, an event listener will be added that resizes the underlines properly.

#### highlighting

Highlight a single line by doing this (block 2 line 6):
```css
#rb2ln6:after {
	height: 100%;
	background-color: rgba(255, 255, 0, 0.1);
}
```
The highlight will appear over the line by default. Setting the pre element's z-index to -2 an the .line:after to -1 solves this.

### hyperlinking

Each span.line has an id so you can easily jump to a specific line using and anchor href like #rb1ln30 (meaning rainbow block 1 line 30)
