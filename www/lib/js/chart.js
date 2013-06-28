/*
	Draw chart using canvas
	@author Mikhail Miropolskiy <the-ms@ya.ru>
	@package Lib
	@copyright (c) 2013. Mikhail Miropolskiy. All Rights Reserved.

	<canvas width="600" height="400" id="chart"></canvas>

	<script>
		chart({
			id: 'chart',
			data: [{
				title: 'работа',
				value: '50'
			}, {
				title: 'семья',
				value: '40'
			}, {
				title: 'личное',
				value: '10'
			}]
		});
	</script>
*/
function chart (config) {
	var canvas = document.getElementById(config.id),
		context = canvas.getContext('2d'),
		width = parseInt(canvas.getAttribute('width')),
		height = parseInt(canvas.getAttribute('height')),
		center = [width / 2, height / 2],
		startAngle,
		endAngle = 0,
		colors = ['#ffb2b2', '#ffd17f', '#fffe7f', '#7fff7f', '#7fc5ff', '#7f7fff', '#c47fff', '#fff', '#ccc'],
		i, x, y, angle, item,

		data = config.data;

	for (i in data) {
		item = data[i];

		startAngle = endAngle;
		endAngle = Math.PI / 50 * item.value + startAngle;

		context.beginPath();
		context.moveTo(center[0], center[1]);
		context.strokeStyle = "#000";
		context.fillStyle = colors[i];
		context.arc(center[0], center[1], height / 3, startAngle, endAngle, false);
		context.lineTo(center[0], center[1]);
		context.fill();
		context.stroke();

		x = center[0];
		y = center[1];
		angle = (endAngle - startAngle) / 2 + startAngle;

		x = x + Math.cos(angle) * 50;
		y = y + Math.sin(angle) * 50;

		context.save();
		context.fillStyle = '#000';
		context.font = 'bold 12px sans-serif';
		context.translate(x, y);
		context.rotate(angle);
		context.translate(-x, -y);
		context.fillText(item.title + ' ' + item.value + '%', x, y);
		context.restore();
	}
}