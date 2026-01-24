@extends('layouts.admin')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Instructor Payouts</h1>
        <p class="text-gray-500">Manage earnings and payments for your instructors.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                    <th class="px-6 py-4">Instructor Name</th>
                    <th class="px-6 py-4">Total Earnings (70%)</th>
                    <th class="px-6 py-4">Paid Amount</th>
                    <th class="px-6 py-4">Pending Payout</th>
                    <th class="px-6 py-4">Last Payout</th>
                    @role('Super Admin')
                    <th class="px-6 py-4">Actions</th>
                    @endrole
                </tr>
            </thead>
            <tbody id="payouts_table_body" class="divide-y divide-gray-50 text-sm">
                <!-- Data will be loaded via JS -->
            </tbody>
        </table>
    </div>
</div>

@role('Super Admin')
<!-- Payout Modal -->
<div id="payoutModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <!-- ... same modal content ... -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Process Payout</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <input type="hidden" id="modal_user_id">
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-2">Instructor</p>
                <p id="modal_user_name" class="font-bold text-gray-900"></p>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Payout Amount (₹)</label>
                <input type="number" id="modal_amount" step="0.01" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
            </div>
            <button onclick="submitPayout()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-blue-200">
                Mark as Paid
            </button>
        </div>
    </div>
</div>
@endrole

<script>
    const isSuperAdmin = @json(auth()->user()->hasRole('Super Admin'));

    document.addEventListener('DOMContentLoaded', fetchEarnings);

    async function fetchEarnings() {
        try {
            const response = await fetch('/api/admin/instructors/earnings', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            
            const tbody = document.getElementById('payouts_table_body');
            tbody.innerHTML = '';

            data.forEach(instructor => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50/50 transition-colors';
                
                let actionHtml = '';
                if (isSuperAdmin) {
                    actionHtml = `
                        <td class="px-6 py-4">
                            <button onclick="openModal(${instructor.id}, '${instructor.name}', ${instructor.pending_payouts.replace(',','')})" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                Payout Now
                            </button>
                        </td>
                    `;
                }

                tr.innerHTML = `
                    <td class="px-6 py-4 font-medium text-gray-900">${instructor.name}</td>
                    <td class="px-6 py-4 text-gray-600">₹${instructor.total_earnings}</td>
                    <td class="px-6 py-4 text-green-600 font-bold">₹${instructor.paid_amount}</td>
                    <td class="px-6 py-4 text-orange-600 font-bold">₹${instructor.pending_payouts}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs font-mono">${instructor.last_payout_date}</td>
                    ${actionHtml}
                `;
                tbody.appendChild(tr);
            });

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${isSuperAdmin ? 6 : 5}" class="px-6 py-12 text-center text-gray-400">No instructors found with sales data.</td></tr>`;
            }
        } catch (error) {
            console.error('Error fetching earnings:', error);
        }
    }

    function openModal(userId, userName, pendingAmount) {
        document.getElementById('modal_user_id').value = userId;
        document.getElementById('modal_user_name').innerText = userName;
        document.getElementById('modal_amount').value = pendingAmount;
        document.getElementById('payoutModal').classList.remove('hidden');
        document.getElementById('payoutModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('payoutModal').classList.add('hidden');
        document.getElementById('payoutModal').classList.remove('flex');
    }

    async function submitPayout() {
        const userId = document.getElementById('modal_user_id').value;
        const amount = document.getElementById('modal_amount').value;

        if (!amount || amount <= 0) {
            alert('Please enter a valid amount');
            return;
        }

        try {
            const response = await fetch('/api/admin/payouts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: userId,
                    amount: amount
                })
            });

            if (response.ok) {
                closeModal();
                fetchEarnings();
                alert('Payout recorded successfully');
            } else {
                alert('Failed to record payout');
            }
        } catch (error) {
            console.error('Error submitting payout:', error);
        }
    }
</script>
@endsection
