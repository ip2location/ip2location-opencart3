(function() {
	jQuery(function($) {
		$('#add-rule-code').on('change', function() {
			($(this).val() == 404) ? $('#add-rule-to').prop('disabled', true).val('') : $('#add-rule-to').prop('disabled', false);
		});

		$('#add-rule-modal').on('show.bs.modal', function(){
			$('#add-rule-from').on('input', function(e){
				$(this).val($(this).val().replace(/^\//g, ''));
			});

			$('#add-origin').chosen({ width: '100%' }).on('change', function(){
				if (Array.isArray($(this).val())) {
					var origins = $(this).val();
					var countries = [];

					for (var origin in origins) {
						if (origins[origin] == '*-*') {
							$(this).val(['*-*']);
							$(this).trigger('chosen:updated');
							break;
						}

						if (origins[origin].substr(-1) == '*') {
							countries.push(origins[origin].substr(0, 2));
						}
					}

					if (countries.length > 0) {
						for (var country in countries) {
							for (var origin in origins) {
								if (origins[origin].substr(0, 2) == countries[country] && origins[origin].substr(-1) != '*') {
									delete origins[origin];
								}
							}
						}

						$(this).val(origins);
						$(this).trigger('chosen:updated');
					}
				}
			});
		}).on('hidden.bs.modal', function(){
			$('#add-rule-from').off('input');
			$('#add-origin').chosen('destroy');
		});

		$('#lookup-method').on('change', function(){
			if($(this).val() == 0){
				$('#local-database').removeClass('hidden');
				$('#remote-api').addClass('hidden');
			}
			else{
				$('#local-database').addClass('hidden');
				$('#remote-api').removeClass('hidden');
			}
		});

		$('a[data-button="edit"]').on('click', function(e) {
			e.preventDefault();

			var $row = $(this).parent().parent();

			$row.find('span[data-preview="origins"], span[data-preview="from"], span[data-preview="to"], span[data-preview="code"], span[data-preview="status"], a[data-button="edit"], a[data-button="delete"]').addClass('hidden');
			$row.find('select[data-input="origins"], select[data-input="condition"], select[data-input="code"], select[data-input="status"], a[data-button="save"], a[data-button="cancel"]').removeClass('hidden');
			$row.find('input[type="hidden"]').attr('type', 'text');
			$row.find('select[data-input="origins"]').chosen({ width: '100%' }).on('change', function(){
				if (Array.isArray($(this).val())) {
					var origins = $(this).val();
					var countries = [];

					for (var origin in origins) {
						if (origins[origin] == '*-*') {
							$(this).val(['*-*']);
							$(this).trigger('chosen:updated');
							break;
						}

						if (origins[origin].substr(-1) == '*') {
							countries.push(origins[origin].substr(0, 2));
						}
					}

					if (countries.length > 0) {
						for (var country in countries) {
							for (var origin in origins) {
								if (origins[origin].substr(0, 2) == countries[country] && origins[origin].substr(-1) != '*') {
									delete origins[origin];
								}
							}
						}

						$(this).val(origins);
						$(this).trigger('chosen:updated');
					}
				}
			});

			$row.find('select[data-input="code"]').off('change').on('change', function() {
				if ($(this).val() == '404') {
					$row.find('input[data-input="to"]').val('').prop('disabled', true);
				} else {
					$row.find('input[data-input="to"]').prop('disabled', false);
				}
			});

			$row.find('select, input').each(function(i, obj) {
				$(obj).attr('data-initial-values', $(obj).val());
			});

			$row.find('a[data-button="save"]').off('click').on('click', function(e) {
				var $form = $('<form method="post" />').html('' + 
				'<input type="hidden" name="ruleId" value="' + $(this).attr('data-rule-id') + '">' +
				'<input type="hidden" name="origins" value="' + $row.find('select[data-input="origins"]').val() + '">' + 
				'<input type="hidden" name="mode" value="' + $row.find('select[data-input="condition"]').val() + '">' + 
				'<input type="hidden" name="from" value="' + $row.find('input[data-input="from"]').val() + '">' + 
				'<input type="hidden" name="to" value="' + $row.find('input[data-input="to"]').val() + '">' + 
				'<input type="hidden" name="code" value="' + $row.find('select[data-input="code"]').val() + '">' + 
				'<input type="hidden" name="status" value="' + $row.find('select[data-input="status"]').val() + '">' + 
				'');

				$('body').append($form);
				$form.submit();
			});

			$row.find('a[data-button="cancel"]').off('click').on('click', function(e) {
				e.preventDefault();

				$row.find('select, input').each(function(i, obj) {
					$(obj).val($(obj).attr('data-initial-values'));
					$(obj).attr('data-initial-values', '');
				});

				$row.find('span[data-preview="origins"], span[data-preview="from"], span[data-preview="to"], span[data-preview="code"], span[data-preview="status"], a[data-button="edit"], a[data-button="delete"]').removeClass('hidden');
				$row.find('select[data-input="origins"], select[data-input="condition"], select[data-input="code"], select[data-input="status"], a[data-button="save"], a[data-button="cancel"]').addClass('hidden');
				$row.find('input[type="text"]').attr('type', 'hidden');
				$row.find('select[data-input="origins"]').chosen('destroy');
			});
		});

		$('a[data-button="delete"]').on('click', function(e) {
			e.preventDefault();

			if (confirm('Confirm to delete this rule?')) {
				var $form = $('<form method="post" />').html('<input type="hidden" name="deleteId" value="' + $(this).attr('data-rule-id') + '">');
				$('body').append($form);
				$form.submit();
			}
		});
	});
}).call(this);
