if ( typeof jQuery === 'undefined' ) {
	throw new Error( 'jQuery not found.' )
}

// ColorPicker
(function ($) {
  $(function () {
    $('.my-input-class').wpColorPicker();
  });
}(jQuery));

jQuery( document ).ready(function($) {
	"use strict";
	console.log($( '#attachids' ).val());
	console.log('done');

	/*!
	 *
	 * Carousel's image uploader, preview & tabs update.
	 *
	 */
	$( '#gallery-button' ).on( 'click', function(e) {
		var mediaUploader, mlabsImagesIds = [], mlabsContent = [], attachment;

		e.preventDefault();
		if( mediaUploader ){
			mediaUploader.open();
			return;
		}

		// Create the media frame with the options we want
		mediaUploader = wp.media({
			title: 'Select your images',
			button: {
				text: 'Choose Picture'
			},
			multiple: true
		});

		//Save the images selected 
		mediaUploader.on( 'select', function(){

			console.log('mediaUploader.on function called');

			var mlabsContent = [], mlabsImagesIds = [], numItems, mlabsPreviousIds;
			var mlabsCarouselType = $( '#mlabs_carousel_preview' ).data( 'cartype' );



			if ( $.parseJSON( $( '#attachids' ).val() ) == null ) {
				numItems = 0
			} else {
				numItems = $.parseJSON( $( '#attachids' ).val() ).length;
			}
			
			attachment = mediaUploader.state().get( 'selection' );

			attachment.map(function( current ) {
				current = current.toJSON();
				console.log('ids are'+current.id);
				mlabsImagesIds.push( current.id );

				// Prepare the HTML string for the new carousel:
				var mlabsContentPlaceholder = 
				makeItemContent( mlabsCarouselType, numItems, current.url, current.width, current.height );

				mlabsContent.push( mlabsContentPlaceholder );

				makeTabContent( numItems, current.id, current.url );

				numItems++;

			});

			mlabsPreviousIds = $.parseJSON( $( '#attachids' ).val());

			console.log('previouse IDs are ' + mlabsPreviousIds);

			// If there was any image already uploaded
			if ( mlabsPreviousIds != null ) {
				// Get previous and current items count
				var mlabsImagesIds = mlabsPreviousIds.concat( mlabsImagesIds );
				var numItemsPrev = $.parseJSON( $( '#attachids' ).val() ).length;
			} else {
				numItemsPrev = 0;
			}

			$( '#attachids' ).val( JSON.stringify( mlabsImagesIds ) );
			var numItems = $.parseJSON( $( '#attachids' ).val() ).length;
			console.log('we added this amount of IDs: ' + numItems);

			// Update Carousel preview
			var car = $( "#owl_mlabs_carousel" );
			if ( car.length ) {
				// Add the new content to the carousel
				for ( var i = 0; i < mlabsContent.length; i++ ) {
					$( '.owl-carousel' ).trigger( 'add.owl.carousel', mlabsContent[ i ] );
				}
				// Refresh it
				$('.owl-carousel').trigger('refresh.owl.carousel');
				// Go to last item
				$('.owl-carousel').trigger('to.owl.carousel', numItems);

			} else {

				// Forge the content
				var i;
				for ( i = 0; i < mlabsContent.length; i++ ) {
					if ( ! mlabs_placeholder ) {
						mlabs_placeholder = mlabsContent[ i ];
					} else {
						mlabs_placeholder = mlabs_placeholder + mlabsContent[ i ];
					}
				}
				var mlabs_placeholder = '<div id="owl_mlabs_carousel" class="owl-carousel">' + 
					mlabs_placeholder + '</div>\
					<div class="mlabs-prev-btn mlabs-carousel-btn"></div>\
					<div class="mlabs-next-btn mlabs-carousel-btn"></div>';

				// Make the carousel with the new content
				$( '#mlabs_carousel_preview' ).html( mlabs_placeholder );

				// Initiate the Owl Carousel
				var owl = jQuery( '.owl-carousel' );
				owl.owlCarousel({
					margin:10,
					loop:true,
					autoWidth:false,
					items:5,
					dots:false,
				});
				jQuery( '.mlabs-next-btn' ).click(function() {
					owl.trigger( 'next.owl.carousel' );
				})
				jQuery('.mlabs-prev-btn').click(function() {
					owl.trigger( 'prev.owl.carousel' );
				})
			}
			});
			mediaUploader.open();
		});

	// Update de admin tabs for item customization
	function makeTabContent( numItemsPrev, attachid, url ) {

		var mlabsInputState;

		// Create the tab links
		$( 'ul#mlabs_ele_tabs' ).append( '<li role="presentation"><a href="#element'
			+ ( numItemsPrev + 1 ) + '" aria-controls="mlabs_ele" role="tab" data-toggle="tab">Element ' +
			( numItemsPrev + 1 ) + '</a><button class="close" type="button" data-identifier="'+( numItemsPrev + 1 ) +
			'" title="Remove this slide">x</button></li>');

		// Create the tabs content:

		if ( $( "#mlabs_carousel_type" ).val() == 4) {
			mlabsInputState = '';
		} else {
			mlabsInputState = 'class="disabled_input"';
		}

		// Populate the admin tabs
		$( '#mlabs_ele_tab_content' ).append( '<div role="tabpanel" class="tab-pane fade" id="element' +
			( numItemsPrev + 1 ) + '">\
			<table class="form-table mlabs_close_table"><tbody>\
			<tr>\
				<th scope="row">\
					<label for="mlabs_element_image' + numItemsPrev + '">Image</label>\
				</th>\
				<td>\
					<input id="mlabs_element_image' + numItemsPrev + '" type="hidden" name="attachid' +
					numItemsPrev + '" value="' + attachid + '" />\
					<img id="ele_img_' + numItemsPrev + '" src="' + url +
					'" style="height:200px; width:auto;display:block;margin-bottom:15px;">\
					<input type="button" value="Change Image" class="button ele_img_button" data-element="' +
					numItemsPrev + '">\
				</td>\
			</tr>\
			<tr>\
				<th scope="row">\
					<label for="mlabs_element_title' + numItemsPrev + '">Title text</label>\
				</th>\
				<td>\
					<input id="mlabs_element_title' + numItemsPrev + '" type="text" name="title' + numItemsPrev + '" value="" />\
				</td>\
			</tr>\
			\
			<tr>\
				<th scope="row">\
					<label for="mlabs_element_desc' + numItemsPrev + '">Description text</label>\
				</th>\
				<td>\
					<input id="mlabs_element_desc' + numItemsPrev + '" type="text" name="desc' + numItemsPrev + '" value="" />\
				</td>\
			</tr>\
			\
			<tr>\
				<th scope="row">\
					<label for="mlabs_element_linkurl' + numItemsPrev + '">Slug of post/page</label>\
				</th>\
				<td>\
					<input id="mlabs_element_linkurl' + numItemsPrev + '" type="text" name="linkurl' + numItemsPrev + '" value="" />\
				</td>\
			</tr>\
			\
			<tr>\
				<th scope="row">\
					<label for="mlabs_element_linktext' + numItemsPrev + '">Button link text</label>\
				</th>\
				<td>\
					<input id="mlabs_element_linktext' + numItemsPrev + '" type="text" name="linktext' + numItemsPrev + '" value="See more" />\
				</td>\
			</tr>\
			\
			<tr id="mlabs_social_class" ' + mlabsInputState + '>\
				<th scope="row">\
					<label for="mlabs_element_facebook' + numItemsPrev + '">Facebook link</label>\
				</th>\
				<td>\
					<input id="mlabs_element_facebook' + numItemsPrev + '" type="text" name="facebook' + numItemsPrev + '" value="" />\
				</td>\
			</tr>\
			\
			<tr id="mlabs_social_class" ' + mlabsInputState + '>\
				<th scope="row">\
					<label for="mlabs_element_twitter' + numItemsPrev + '">Twitter link</label>\
				</th>\
				<td>\
					<input id="mlabs_element_twitter' + numItemsPrev + '" type="text" name="twitter' + numItemsPrev + '" value="" />\
				</td>\
			</tr>\
			\
			<tr id="mlabs_social_class" ' + mlabsInputState + '>\
				<th scope="row">\
					<label for="mlabs_element_googleplus' + numItemsPrev + '">Google+ link</label>\
				</th>\
				<td>\
					<input id="mlabs_element_googleplus' + numItemsPrev + '" type="text" name="googleplus' + numItemsPrev + '" value="" />\
				</td>\
			</tr>\
			\
			<tr id="mlabs_social_class" ' + mlabsInputState + '>\
				<th scope="row">\
					<label for="mlabs_element_email' + numItemsPrev + '">Email link</label>\
				</th>\
				<td>\
					<input id="mlabs_element_email' + numItemsPrev + '" type="text" name="email' + numItemsPrev + '" value="" />\
				</td>\
			</tr>\
			</tbody></table>\
			</div>\
			</div>');

			numItemsPrev++;
	}

	function makeItemContent( cartype, numItems, url, width, height ) {
		switch ( cartype ) {
			case 0:
				var mlabsContent = '<div class="children-owl-item">\
					<img id="car_item_' + numItems + '" class="mlabs-zommable" style="width:100%; height: auto;" src="' + url + '">\
					<div class="mlabs_c_overlay">\
						<div class="mlabs-overlay-content">\
							<a class="mlabs-ov mlabs-ov-lightbox" data-lightbox="image-1" href="' + url + '"></a>\
						</div>\
					</div>\
				</div>';
				break;
			case 1:
				var mlabsContent = '<img id="car_item_' + numItems +
					'" class="mlabs-zommable" style="width:100%; height: auto;" src="' + url + '">';
				break;
			case 2:
				var mlabs_img_width = Math.round( 200 * width / height );
				var mlabsContent = '<div class="children-owl-item">\
				<img id="car_item_' + numItems + '" class="mlabs-zommable"\
				style="height: ' + height + 'px; width:' + mlabs_img_width + 'px;" src="' + url + '">\
					<div class="mlabs_c_overlay">\
						<div class="mlabs-overlay-content">\
							<a class="mlabs-ov mlabs-ov-lightbox" data-lightbox="image-2" href="' + url + '"></a>\
						</div>\
					</div>\
				</div>';
				break;
			case 3:
				var mlabsContent = '<div class="children-owl-item">\
				<div class="mlabs-testimonials">\
						<div id="car_item_' + numItems + '" class="mlabs-testminial-image" style="background-image:url(\'' +
						url + '\');"></div>\
						<div class="blockquote">\
							<div class="mlabs-testimonial-quote"></div>\
							<div class="mlabs-testimonial-author"></div>\
						</div>\
					</div>\
				</div>';
				break;
			case 4:
				var mlabsContent = '<div class="children-owl-item">\
				<img id="car_item_' + numItems + '" class="mlabs-zommable" src="' + url + '"></img>\
					<div class="mlabs-black-overlay">\
						<div class="mlabs-overlay-caption">\
							<div class="mlabs-caption-title"></div>\
							<div class="double-separator"></div>\
							<div class="mlabs-caption-name"></div>\
						</div>\
					</div>\
				</div>';
				break;
			case 5:
				var mlabsContent = '<div class="children-owl-item">\
				<div class="mlabs-service">\
						<span id="car_item_' + numItems + '" class="dashicons-heart mlabs-ico"></span>\
						<div class="mlabs-service-title"></div>\
						<div class="mlabs-card-text"></div>\
					</div>\
				</div>';
				break;
			case 6:
				var mlabsContent = '<div class="children-owl-item">\
				<div class="mlabs-card">\
						<img id="car_item_' + numItems + '" class="mlabs-card-img-top" src="' + url + '">\
						<div class="mlabs-card-block">\
							<p class="mlabs-card-title"></p>\
							<div class="mlabs-card-text"></div>\
							<a href="#" class="mlabs-btn-card">See more</a>\
						</div>\
					</div>\
				</div>';
				break;
		}
		return mlabsContent;
	}

	/*!
	 *
	 * Change an item's image.
	 *
	 */
	$( '.ele_img_button' ).on( 'click', function(e) {

		var eleID = this.getAttribute( "data-element" );
		var imgID = "#ele_img_" + eleID;
		var imgCarID = ".car_item_" + eleID;
		var inputID = "#mlabs_element_image" + eleID;
		var carType = $( '#mlabs_carousel_preview' ).data( 'cartype' );

		e.preventDefault();
		if ( mediaUploader ) {
			mediaUploader.open();
			return;
		}

		// Create the media frame with the options we want
		var mediaUploader = wp.media.frames.file_frame = wp.media( {
			title: 'Choose your image',
			button: {
				text: 'Change image'
			},
			multiple: false
		});

		// Save the image selected & refresh preview
		mediaUploader.on( 'select', function(){
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			
			$( imgID ).attr( 'src', attachment.url );
			$( inputID ).val( attachment.id );
			if ( carType == 3 ) {
				$( imgCarID ).css( 'background-image', 'url(\'' + attachment.url + '\')' );
			} else {
				$( imgCarID ).attr( 'src', attachment.url );
			}

			var attachmentsArray = jQuery( '#attachids' ).val();
			attachmentsArray = JSON.parse( attachmentsArray );
			attachmentsArray.splice( eleID, 1, attachment.id );
			attachmentsArray = JSON.stringify( attachmentsArray );
			$( '#attachids' ).val( attachmentsArray );

			/* Prepare the HTML string for the new carousel item:
			mlabs_img_width = Math.round((200 * attachment.width) / attachment.height);
			mlabsContent.push('<div class="children-owl-item">\
				<img style="height: 200px; width: '+mlabs_img_width+'px;" src="'
				+current.url+'" alt="Owl Image">\
				</div>');

			$('.owl-carousel').trigger('remove.owl.carousel', (tabId-1));
			$('.owl-carousel').trigger('add.owl.carousel', mlabsContent[i]);
			$('.owl-carousel').trigger('refresh.owl.carousel');*/

		});

		mediaUploader.open();

	});

	/*!
	 *
	 * Delete all items button.
	 *
	 */
	$('#delete-button').on( 'click', function(e) {
		e.preventDefault();
		var answer = confirm( 'Are you sure you want to delete ALL items in this carousel?' );
		if ( answer == true ) {
			$( '#attachids' ).val( '[]' );
			$( '#mlabs_carousel_form' ).submit();
		}
		return;
	});


	$( '#btnsubmit' ).on( 'click', function(e) {
		e.preventDefault();
		//TODO: Pertinent checks
		$( '#mlabs_carousel_form' ).submit();
	});

	/*!
	 *
	 * Remove a tab button.
	 *
	 */
	$( '#mlabs_ele_tabs' ).on( 'click', ' li .close', function() {
		if ( confirm( 'Are you sure you want to permanently remove this item?' ) ) {
			
			var tabId = $( this ).data( 'identifier' );
			var totalElements = $( '#mlabs_ele_tabs li' ).size() - 1;
			var attachmentsArray = $( '#attachids' ).val();

			// Remove the tab and it's content
			$( this ).parents( 'li' ).remove( 'li' );
			$( '#element' + tabId ).remove();

			// Delete the removed element from the attachments array
			attachmentsArray = JSON.parse( attachmentsArray );
			attachmentsArray.splice( ( tabId-1), 1 );
			attachmentsArray = JSON.stringify( attachmentsArray );
			$( '#attachids' ).val( attachmentsArray );

			// Re-Number the other tabs accordingly
			reNumberElements( tabId, totalElements );

			// Remove the image from the carousel
			$( '.owl-carousel' ).trigger( 'remove.owl.carousel', ( tabId - 1 ) );
			$( '.owl-carousel' ).trigger( 'refresh.owl.carousel' );
		}
	});


	// Used after removing a tab to Re-Number the other tabs accordingly
	function reNumberElements( removedId, totalElements ) {
		for ( i = removedId; i < totalElements; i++ ) {

			var elementSelector = 'a[href="#element' + ( i + 1 ) + '"]';
			var closeButton = jQuery('button[data-identifier="' + ( i + 1 ) + '"]');

			// Re-number the tabs links and content divs
			jQuery( elementSelector ).text( 'Element ' + i );
			jQuery( elementSelector ).attr( "href", "#element" + i );
			jQuery( '#element' + ( i + 1 ) ).attr( "id", "element" + i );

			// Re-number the close buttons data
			closeButton.data( 'identifier', i );
			closeButton.attr( 'data-identifier', i );

			// Re-number the inputs and labels
			jQuery( "input[id$=" + i + "] " ).each(function() {
				var newid = this.id.substr( 0, this.id.length - 1 );
				newid = newid + ( i - 1 );
				this.id = newid;
			});

			// Re-number the inputs and labels
			jQuery( "input[name$=" + i + "]" ).each(function() {
				var newid = this.name.substr( 0, this.name.length - 1);
				newid = newid + ( i - 1 );
				this.name = newid;
			});

			jQuery( "label[for$=" + i + "]" ).each(function(){
				var newid = jQuery( this ).attr( 'for' ).substr( 0, jQuery( this ).attr( 'for' ).length - 1 );
				newid = newid + (i - 1 );
				jQuery(this).attr( 'for', newid );
			});

		}

	}

	/*!
	 *
	 * Put the correct inputs when changing the carousel type.
	 *
	 */
	$( "#mlabs_carousel_type" ).change(function() {

		// Check input( $( this ).val() ) for validity here
		// TODO

		// Toggle on the social media properties if the carousel is of Meet your Team type
		if ( $( this ).val() != 4 ) {
			$( 'tr.mlabs_social_class' ).addClass( 'disabled_input' );
		} else {
			$( 'tr.mlabs_social_class' ).removeClass( 'disabled_input' );
		}

		// Toggle off the 'amounts of items to show' properties if the carousel is a Slider or Flexible Width
		if ( $( this ).val() == 1 || $( this ).val() == 2 ) {
			$( 'tr.mlabs_items_class' ).addClass( 'disabled_input' );
		} else {
			$( 'tr.mlabs_items_class' ).removeClass( 'disabled_input' );
		}

		// Toggle on the 'fixed height' property if the carousel is a flexible width
		if ( $( this ).val() != 2 ) {
			$( 'tr.mlabs_height_class' ).addClass( 'disabled_input' );
		} else {
			$( 'tr.mlabs_height_class' ).removeClass( 'disabled_input' );
		}

		// Toggle on the 'link url' property if the carousel is images or content
		if ( $( this ).val() != 0 && $( this ).val() != 2 && $( this ).val() != 6 ) {
			$( 'tr.mlabs_linkurl' ).addClass( 'disabled_input' );
		} else {
			$( 'tr.mlabs_linkurl' ).removeClass( 'disabled_input' );
		}

		// Toggle on the 'button's text' property if the carousel is content
		if ( $( this ).val() != 6 ) {
			$( 'tr.mlabs_buttontext' ).addClass( 'disabled_input' );
		} else {
			$( 'tr.mlabs_buttontext' ).removeClass( 'disabled_input' );
		}

		// Toggle off the 'pagination' property if the carousel is flexible width
		if ( $( this ).val() == 2 ) {
			$( 'tr#mlabs_dots_pagination' ).addClass( 'disabled_input' );
		} else {
			$( 'tr#mlabs_dots_pagination' ).removeClass( 'disabled_input' );
		}

		// Toggle off the 'overlay color' property if the carousel is content
		if ( $( this ).val() == 6 ) {
			$( 'tr#mlabs_colorpick' ).addClass( 'disabled_input' );
		} else {
			$( 'tr#mlabs_colorpick' ).removeClass( 'disabled_input' );
		}

	});

});

