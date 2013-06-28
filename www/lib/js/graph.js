/*
	Draw graph by points using canvas
	@author Mikhail Miropolskiy <the-ms@ya.ru>
	@package Lib
	@copyright (c) 2013. Mikhail Miropolskiy. All Rights Reserved.

	<canvas width="600" height="400" id="graph" style="border: 1px solid black;"></canvas>
	<script>
		graph({
			id: 'graph',
			points: [
			[8, 10],
			[11, 5],
			[16, 0],
			[18, 5]
			],
			labelX: 'Часы',
			labelY: 'Производительность'
		});
	</script>
 */

function graph (config) {
	var canvas = document.getElementById(config.id),
		context = canvas.getContext('2d'),
		width = parseInt(canvas.getAttribute('width')),
		height = parseInt(canvas.getAttribute('height')),

		padding = parseInt(width / 10),
		startX = padding,
		endX = width - padding,
		startY = height - padding,
		endY = padding,

		points = config.points,
		maxPoints = config.maxPoints || getMaxPoints(),
		minPoints = config.minPoints || getMinPoints(),
		labelX = config.labelX || '',
		labelY = config.labelY || '',
		pointStepX = (endX - startX) / (maxPoints[0] - minPoints[0]),
		pointStepY = (startY - endY) / (maxPoints[1] - minPoints[1]);

	function getMaxPoints () {
		var i, point,
			maxX = 0,
			maxY = 0;

		for (i in points) {
			point = points[i];
			if (point[0] > maxX) {
				maxX = point[0];
			}
			if (point[1] > maxY) {
				maxY = point[1];
			}
		}

		return [maxX, maxY];
	}

	function getMinPoints () {
		var i, point,
			minX = Number.MAX_VALUE,
			minY = Number.MAX_VALUE;

		for (i in points) {
			point = points[i];
			if (point[0] < minX) {
				minX = point[0];
			}
			if (point[1] < minY) {
				minY = point[1];
			}
		}

		return [minX, minY];
	}

	function drawGrid() {
		var x, y;

		for (x = 0.5; x < width; x += 10) {
			context.moveTo(x, 0);
			context.lineTo(x, height);
		}

		for (y = 0.5; y < height; y += 10) {
			context.moveTo(0, y);
			context.lineTo(width, y);
		}

		context.strokeStyle = "#eee";
		context.stroke();
	}

	function drawAxis () {
		context.beginPath();
		context.moveTo(startX, endY);
		context.lineTo(startX, startY);
		context.lineTo(endX, startY);

		context.moveTo(startX - 5, endY + 5);
		context.lineTo(startX, endY);
		context.lineTo(startX + 5, endY + 5);

		context.moveTo(endX - 5, startY - 5);
		context.lineTo(endX, startY);
		context.lineTo(endX - 5, startY + 5);

		context.strokeStyle = '#000';
		context.stroke();
	}

	function drawAxisLabels () {
		var x = startX - 40,
			y = height / 2 + 40;

		context.font = 'bold 12px sans-serif';
		context.fillText(labelX, width / 2 - 40, startY + 40);
		context.save();
		context.translate(x, y);
		context.rotate(-Math.PI / 2);
		context.translate(-x, -y);
		context.fillText(labelY, x, y);
		context.restore();
	}

	function drawPoints () {
		var i, point, pointX, pointY;

		pointX = startX + (points[0][0] - minPoints[0]) * pointStepX;
		pointY = startY - (points[0][1] - minPoints[1]) * pointStepY;
		context.moveTo(pointX, pointY);
		for (i in points) {
			point = points[i];
			pointX = startX + (point[0] - minPoints[0]) * pointStepX;
			pointY = startY - (point[1] - minPoints[1]) * pointStepY;
			context.lineTo(pointX, pointY);
		}
		context.stroke();
	}

	function drawPointLabels () {
		var i, point, pointX, pointY;

		for (i in points) {
			point = points[i];

			pointX = startX + (point[0] - minPoints[0]) * pointStepX;
			pointY = startY + 20;
			context.fillText(point[0], pointX, pointY);

			pointX = startX - 25;
			pointY = startY - (point[1] - minPoints[1]) * pointStepY;
			context.fillText(point[1], pointX, pointY);
		}
	}

	drawGrid();
	drawAxis();
	drawAxisLabels();
	drawPoints();
	drawPointLabels();
}