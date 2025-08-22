<?php $this->load->view("partial/header"); ?>

<style>
@media print {
  .pagebreak { page-break-before: always; }
}
.receipt-wrapper { margin-bottom: 20px; }
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
          // All loaded, trigger print
          window.print();
          return;
        }
        var sid = saleIds[index];
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?php echo site_url('sales/receipt/'); ?>' + sid + '?embed=1', true);
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


