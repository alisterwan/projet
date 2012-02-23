// JavaScript Document
			$(function() {

				$('#default').raty();

				$('#fixed').raty({
					readOnly:	true,
					start:		1
				});

				$('#start').raty({
				    start: function() {
				        return $(this).attr('data-rating');
				    }
				});

				$('#anyone').raty({
					readOnly:	true,
					noRatedMsg:	'anyone rated this product yet!'
				});

				$('#number').raty({
					scoreName:	'entity.score',
					number:		10
				});

				$('#click').raty({
					click: function(score, evt) {
						alert('ID: ' + $(this).attr('id') + '\nscore: ' + score + '\nevent: ' + evt);
					}
				});

				$('#cancel').raty({
					cancel: true
				});

				$('#cancel-custom').raty({
					cancel:			true,
					cancelHint:		'remove my rating!',
					cancelPlace:	'right',
					click: function(score, evt) {
						alert('score: ' + score);
					}
				});

				$('#half').raty({
					half: true,
					start: 3.3
				});

				$('#round').raty({
					start: 1.26,
					round: { down: .25, full: .6, up: .76 }
				});

				$('#icon').raty({
					hintList:	['a', '', null, 'd', '5'],
				   	starOn:		'medal-on.png',
				   	starOff:	'medal-off.png'
				});

				$('#range').raty({
					iconRange: [
						{ range: 2, on: 'face-a.png', off: 'face-a-off.png' },
						{ range: 3, on: 'face-b.png', off: 'face-b-off.png' },
						{ range: 4, on: 'face-c.png', off: 'face-c-off.png' },
						{ range: 5, on: 'face-d.png', off: 'face-d-off.png' }
					]
				});

				$('#big').raty({
					cancel:		true,
					cancelOff:	'cancel-off-big.png',
					cancelOn:	'cancel-on-big.png',
					half:		true,
					size:		24,
					starOff:	'star-off-big.png',
					starOn:	'star-on-big.png',
					starHalf:	'star-half-big.png'
				});

				$('.group').raty();

				$('#target').raty({
					cancel:		true,
					cancelHint:	'none',
					target:		'#hint'
				});

				$('#format').raty({
					cancel:			true,
					cancelHint:		'Sure?',
					target:			'#hint-format',
					targetFormat:	 'your score: {score}',
					targetKeep:		true,
					targetText:		'none'
				});

				$('#target-number').raty({
					cancel:		true,
					target:		'#score',
					targetKeep:	true,
					targetType:	'number'
				});

				$('#target-out').raty({
					target:		'#hint-out',
					targetText:	'--'
				});

				$('#precision').raty({
					half:		true,
					precision:	true,
					size:		24,
					starOff:	'star-off-big.png',
					starOn:		'star-on-big.png',
					starHalf:	'star-half-big.png',
					target:		'#precision-target',
					targetType:	'number'
				});

				$('#space').raty({
					space: false
				});

				$('#single').raty({
					single: true
				});

				var $result = $('#result').raty();

				$('.action').raty({
					click: function(score, evt) {
						$(this).raty('cancel');
						$result.raty('start', score);
					}
				});

				$('#function').raty({
					cancel:			true,
					cancelHint:		'Boring!',
					click:	function(score, evt) {
						$(this).fadeOut(function() { $(this).fadeIn(); });
					},
					targetKeep:	true,
					start:		2,
					target:		'#hint-function',
					targetText:	'--'
				});

				$('.start').click(function() {
					$('#function').raty('start', this.title);
				});

				$('.click').click(function() {
					$('#function').raty('click', this.title);
				});

				$('.readOnly').click(function() {
					$('#function').raty('readOnly', (this.title == 'true') ? true : false);
				});

				$('.cancel').click(function() {
					$('#function').raty('cancel', (this.title == 'true') ? true : false);
				});

			});
		