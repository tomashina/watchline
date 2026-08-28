(function(window, document, $) {
	'use strict';

	var BoxNowCheckout = window.WatchlineBoxNow || {};
	BoxNowCheckout.pendingOpen = false;

	BoxNowCheckout.init = function(options) {
		var root = options.root;
		var hostId = 'watchline-boxnow-map-host';
		var launcherId = 'watchline-boxnow-map-launcher';

		if (!$(root).length) {
			return;
		}

		function currentRoot() {
			return $(root).first();
		}

		if (!document.getElementById(hostId)) {
			$('<div>', { id: hostId }).appendTo('body');
		}

		if (!document.getElementById(launcherId)) {
			$('<button>', {
				id: launcherId,
				type: 'button',
				style: 'display:none'
			}).appendTo('body');
		}

		function selectedMethod() {
			var selected = $('#shipping-method input[name="shipping_method"]:checked, #collapse-shipping-method input[name="shipping_method"]:checked').first().val();

			if (!selected) {
				selected = $('#shipping-method select[name="shipping_method"], #collapse-shipping-method select[name="shipping_method"]').first().val();
			}

			return selected;
		}

		function toggleFields() {
			var active = selectedMethod() === 'boxnow.boxnow';
			var $currentRoot = currentRoot();

			$currentRoot.toggle(active);
			$currentRoot.find('.boxnow-locker-error').toggle(active && !$currentRoot.find('input[name="boxnow_locker_id"]').val());
		}

		function saveLocker(locker) {
			$.ajax({
				url: options.saveUrl,
				type: 'post',
				dataType: 'json',
				global: false,
				data: {
					boxnow_locker_id: locker.id,
					boxnow_locker_label: locker.label,
					boxnow_locker_postcode: locker.postcode
				},
				success: function(json) {
					if (json && json.error) {
						currentRoot().find('.boxnow-locker-error').text(json.error).show();
						return;
					}

					if (json && json.locker) {
						var $currentRoot = currentRoot();

						$currentRoot.find('input[name="boxnow_locker_id"]').val(json.locker.id || '');
						$currentRoot.find('input[name="boxnow_locker_label"]').val(json.locker.label || '');
						$currentRoot.find('input[name="boxnow_locker_postcode"]').val(json.locker.postcode || '');
						$currentRoot.find('.boxnow-locker-error').hide();
					}
				},
				error: function() {
					currentRoot().find('.boxnow-locker-error').text(options.saveError).show();
				}
			});
		}

		function loadWidget(openAfterLoad) {
			var existingScript = document.getElementById('watchline-boxnow-widget');

			if (existingScript) {
				if (openAfterLoad) {
					if (existingScript.getAttribute('data-loaded') === '1') {
						document.getElementById(launcherId).click();
					} else {
						BoxNowCheckout.pendingOpen = true;
					}
				}

				return;
			}

			BoxNowCheckout.pendingOpen = !!openAfterLoad;

			var script = document.createElement('script');

			script.id = 'watchline-boxnow-widget';
			script.src = options.widgetUrl;
			script.async = true;
			script.defer = true;
			script.onload = function() {
				script.setAttribute('data-loaded', '1');

				if (BoxNowCheckout.pendingOpen) {
					BoxNowCheckout.pendingOpen = false;
					document.getElementById(launcherId).click();
				}
			};
			script.onerror = function() {
				BoxNowCheckout.pendingOpen = false;
				currentRoot().find('.boxnow-locker-error').text(options.widgetError).show();

				if (script.parentNode) {
					script.parentNode.removeChild(script);
				}
			};

			document.getElementsByTagName('head')[0].appendChild(script);
		}

		$(document)
			.off('change.watchlineBoxNow', '#shipping-method input[name="shipping_method"], #shipping-method select[name="shipping_method"], #collapse-shipping-method input[name="shipping_method"], #collapse-shipping-method select[name="shipping_method"]')
			.on('change.watchlineBoxNow', '#shipping-method input[name="shipping_method"], #shipping-method select[name="shipping_method"], #collapse-shipping-method input[name="shipping_method"], #collapse-shipping-method select[name="shipping_method"]', toggleFields);

		$(document)
			.off('click.watchlineBoxNow', root + ' .boxnow-map-widget-button')
			.on('click.watchlineBoxNow', root + ' .boxnow-map-widget-button', function(event) {
				event.preventDefault();
				loadWidget(true);
			});

		toggleFields();

		var widgetConfig = window._bn_map_widget_config || {};

		widgetConfig.type = 'popup';
		widgetConfig.partnerId = parseInt(options.partnerId, 10);
		widgetConfig.autoselect = true;
		// Basel refreshes the checkout fragment through AJAX. Keep the widget's
		// real launcher outside that fragment so its one-time event binding stays
		// valid, while the visible button delegates to it after every refresh.
		widgetConfig.parentElement = '#' + hostId;
		widgetConfig.buttonSelector = '#' + launcherId;
		widgetConfig.afterSelect = function(selected) {
			var $currentRoot = currentRoot();
			var lockerId = selected ? (selected.boxnowLockerId || selected.boxnowLockerID || selected.lockerId || selected.id || '') : '';
			var postcode = selected ? (selected.boxnowLockerPostalCode || selected.postalCode || '') : '';
			var address = selected ? (selected.boxnowLockerAddressLine1 || selected.addressLine1 || selected.address || selected.name || '') : '';
			var label = [postcode, address].filter(function(value) { return !!value; }).join(', ');

			lockerId = String(lockerId || '').replace(/[^A-Za-z0-9_-]/g, '').substring(0, 64);
			postcode = String(postcode || '').replace(/[^0-9A-Za-z -]/g, '').substring(0, 32);
			label = String(label || lockerId).replace(/<[^>]*>/g, '').substring(0, 255);

			if (!lockerId) {
				$currentRoot.find('input[name="boxnow_locker_id"]').val('');
				$currentRoot.find('input[name="boxnow_locker_label"]').val('');
				$currentRoot.find('input[name="boxnow_locker_postcode"]').val('');
				$currentRoot.find('.boxnow-locker-error').text(options.requiredError).show();
				return;
			}

			$currentRoot.find('.boxnow-locker-error').hide();

			saveLocker({
				id: lockerId,
				label: label,
				postcode: postcode
			});
		};

		window._bn_map_widget_config = widgetConfig;

		loadWidget(false);
	};

	window.WatchlineBoxNow = BoxNowCheckout;
})(window, document, jQuery);
