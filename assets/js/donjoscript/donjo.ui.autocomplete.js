(function( $ ) {

var proto = $.ui.autocomplete.prototype,
	initSource = proto._initSource;

function filter( array, term ) {
	var matcher = new RegExp( $.ui.autocomplete.escapeRegex(term), "i" );
	return $.grep( array, function(value) {
		return matcher.test( $( "<div>" ).html( value.label || value.value || value ).text() );
	});
}

$.extend( proto, {
	_initSource: function() {
		if ( this.options.html && $.isArray(this.options.source) ) {
			this.source = function( request, response ) {
				response( filter( this.options.source, request.term ) );
			};
		} else {
			initSource.call( this );
		}
	},

	_renderItem: function( ul, item) {
    item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong class='highlight'>$1</strong>");
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( $( "<a></a>" )[ this.options.html ? "html" : "text" ]( item.label ) )
			.appendTo( ul );
	}
});

})( jQuery );

$(function() {
  $('.inputbox[rel=autocomplete]').live('keyup', function(){ 
    var location = $(this).attr('source');
    $(this).autocomplete({    
      source: function(req, response) {
       $.ajax({
          url: location,
          dataType: "json",
          success: function( data ) {
          var re = $.ui.autocomplete.escapeRegex(req.term);
              var matcher = new RegExp( re, "i" );
          response($.grep(data, function(item){return matcher.test(item.label);}) );
          }
        });
           },
      html: true,
      minLength: 4,
    }); 
  });
});