/*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */

/*!
 * Generated using the Bootstrap Customizer (http://getbootstrap.com/customize/?id=f99bada8d2bd4ed5aaed86651a81ba84)
 * Config saved to config.json and https://gist.github.com/f99bada8d2bd4ed5aaed86651a81ba84
 */
if (typeof jQuery === 'undefined') {
	throw new Error('Bootstrap\'s JavaScript requires jQuery')
}
+function ($) {
	'use strict';
	var version = $.fn.jquery.split(' ')[0].split('.')
	if ((version[0] < 2 && version[1] < 9) || (version[0] == 1 && version[1] == 9 && version[2] < 1) || (version[0] > 3)) {
		throw new Error('Bootstrap\'s JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4')
	}
}(jQuery);

/* ========================================================================
 * Bootstrap: alert.js v3.3.7
 * http://getbootstrap.com/javascript/#alerts
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// ALERT CLASS DEFINITION
	// ======================

	var dismiss = '[data-dismiss="alert"]'
	var Alert   = function (el) {
		$(el).on('click', dismiss, this.close)
	}

	Alert.VERSION = '3.3.7'

	Alert.TRANSITION_DURATION = 150

	Alert.prototype.close = function (e) {
		var $this    = $(this)
		var selector = $this.attr('data-target')

		if (!selector) {
			selector = $this.attr('href')
			selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
		}

		var $parent = $(selector === '#' ? [] : selector)

		if (e) e.preventDefault()

		if (!$parent.length) {
			$parent = $this.closest('.alert')
		}

		$parent.trigger(e = $.Event('close.bs.alert'))

		if (e.isDefaultPrevented()) return

		$parent.removeClass('in')

		function removeElement() {
			// detach from parent, fire event then clean up data
			$parent.detach().trigger('closed.bs.alert').remove()
		}

		$.support.transition && $parent.hasClass('fade') ?
			$parent
				.one('bsTransitionEnd', removeElement)
				.emulateTransitionEnd(Alert.TRANSITION_DURATION) :
			removeElement()
	}

 
	// ALERT PLUGIN DEFINITION
	// =======================

	function Plugin(option) {
		return this.each(function () {
			var $this = $(this)
			var data  = $this.data('bs.alert')

			if (!data) $this.data('bs.alert', (data = new Alert(this)))
			if (typeof option == 'string') data[option].call($this)
		})
	}

	var old = $.fn.alert

	$.fn.alert             = Plugin
	$.fn.alert.Constructor = Alert


	// ALERT NO CONFLICT
	// =================

	$.fn.alert.noConflict = function () {
		$.fn.alert = old
		return this
	}


	// ALERT DATA-API
	// ==============

	$(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)

}(jQuery);

/* ========================================================================
 * Bootstrap: tooltip.js v3.3.7
 * http://getbootstrap.com/javascript/#tooltip
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// TOOLTIP PUBLIC CLASS DEFINITION
	// ===============================

	var Tooltip = function (element, options) {
		this.type       = null
		this.options    = null
		this.enabled    = null
		this.timeout    = null
		this.hoverState = null
		this.$element   = null
		this.inState    = null

		this.init('tooltip', element, options)
	}

	Tooltip.VERSION  = '3.3.7'

	Tooltip.TRANSITION_DURATION = 150

	Tooltip.DEFAULTS = {
		animation: true,
		placement: 'top',
		selector: false,
		template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
		trigger: 'hover focus',
		title: '',
		delay: 0,
		html: false,
		container: false,
		viewport: {
			selector: 'body',
			padding: 0
		}
	}

	Tooltip.prototype.init = function (type, element, options) {
		this.enabled   = true
		this.type      = type
		this.$element  = $(element)
		this.options   = this.getOptions(options)
		this.$viewport = this.options.viewport && $($.isFunction(this.options.viewport) ? this.options.viewport.call(this, this.$element) : (this.options.viewport.selector || this.options.viewport))
		this.inState   = { click: false, hover: false, focus: false }

		if (this.$element[0] instanceof document.constructor && !this.options.selector) {
			throw new Error('`selector` option must be specified when initializing ' + this.type + ' on the window.document object!')
		}

		var triggers = this.options.trigger.split(' ')

		for (var i = triggers.length; i--;) {
			var trigger = triggers[i]

			if (trigger == 'click') {
				this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
			} else if (trigger != 'manual') {
				var eventIn  = trigger == 'hover' ? 'mouseenter' : 'focusin'
				var eventOut = trigger == 'hover' ? 'mouseleave' : 'focusout'

				this.$element.on(eventIn  + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
				this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
			}
		}

		this.options.selector ?
			(this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
			this.fixTitle()
	}

	Tooltip.prototype.getDefaults = function () {
		return Tooltip.DEFAULTS
	}

	Tooltip.prototype.getOptions = function (options) {
		options = $.extend({}, this.getDefaults(), this.$element.data(), options)

		if (options.delay && typeof options.delay == 'number') {
			options.delay = {
				show: options.delay,
				hide: options.delay
			}
		}

		return options
	}

	Tooltip.prototype.getDelegateOptions = function () {
		var options  = {}
		var defaults = this.getDefaults()

		this._options && $.each(this._options, function (key, value) {
			if (defaults[key] != value) options[key] = value
		})

		return options
	}

	Tooltip.prototype.enter = function (obj) {
		var self = obj instanceof this.constructor ?
			obj : $(obj.currentTarget).data('bs.' + this.type)

		if (!self) {
			self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
			$(obj.currentTarget).data('bs.' + this.type, self)
		}

		if (obj instanceof $.Event) {
			self.inState[obj.type == 'focusin' ? 'focus' : 'hover'] = true
		}

		if (self.tip().hasClass('in') || self.hoverState == 'in') {
			self.hoverState = 'in'
			return
		}

		clearTimeout(self.timeout)

		self.hoverState = 'in'

		if (!self.options.delay || !self.options.delay.show) return self.show()

		self.timeout = setTimeout(function () {
			if (self.hoverState == 'in') self.show()
		}, self.options.delay.show)
	}

	Tooltip.prototype.isInStateTrue = function () {
		for (var key in this.inState) {
			if (this.inState[key]) return true
		}

		return false
	}

	Tooltip.prototype.leave = function (obj) {
		var self = obj instanceof this.constructor ?
			obj : $(obj.currentTarget).data('bs.' + this.type)

		if (!self) {
			self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
			$(obj.currentTarget).data('bs.' + this.type, self)
		}

		if (obj instanceof $.Event) {
			self.inState[obj.type == 'focusout' ? 'focus' : 'hover'] = false
		}

		if (self.isInStateTrue()) return

		clearTimeout(self.timeout)

		self.hoverState = 'out'

		if (!self.options.delay || !self.options.delay.hide) return self.hide()

		self.timeout = setTimeout(function () {
			if (self.hoverState == 'out') self.hide()
		}, self.options.delay.hide)
	}

	Tooltip.prototype.show = function () {
		var e = $.Event('show.bs.' + this.type)

		if (this.hasContent() && this.enabled) {
			this.$element.trigger(e)

			var inDom = $.contains(this.$element[0].ownerDocument.documentElement, this.$element[0])
			if (e.isDefaultPrevented() || !inDom) return
			var that = this

			var $tip = this.tip()

			var tipId = this.getUID(this.type)

			this.setContent()
			$tip.attr('id', tipId)
			this.$element.attr('aria-describedby', tipId)

			if (this.options.animation) $tip.addClass('fade')

			var placement = typeof this.options.placement == 'function' ?
				this.options.placement.call(this, $tip[0], this.$element[0]) :
				this.options.placement

			var autoToken = /\s?auto?\s?/i
			var autoPlace = autoToken.test(placement)
			if (autoPlace) placement = placement.replace(autoToken, '') || 'top'

			$tip
				.detach()
				.css({ top: 0, left: 0, display: 'block' })
				.addClass(placement)
				.data('bs.' + this.type, this)

			this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)
			this.$element.trigger('inserted.bs.' + this.type)

			var pos          = this.getPosition()
			var actualWidth  = $tip[0].offsetWidth
			var actualHeight = $tip[0].offsetHeight

			if (autoPlace) {
				var orgPlacement = placement
				var viewportDim = this.getPosition(this.$viewport)

				placement = placement == 'bottom' && pos.bottom + actualHeight > viewportDim.bottom ? 'top'    :
										placement == 'top'    && pos.top    - actualHeight < viewportDim.top    ? 'bottom' :
										placement == 'right'  && pos.right  + actualWidth  > viewportDim.width  ? 'left'   :
										placement == 'left'   && pos.left   - actualWidth  < viewportDim.left   ? 'right'  :
										placement

				$tip
					.removeClass(orgPlacement)
					.addClass(placement)
			}

			var calculatedOffset = this.getCalculatedOffset(placement, pos, actualWidth, actualHeight)

			this.applyPlacement(calculatedOffset, placement)

			var complete = function () {
				var prevHoverState = that.hoverState
				that.$element.trigger('shown.bs.' + that.type)
				that.hoverState = null

				if (prevHoverState == 'out') that.leave(that)
			}

			$.support.transition && this.$tip.hasClass('fade') ?
				$tip
					.one('bsTransitionEnd', complete)
					.emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
				complete()
		}
	}

	Tooltip.prototype.applyPlacement = function (offset, placement) {
		var $tip   = this.tip()
		var width  = $tip[0].offsetWidth
		var height = $tip[0].offsetHeight

		// manually read margins because getBoundingClientRect includes difference
		var marginTop = parseInt($tip.css('margin-top'), 10)
		var marginLeft = parseInt($tip.css('margin-left'), 10)

		// we must check for NaN for ie 8/9
		if (isNaN(marginTop))  marginTop  = 0
		if (isNaN(marginLeft)) marginLeft = 0

		offset.top  += marginTop
		offset.left += marginLeft

		// $.fn.offset doesn't round pixel values
		// so we use setOffset directly with our own function B-0
		$.offset.setOffset($tip[0], $.extend({
			using: function (props) {
				$tip.css({
					top: Math.round(props.top),
					left: Math.round(props.left)
				})
			}
		}, offset), 0)

		$tip.addClass('in')

		// check to see if placing tip in new offset caused the tip to resize itself
		var actualWidth  = $tip[0].offsetWidth
		var actualHeight = $tip[0].offsetHeight

		if (placement == 'top' && actualHeight != height) {
			offset.top = offset.top + height - actualHeight
		}

		var delta = this.getViewportAdjustedDelta(placement, offset, actualWidth, actualHeight)

		if (delta.left) offset.left += delta.left
		else offset.top += delta.top

		var isVertical          = /top|bottom/.test(placement)
		var arrowDelta          = isVertical ? delta.left * 2 - width + actualWidth : delta.top * 2 - height + actualHeight
		var arrowOffsetPosition = isVertical ? 'offsetWidth' : 'offsetHeight'

		$tip.offset(offset)
		this.replaceArrow(arrowDelta, $tip[0][arrowOffsetPosition], isVertical)
	}

	Tooltip.prototype.replaceArrow = function (delta, dimension, isVertical) {
		this.arrow()
			.css(isVertical ? 'left' : 'top', 50 * (1 - delta / dimension) + '%')
			.css(isVertical ? 'top' : 'left', '')
	}

	Tooltip.prototype.setContent = function () {
		var $tip  = this.tip()
		var title = this.getTitle()

		$tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
		$tip.removeClass('fade in top bottom left right')
	}

	Tooltip.prototype.hide = function (callback) {
		var that = this
		var $tip = $(this.$tip)
		var e    = $.Event('hide.bs.' + this.type)

		function complete() {
			if (that.hoverState != 'in') $tip.detach()
			if (that.$element) { // TODO: Check whether guarding this code with this `if` is really necessary.
				that.$element
					.removeAttr('aria-describedby')
					.trigger('hidden.bs.' + that.type)
			}
			callback && callback()
		}

		this.$element.trigger(e)

		if (e.isDefaultPrevented()) return

		$tip.removeClass('in')

		$.support.transition && $tip.hasClass('fade') ?
			$tip
				.one('bsTransitionEnd', complete)
				.emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
			complete()

		this.hoverState = null

		return this
	}

	Tooltip.prototype.fixTitle = function () {
		var $e = this.$element
		if ($e.attr('title') || typeof $e.attr('data-original-title') != 'string') {
			$e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
		}
	}

	Tooltip.prototype.hasContent = function () {
		return this.getTitle()
	}

	Tooltip.prototype.getPosition = function ($element) {
		$element   = $element || this.$element

		var el     = $element[0]
		var isBody = el.tagName == 'BODY'

		var elRect    = el.getBoundingClientRect()
		if (elRect.width == null) {
			// width and height are missing in IE8, so compute them manually; see https://github.com/twbs/bootstrap/issues/14093
			elRect = $.extend({}, elRect, { width: elRect.right - elRect.left, height: elRect.bottom - elRect.top })
		}
		var isSvg = window.SVGElement && el instanceof window.SVGElement
		// Avoid using $.offset() on SVGs since it gives incorrect results in jQuery 3.
		// See https://github.com/twbs/bootstrap/issues/20280
		var elOffset  = isBody ? { top: 0, left: 0 } : (isSvg ? null : $element.offset())
		var scroll    = { scroll: isBody ? document.documentElement.scrollTop || document.body.scrollTop : $element.scrollTop() }
		var outerDims = isBody ? { width: $(window).width(), height: $(window).height() } : null

		return $.extend({}, elRect, scroll, outerDims, elOffset)
	}

	Tooltip.prototype.getCalculatedOffset = function (placement, pos, actualWidth, actualHeight) {
		return placement == 'bottom' ? { top: pos.top + pos.height,   left: pos.left + pos.width / 2 - actualWidth / 2 } :
					 placement == 'top'    ? { top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2 } :
					 placement == 'left'   ? { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth } :
				/* placement == 'right' */ { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width }

	}

	Tooltip.prototype.getViewportAdjustedDelta = function (placement, pos, actualWidth, actualHeight) {
		var delta = { top: 0, left: 0 }
		if (!this.$viewport) return delta

		var viewportPadding = this.options.viewport && this.options.viewport.padding || 0
		var viewportDimensions = this.getPosition(this.$viewport)

		if (/right|left/.test(placement)) {
			var topEdgeOffset    = pos.top - viewportPadding - viewportDimensions.scroll
			var bottomEdgeOffset = pos.top + viewportPadding - viewportDimensions.scroll + actualHeight
			if (topEdgeOffset < viewportDimensions.top) { // top overflow
				delta.top = viewportDimensions.top - topEdgeOffset
			} else if (bottomEdgeOffset > viewportDimensions.top + viewportDimensions.height) { // bottom overflow
				delta.top = viewportDimensions.top + viewportDimensions.height - bottomEdgeOffset
			}
		} else {
			var leftEdgeOffset  = pos.left - viewportPadding
			var rightEdgeOffset = pos.left + viewportPadding + actualWidth
			if (leftEdgeOffset < viewportDimensions.left) { // left overflow
				delta.left = viewportDimensions.left - leftEdgeOffset
			} else if (rightEdgeOffset > viewportDimensions.right) { // right overflow
				delta.left = viewportDimensions.left + viewportDimensions.width - rightEdgeOffset
			}
		}

		return delta
	}

	Tooltip.prototype.getTitle = function () {
		var title
		var $e = this.$element
		var o  = this.options

		title = $e.attr('data-original-title')
			|| (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

		return title
	}

	Tooltip.prototype.getUID = function (prefix) {
		do prefix += ~~(Math.random() * 1000000)
		while (document.getElementById(prefix))
		return prefix
	}

	Tooltip.prototype.tip = function () {
		if (!this.$tip) {
			this.$tip = $(this.options.template)
			if (this.$tip.length != 1) {
				throw new Error(this.type + ' `template` option must consist of exactly 1 top-level element!')
			}
		}
		return this.$tip
	}

	Tooltip.prototype.arrow = function () {
		return (this.$arrow = this.$arrow || this.tip().find('.tooltip-arrow'))
	}

	Tooltip.prototype.enable = function () {
		this.enabled = true
	}

	Tooltip.prototype.disable = function () {
		this.enabled = false
	}

	Tooltip.prototype.toggleEnabled = function () {
		this.enabled = !this.enabled
	}

	Tooltip.prototype.toggle = function (e) {
		var self = this
		if (e) {
			self = $(e.currentTarget).data('bs.' + this.type)
			if (!self) {
				self = new this.constructor(e.currentTarget, this.getDelegateOptions())
				$(e.currentTarget).data('bs.' + this.type, self)
			}
		}

		if (e) {
			self.inState.click = !self.inState.click
			if (self.isInStateTrue()) self.enter(self)
			else self.leave(self)
		} else {
			self.tip().hasClass('in') ? self.leave(self) : self.enter(self)
		}
	}

	Tooltip.prototype.destroy = function () {
		var that = this
		clearTimeout(this.timeout)
		this.hide(function () {
			that.$element.off('.' + that.type).removeData('bs.' + that.type)
			if (that.$tip) {
				that.$tip.detach()
			}
			that.$tip = null
			that.$arrow = null
			that.$viewport = null
			that.$element = null
		})
	}


	// TOOLTIP PLUGIN DEFINITION
	// =========================

	function Plugin(option) {
		return this.each(function () {
			var $this   = $(this)
			var data    = $this.data('bs.tooltip')
			var options = typeof option == 'object' && option

			if (!data && /destroy|hide/.test(option)) return
			if (!data) $this.data('bs.tooltip', (data = new Tooltip(this, options)))
			if (typeof option == 'string') data[option]()
		})
	}

	var old = $.fn.tooltip

	$.fn.tooltip             = Plugin
	$.fn.tooltip.Constructor = Tooltip


	// TOOLTIP NO CONFLICT
	// ===================

	$.fn.tooltip.noConflict = function () {
		$.fn.tooltip = old
		return this
	}

}(jQuery);

