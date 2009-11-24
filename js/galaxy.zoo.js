/*  Copyright 2009  SciBuff - Galaxy Zoo

    This file is part of Galaxy Zoo Wordpress Plugin.

    Alfisti Connect is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Alfisti Connect is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Galaxy Zoo Wordpress Plugin.  If not, see <http://www.gnu.org/licenses/>.

*/

( function( $ ) {

	/**
	 * Creates a namespace specified by the arguments
	 * of the function
	 */
	$.createNamespace = function() {
		var a=arguments, o=null, i, j, d;
		for (i=0; i<a.length; i=i+1) {
		    d=a[i].split(".");
		    o=window;
		    for (j=0; j<d.length; j=j+1) {
		        o[d[j]]=o[d[j]] || {};
		        o=o[d[j]];
		    }
		}
		return o;
	};
	
	GALAXY_ZOO_NAMESPACE = "com.scibuff.galaxyzoo";
	GALAXY_ZOO = jQuery.createNamespace( GALAXY_ZOO_NAMESPACE );

	GALAXY_ZOO.Widget = function(){
			
		var tmp = {};
		var pub = {};
		
		pub.load = function ( url, id, query_vars ){
			tmp.id = id;
			if ( query_vars['timestamp'] ){ query_vars['timestamp'] = new Date().valueOf(); }
			 
			$.post( url, query_vars, function( data, textStatus ){
				tmp.el = $( '#' + tmp.id );
				
				switch ( textStatus ){
					/*case "notmodified" : { 
						break; 
					}*/					
					case "success" : { 
						tmp.el.hide('fast', function (){
							tmp.el.empty().append( data ).show('fast');	
						});
						break; 
					}
					case "timeout" : { 
						//break; 
					}
					case "error" : { 
						//break; 
					}
					case "parsererror" : { 
						tmp.el.hide('fast', function (){
							var content = '<p class="error">Couldn\'t retrieve data from Galaxy Zoo.</p>';
							tmp.el.empty().append( content ).show('fast');	
						});
						break; 
					}
				}
			});
		}
		
		return pub;
	}
})( jQuery );