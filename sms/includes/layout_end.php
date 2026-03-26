    </div><!-- .page-body -->
  </main>

</div><!-- .shell -->

<!-- Modal backdrop (shared) -->
<div class="modal-backdrop" id="modalBackdrop" onclick="if(event.target===this)closeModal()">
  <div class="modal" id="modalBox">
    <button class="modal-close" onclick="closeModal()">&#x2715;</button>
    <h3 id="modalTitle"></h3>
    <div id="modalBody"></div>
  </div>
</div>

<script>
function openModal(title, bodyHtml) {
  document.getElementById('modalTitle').textContent = title;
  document.getElementById('modalBody').innerHTML = bodyHtml;
  document.getElementById('modalBackdrop').classList.add('open');
}
function closeModal() {
  document.getElementById('modalBackdrop').classList.remove('open');
}
</script>
</body>
</html>