/* ========================================================================
 * Bootstrap: tab.js v3.3.7
 * http://getbootstrap.com/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// TAB CLASS DEFINITION
	// ====================

	var Tab = function (element) {
		// jscs:disable requireDollarBeforejQueryAssignment
		this.element = $(element)
		// jscs:enable requireDollarBeforejQueryAssignment
	}

	Tab.VERSION = '3.3.7'

	Tab.TRANSITION_DURATION = 150

	Tab.prototype.show = function () {
		var $this    = this.element
		var $ul      = $this.closest('ul:not(.dropdown-menu)')
		var selector = $this.data('target')

		if (!selector) {
			selector = $this.attr('href')
			selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
		}

		if ($this.parent('li').hasClass('active')) return

		var $previous = $ul.find('.active:last a')
		var hideEvent = $.Event('hide.bs.tab', {
			relatedTarget: $this[0]
		})
		var showEvent = $.Event('show.bs.tab', {
			relatedTarget: $previous[0]
		})

		$previous.trigger(hideEvent)
		$this.trigger(showEvent)

		if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) return

		var $target = $(selector)

		this.activate($this.closest('li'), $ul)
		this.activate($target, $target.parent(), function () {
			$previous.trigger({
				type: 'hidden.bs.tab',
				relatedTarget: $this[0]
			})
			$this.trigger({
				type: 'shown.bs.tab',
				relatedTarget: $previous[0]
			})
		})
	}

	Tab.prototype.activate = function (element, container, callback) {
		var $active    = container.find('> .active')
		var transition = callback
			&& $.support.transition
			&& ($active.length && $active.hasClass('fade') || !!container.find('> .fade').length)

		function next() {
			$active
				.removeClass('active')
				.find('> .dropdown-menu > .active')
					.removeClass('active')
				.end()
				.find('[data-toggle="tab"]')
					.attr('aria-expanded', false)

			element
				.addClass('active')
				.find('[data-toggle="tab"]')
					.attr('aria-expanded', true)

			if (transition) {
				element[0].offsetWidth // reflow for transition
				element.addClass('in')
			} else {
				element.removeClass('fade')
			}

			if (element.parent('.dropdown-menu').length) {
				element
					.closest('li.dropdown')
						.addClass('active')
					.end()
					.find('[data-toggle="tab"]')
						.attr('aria-expanded', true)
			}

			callback && callback()
		}

		$active.length && transition ?
			$active
				.one('bsTransitionEnd', next)
				.emulateTransitionEnd(Tab.TRANSITION_DURATION) :
			next()

		$active.removeClass('in')
	}


	// TAB PLUGIN DEFINITION
	// =====================

	function Plugin(option) {
		return this.each(function () {
			var $this = $(this)
			var data  = $this.data('bs.tab')

			if (!data) $this.data('bs.tab', (data = new Tab(this)))
			if (typeof option == 'string') data[option]()
		})
	}

	var old = $.fn.tab

	$.fn.tab             = Plugin
	$.fn.tab.Constructor = Tab


	// TAB NO CONFLICT
	// ===============

	$.fn.tab.noConflict = function () {
		$.fn.tab = old
		return this
	}


	// TAB DATA-API
	// ============

	var clickHandler = function (e) {
		e.preventDefault()
		Plugin.call($(this), 'show')
	}

	$(document)
		.on('click.bs.tab.data-api', '[data-toggle="tab"]', clickHandler)
		.on('click.bs.tab.data-api', '[data-toggle="pill"]', clickHandler)

}(jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.3.7
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
	// ============================================================

	function transitionEnd() {
		var el = document.createElement('bootstrap')

		var transEndEventNames = {
			WebkitTransition : 'webkitTransitionEnd',
			MozTransition    : 'transitionend',
			OTransition      : 'oTransitionEnd otransitionend',
			transition       : 'transitionend'
		}

		for (var name in transEndEventNames) {
			if (el.style[name] !== undefined) {
				return { end: transEndEventNames[name] }
			}
		}

		return false // explicit for ie8 (  ._.)
	}

	// http://blog.alexmaccaw.com/css-transitions
	$.fn.emulateTransitionEnd = function (duration) {
		var called = false
		var $el = this
		$(this).one('bsTransitionEnd', function () { called = true })
		var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
		setTimeout(callback, duration)
		return this
	}

	$(function () {
		$.support.transition = transitionEnd()

		if (!$.support.transition) return

		$.event.special.bsTransitionEnd = {
			bindType: $.support.transition.end,
			delegateType: $.support.transition.end,
			handle: function (e) {
				if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
			}
		}
	})

}(jQuery);



