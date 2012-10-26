$(document).ready(function() {
	$('[data-addons-toggle]').each(function(i, el) {
		$(el).click(function(e) {
			e.preventDefault();

			$this = $(this);
			$($this.attr('rel')).slideToggle('default');

			hRel = $this.attr('data-addons-toggle');
			if (hRel) {
				$(hRel).slideToggle();
			}
		});
	});

	$.nette.init();

	$("#content h2[id|=toc], #content h3[id|=toc]").each(function () {
		$(this).append($('<a class="anchor">#</a>').attr("href", "#" + $(this).attr("id")));
	});

	$(".chzn-select").chosen();

	var $downloadsGraph = $('#downloads-graph');
	if ($downloadsGraph.length) {
		google.load('visualization', '1.0', { 'packages' : ['corechart'], 'callback' : function () {
			var input = [
				['Den', 'Downloads + Installs']
			];
			$.each($downloadsGraph.data('netteaddonsDownloads'), function () {
				input.push([this.date, this.count]);
			});
			var data = google.visualization.arrayToDataTable(input);

			var options = {
				height : 60,
				backgroundColor : { fill : 'transparent' },
				fontSize : 10,
				chartArea : {
					width : '100%',
					height : '80%'
				},
				hAxis : { textPosition : 'none' },
				vAxis : {
					format : '#',
					baselineColor: '#ddd',
					textPosition : 'in',
					textStyle : { fontSize: 8 },
					gridlines : {
						color : 'transparent',
						count : 2
					}
				},
				legend : { position : 'none' },
				series : { 0 : { color : '#26374e' } }
			};

			var chart = new google.visualization.LineChart($downloadsGraph.get(0));
			chart.draw(data, options);
		} });
	}

	// fullscreen textarea
	$('.fullscreen-textarea').each(function (i, el) {
		var link = $(el).find('a.open');
		link.on('click', function (e) {
			/*$.getJSON(this.href, function (payload) {
				link.parent().append(payload.tpl);
			});*/
			return false;
		});
	});

});
