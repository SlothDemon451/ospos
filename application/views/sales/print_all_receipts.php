<?php $this->load->view("partial/header"); ?>

<style>
@media print {
  .pagebreak { page-break-before: always; }
}
.receipt-wrapper { margin-bottom: 20px; }
.suspended-header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
.suspended-header-table td, .suspended-header-table th { border: 1px solid #333; padding: 4px 8px; vertical-align: top; }
.suspended-header-table th { background: #f4f4f4; text-align: left; }
.suspended-info-block { font-size: 13px; line-height: 1.4; margin-bottom: 6px; }
.suspended-items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.suspended-items-table th, .suspended-items-table td { border: 1px solid #333; padding: 6px 8px; }
.suspended-items-table th { background: #f4f4f4; }
.suspended-total-row td { font-size: 1.5em; font-weight: bold; text-align: right; border-top: 2px solid #000; }
.suspended-footer-note { margin-top: 18px; font-size: 13px; }
.suspended-employee { margin-top: 12px; font-size: 13px; }
</style>

<div class="container-fluid">
  <h3 class="print_hide">Batch Receipts: <?php echo htmlspecialchars($start_date).' to '.htmlspecialchars($end_date); ?></h3>
  <?php if(empty($sale_ids)) : ?>
    <div class="alert alert-info print_hide" style="margin-top:10px;">No receipts to print for the selected date range.</div>
  <?php else: ?>
    <div id="batch_container"></div>
    <div id="progress" class="print_hide" style="margin-top:10px;">
      Loading <span id="loaded">0</span> / <span id="total">0</span> receipts...
    </div>
    <div id="print_controls" class="print_hide" style="margin-top:20px; text-align:center; display:none;">
      <button type="button" class="btn btn-primary btn-lg" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> Print All Receipts
      </button>
    </div>
    <script>
    (function(){
      var saleIds = <?php echo json_encode(array_values($sale_ids)); ?>;
      var total = saleIds.length;
      var loaded = 0;
      document.getElementById('total').textContent = total;

      function appendReceipt(html, sid, isLast) {
        var container = document.getElementById('batch_container');
        var wrap = document.createElement('div');
        wrap.className = 'receipt-wrapper';
        wrap.innerHTML = html || ('<div class="alert alert-warning">Unable to load receipt #'+ sid +'</div>');
        container.appendChild(wrap);
        if (!isLast) {
          var br = document.createElement('div');
          br.className = 'pagebreak';
          container.appendChild(br);
        }
      }

      function loadNext(index){
        if(index >= total){
          // All loaded, show print button instead of auto-printing
          document.getElementById('progress').style.display = 'none';
          document.getElementById('print_controls').style.display = 'block';
          return;
        }
        var sid = saleIds[index];
        var xhr = new XMLHttpRequest();
        // Use a dedicated batch receipt endpoint that returns only receipt content
        xhr.open('GET', '<?php echo site_url('sales/batch_receipt/'); ?>' + sid + '?embed=1&tax=0', true);
        xhr.onreadystatechange = function(){
          if(xhr.readyState === 4){
            appendReceipt(xhr.status === 200 ? xhr.responseText : null, sid, index === total - 1);
            loaded++;
            document.getElementById('loaded').textContent = loaded;
            // Load next
            loadNext(index + 1);
          }
        };
        xhr.send();
      }

      // Start sequential loading to avoid overwhelming the browser
      loadNext(0);
    })();
    </script>
  <?php endif; ?>
</div>

<?php $this->load->view("partial/footer"); ?>


