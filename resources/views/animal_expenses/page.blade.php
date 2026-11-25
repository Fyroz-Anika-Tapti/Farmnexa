@extends('layouts.master')

@section('title', 'Animal Expenses')
@section('page-title', 'Animal Expenses')
@section('breadcrumb', 'Animals / Expenses')

@section('content')
<div class="row">

    {{-- Top: Animal + Date --}}
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cow"></i> Select Animal & Date</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Animal <span class="text-danger">*</span></label>
                        <select id="animal_select" class="form-control form-control-lg">
                            <option value="">-- Select Animal --</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->id }}">
                                    {{ $animal->animal_code }} ({{ ucfirst($animal->animal_type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Expense Date <span class="text-danger">*</span></label>
                        <input type="date" id="expense_date" class="form-control form-control-lg" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Section --}}
    <div class="col-md-12" id="main_section" style="display:none;">

        {{-- Animal Info --}}
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> <span id="info_code">-</span> — Cost Summary</h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-2">
                        <h6 class="text-muted">Purchase</h6>
                        <h5 id="info_purchase">0</h5>
                    </div>
                    <div class="col-md-2">
                        <h6 class="text-muted">Feed</h6>
                        <h5 id="info_feed">0</h5>
                    </div>
                    <div class="col-md-2">
                        <h6 class="text-muted">Medicine</h6>
                        <h5 id="info_medicine">0</h5>
                    </div>
                    <div class="col-md-2">
                        <h6 class="text-muted">Other</h6>
                        <h5 id="info_other">0</h5>
                    </div>
                    <div class="col-md-4 bg-warning rounded">
                        <h6 class="mt-2"><strong>TOTAL COST</strong></h6>
                        <h4><strong id="info_total">0</strong> BDT</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Expense Cards --}}
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus"></i> Add Expenses</h3>
                <div class="card-tools">
                    <small class="text-muted">শুধু যেখানে খরচ হয়েছে সেখানে amount দিন</small>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="expense_cards_grid">

                    {{-- Labor --}}
                    <div class="col-md-3 mb-3">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white p-2">
                                <strong>👷 Labor</strong>
                            </div>
                            <div class="card-body p-2">
                                <label class="small mb-1">Amount (BDT)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm expense-input"
                                       data-type="Labor" min="0" placeholder="0.00">
                                <label class="small mt-2 mb-1">Note</label>
                                <input type="text" class="form-control form-control-sm expense-note"
                                       data-type="Labor" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    {{-- Transport --}}
                    <div class="col-md-3 mb-3">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white p-2">
                                <strong>🚛 Transport</strong>
                            </div>
                            <div class="card-body p-2">
                                <label class="small mb-1">Amount (BDT)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm expense-input"
                                       data-type="Transport" min="0" placeholder="0.00">
                                <label class="small mt-2 mb-1">Note</label>
                                <input type="text" class="form-control form-control-sm expense-note"
                                       data-type="Transport" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    {{-- Utility --}}
                    <div class="col-md-3 mb-3">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark p-2">
                                <strong>💡 Utility</strong>
                            </div>
                            <div class="card-body p-2">
                                <label class="small mb-1">Amount (BDT)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm expense-input"
                                       data-type="Utility" min="0" placeholder="0.00">
                                <label class="small mt-2 mb-1">Note</label>
                                <input type="text" class="form-control form-control-sm expense-note"
                                       data-type="Utility" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    {{-- Rent --}}
                    <div class="col-md-3 mb-3">
                        <div class="card border-secondary h-100">
                            <div class="card-header bg-secondary text-white p-2">
                                <strong>🏠 Rent</strong>
                            </div>
                            <div class="card-body p-2">
                                <label class="small mb-1">Amount (BDT)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm expense-input"
                                       data-type="Rent" min="0" placeholder="0.00">
                                <label class="small mt-2 mb-1">Note</label>
                                <input type="text" class="form-control form-control-sm expense-note"
                                       data-type="Rent" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    {{-- Maintenance --}}
                    <div class="col-md-3 mb-3">
                        <div class="card border-dark h-100">
                            <div class="card-header bg-dark text-white p-2">
                                <strong>🔧 Maintenance</strong>
                            </div>
                            <div class="card-body p-2">
                                <label class="small mb-1">Amount (BDT)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm expense-input"
                                       data-type="Maintenance" min="0" placeholder="0.00">
                                <label class="small mt-2 mb-1">Note</label>
                                <input type="text" class="form-control form-control-sm expense-note"
                                       data-type="Maintenance" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    {{-- Veterinary --}}
                    <div class="col-md-3 mb-3">
                        <div class="card border-danger h-100">
                            <div class="card-header bg-danger text-white p-2">
                                <strong>💊 Veterinary</strong>
                            </div>
                            <div class="card-body p-2">
                                <label class="small mb-1">Amount (BDT)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm expense-input"
                                       data-type="Veterinary" min="0" placeholder="0.00">
                                <label class="small mt-2 mb-1">Note</label>
                                <input type="text" class="form-control form-control-sm expense-note"
                                       data-type="Veterinary" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    {{-- Other --}}
                    <div class="col-md-3 mb-3">
                        <div class="card border-light h-100">
                            <div class="card-header bg-light p-2">
                                <strong>📝 Other</strong>
                            </div>
                            <div class="card-body p-2">
                                <label class="small mb-1">Amount (BDT)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm expense-input"
                                       data-type="Other" min="0" placeholder="0.00">
                                <label class="small mt-2 mb-1">Note</label>
                                <input type="text" class="form-control form-control-sm expense-note"
                                       data-type="Other" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer text-right">
                <h5>Total: <span id="live_total" class="text-primary">0.00</span> BDT</h5>
                <button type="button" id="save_all_btn" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Save All Expenses
                </button>
            </div>
        </div>

        {{-- Existing Expenses --}}
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list"></i> Existing Expenses</h3>
                <div class="card-tools">
                    <span id="expense_count" class="badge badge-info">0</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="expense_list"></div>
                <hr>
                <div class="text-right">
                    <h4>Total: <span id="expense_total" class="text-danger">0.00</span> BDT</h4>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const animalSelect = document.getElementById('animal_select');
    const mainSection  = document.getElementById('main_section');
    const csrfToken    = '{{ csrf_token() }}';
    const liveTotal    = document.getElementById('live_total');

    // Update live total as amounts change
    document.querySelectorAll('.expense-input').forEach(input => {
        input.addEventListener('input', updateLiveTotal);
    });

    function updateLiveTotal() {
        let total = 0;
        document.querySelectorAll('.expense-input').forEach(input => {
            const val = parseFloat(input.value);
            if (val > 0) total += val;
        });
        liveTotal.textContent = total.toFixed(2);
    }

    animalSelect.addEventListener('change', function() {
        const animalId = this.value;
        if (!animalId) {
            mainSection.style.display = 'none';
            return;
        }
        loadAnimalData(animalId);
    });

    function loadAnimalData(animalId) {
        fetch(`/animal-expenses/${animalId}/info`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('info_code').textContent = data.animal_code;
                document.getElementById('info_purchase').textContent = data.purchase_value.toFixed(2);
                document.getElementById('info_feed').textContent = data.total_feed_cost.toFixed(2);
                document.getElementById('info_medicine').textContent = data.total_medicine_cost.toFixed(2);
                document.getElementById('info_other').textContent = data.total_other_expense.toFixed(2);
                document.getElementById('info_total').textContent = data.total_cost.toFixed(2);

                mainSection.style.display = 'block';
                renderExpenses(data.expenses);
            })
            .catch(err => alert('Error: ' + err));
    }

    // Save All button
    document.getElementById('save_all_btn').addEventListener('click', function() {
        const animalId = animalSelect.value;
        const expenseDate = document.getElementById('expense_date').value;

        if (!animalId) { alert('Please select an animal.'); return; }
        if (!expenseDate) { alert('Please select a date.'); return; }

        const expenses = [];
        document.querySelectorAll('.expense-input').forEach(input => {
            const amount = parseFloat(input.value);
            if (amount > 0) {
                const type = input.dataset.type;
                const note = document.querySelector(`.expense-note[data-type="${type}"]`).value;
                expenses.push({
                    type: type,
                    amount: amount,
                    description: note,
                });
            }
        });

        if (expenses.length === 0) {
            alert('Please enter at least one expense amount.');
            return;
        }

        const formData = new FormData();
        formData.append('expense_date', expenseDate);
        expenses.forEach((exp, i) => {
            formData.append(`expenses[${i}][type]`, exp.type);
            formData.append(`expenses[${i}][amount]`, exp.amount);
            formData.append(`expenses[${i}][description]`, exp.description || '');
        });

        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        fetch(`/animal-expenses/${animalId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Reset all inputs
                document.querySelectorAll('.expense-input').forEach(i => i.value = '');
                document.querySelectorAll('.expense-note').forEach(i => i.value = '');
                updateLiveTotal();

                this.innerHTML = '<i class="fas fa-check"></i> ' + data.message;
                loadAnimalData(animalId);

                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-save"></i> Save All Expenses';
                    this.disabled = false;
                }, 2000);
            } else {
                alert('Error: ' + (data.message || 'Unknown'));
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-save"></i> Save All Expenses';
            }
        })
        .catch(err => {
            alert('Error: ' + err);
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-save"></i> Save All Expenses';
        });
    });

    function renderExpenses(expenses) {
        const list = document.getElementById('expense_list');
        list.innerHTML = '';

        let total = 0;
        expenses.forEach(exp => {
            total += parseFloat(exp.amount);

            const colorMap = {
                Labor: 'primary', Transport: 'info', Utility: 'warning',
                Rent: 'secondary', Maintenance: 'dark', Veterinary: 'danger', Other: 'secondary'
            };
            const color = colorMap[exp.expense_type] || 'secondary';

            list.innerHTML += `
                <div class="col-md-3 mb-2">
                    <div class="card border-${color}">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between">
                                <span class="badge badge-${color}">${exp.expense_type}</span>
                                <strong class="text-danger">${parseFloat(exp.amount).toFixed(2)}</strong>
                            </div>
                            <small class="text-muted">${exp.expense_date}</small>
                            ${exp.description ? '<br><small>' + exp.description + '</small>' : ''}
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('expense_count').textContent = expenses.length;
        document.getElementById('expense_total').textContent = total.toFixed(2);
    }
</script>
@endpush