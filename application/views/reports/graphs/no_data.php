<script>
	// No data message
	document.addEventListener('DOMContentLoaded', function() {
		var chartContainer = document.getElementById('chart1');
		if (chartContainer) {
			chartContainer.innerHTML = '<div style="text-align: center; padding: 50px; color: #666; font-size: 18px;">No data available for the selected date range</div>';
		}
	});
</script>
