SyntaxHighlighter.brushes.Twig = function()
{
  var functions			= 'range cycle constant random attribute block parent dump';
  var tags				= 'for in endfor if endif elseif else macro endmacro import as filter endfilter set endset extends block endblock include with from use spaceless endspaceless autoescape endautoescape raw endraw';
  var filters			= 'date e format replace url_encode json_encode convert_encoding title capitalize nl2br upper lower striptags join reverse length sort default keys escape raw merge';
 
  this.regexList = [
  		{ regex: new RegExp(this.getKeywords(functions), 'gmi'),		css: 'color2' },		// functions
  		{ regex: new RegExp(this.getKeywords(tags), 'gmi'),				css: 'keyword' },		// tags
  		{ regex: new RegExp(this.getKeywords(filters), 'gmi'),			css: 'color3' },		// filters
      	{ regex: /[{}%]{2}/gm,                                          css: 'color1' },		// delimiters {{ }} or {% %}
      	{ regex: /[a-z]+\.[a-z]+/gm,                                    css: 'variable' },		// arrays
      	{ regex: SyntaxHighlighter.regexLib.doubleQuotedString,			css: 'string' },		// double quoted strings
		{ regex: SyntaxHighlighter.regexLib.singleQuotedString,			css: 'string' },		// single quoted strings
		{ regex: /{#[\s\S]*?#}/gm,										css: 'comments' }		// comments
      ];
};
SyntaxHighlighter.brushes.Twig.prototype = new SyntaxHighlighter.Highlighter();
SyntaxHighlighter.brushes.Twig.aliases  = ['twig'];