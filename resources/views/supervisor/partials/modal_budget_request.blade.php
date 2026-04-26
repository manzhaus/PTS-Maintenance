<div class="modal fade" id="modalRequestBudget" tabindex="-1" role="dialog" aria-labelledby="modalRequestLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold text-dark" id="modalRequestLabel">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>Borang Mohon Bajet Tambahan
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('budget.request.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2 small border-0">
                        <i class="fas fa-info-circle mr-1"></i> Permohonan ini akan dihantar ke HQ Admin untuk semakan dan kelulusan.
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small">Jumlah Diperlukan (RM)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light font-weight-bold">RM</span>
                            </div>
                            <input type="number" name="jumlah_dipohon" class="form-control" placeholder="0.00" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small">Sebab / Justifikasi</label>
                        <textarea name="sebab" class="form-control" rows="4" placeholder="Sila nyatakan mengapa bajet tambahan diperlukan (cth: Kerosakan luar jangka, maintenance lori tambahan)..." required></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold small">Lampiran Sebut Harga (Quotation)</label>
                        <div class="custom-file">
                            <input type="file" name="lampiran" class="custom-file-input" id="customFile" accept=".pdf,.jpg,.png,.jpeg">
                            <label class="custom-file-label" for="customFile">Pilih fail...</label>
                        </div>
                        <small class="text-muted italic">Format: PDF, JPG, PNG (Max: 2MB)</small>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning font-weight-bold px-4 shadow-sm">
                        Hantar Permohonan <i class="fas fa-paper-plane ml-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>