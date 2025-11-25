@extends('layouts.app')

@section('title', 'Order ' . $brand)

@section('content')
<x-order.content :game="$brand" :gameImage="$brandImage">
    <!-- Phone Number Input -->
    <div class="bg-[#1D1618] rounded px-3 py-3">
        <h2 class="text-white text-lg mb-4 flex items-center gap-2">
            Input Nomor HP
            <i class="fas fa-phone text-green-500"></i>
        </h2>

        <div class="space-y-4">
            <div>
                <label for="phone_number" class="block text-sm text-gray-300 mb-2">
                    Nomor HP
                    <span class="text-red-500">*</span>
                </label>
                <input type="tel"
                       id="phone_number"
                       name="phone_number"
                       class="w-full bg-[#27272A] rounded-lg px-3 py-2 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 transition-colors"
                       placeholder="08xxxxxxxxxx">
                <p class="text-gray-400 text-[13px] italic mt-2">Masukkan nomor HP yang akan diisi pulsa/paket data</p>
            </div>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="bg-[#1D1618] rounded px-3 py-3">
        <h2 class="text-white text-lg mb-4">
            Masukkan Kontak Anda
        </h2>
        
        <div class="space-y-4">
            <div>
                <input type="text" 
                       id="contact_email" 
                       name="contact_email"
                       class="w-full bg-[#27272A] rounded-lg px-3 py-2 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 transition-colors"
                       placeholder="*Email">
            </div>
            
            <div class="text-gray-400 text-[13px] italic">
                <p>Bukti pembayaran atas pembelian ini akan kami kirimkan ke Email</p>
            </div>
        </div>
    </div>
    
    <!-- Select Item -->
    <div class="bg-[#1D1618] rounded p-3">
        <h2 class="text-white text-lg mb-4 flex items-center gap-2">
            <i class="fas fa-shopping-cart text-green-500"></i>
            Pilih Nominal
        </h2>
        
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3" id="items-container">
            @foreach($services as $service)
            <div class="item-card bg-[#0f1117] border border-white/10 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-all duration-200"
                 data-code="{{ $service->code }}"
                 data-price="{{ $service->price_basic }}"
                 data-name="{{ $service->name }}">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-white font-medium mb-1">{{ $service->name }}</h3>
                        @if($service->note)
                            <p class="text-gray-400 text-xs mb-2">{{ $service->note }}</p>
                        @endif
                        <p class="text-blue-500 font-semibold">Rp {{ number_format($service->price_basic, 0, ',', '.') }}</p>
                    </div>
                    <div class="item-check hidden">
                        <i class="fas fa-check-circle text-blue-500 text-2xl"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Payment Method -->
    <div class="bg-[#1D1618] rounded px-3 py-3">
        <h2 class="text-white text-lg mb-4 flex items-center gap-2">
            <i class="fas fa-credit-card text-green-500"></i>
            Pilih Metode Pembayaran
        </h2>
        
        <div class="space-y-3">
            <!-- QRIS Payment Methods -->
            @if(isset($paymentMethods['qris']) && $paymentMethods['qris']->count() > 0)
            <div class="bg-[#27272A] rounded-lg p-3 relative">
                <div class="absolute top-2 right-2">
                    <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">BEST PRICE</span>
                </div>
                <div class="flex items-center justify-between cursor-pointer" onclick="togglePaymentCategory('qris')">
                    <span class="text-white font-medium">QRIS</span>
                    <i class="fas fa-chevron-down text-gray-400" id="qris-icon"></i>
                </div>
                <div id="qris-content" class="hidden mt-3 space-y-2">
                    @foreach($paymentMethods['qris'] as $qris)
                    <div class="payment-method-card bg-[#1a1a1a] rounded-lg p-3 cursor-pointer hover:border hover:border-blue-500 transition-all"
                         data-payment-id="{{ $qris->id }}"
                         data-payment-code="{{ $qris->code }}"
                         data-payment-name="{{ $qris->name }}"
                         data-payment-fee="{{ $qris->total_fee }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($qris->image_url)
                                    <img src="{{ $qris->image_url }}" alt="{{ $qris->name }}" class="h-6 w-auto object-contain">
                                @endif
                                <span class="text-white text-sm">{{ $qris->name }}</span>
                            </div>
                            <span class="text-gray-400 text-xs">Fee: {{ $qris->formatted_customer_fee }}</span>
                        </div>
                        @if($qris->description)
                            <p class="text-gray-400 text-xs mt-1">{{ $qris->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- E-Wallet -->
            @if(isset($paymentMethods['ewallet']) && $paymentMethods['ewallet']->count() > 0)
            <div class="bg-[#27272A] rounded-lg p-3">
                <div class="flex items-center justify-between cursor-pointer" onclick="togglePaymentCategory('ewallet')">
                    <span class="text-white font-medium">E-Wallet</span>
                    <i class="fas fa-chevron-down text-gray-400" id="ewallet-icon"></i>
                </div>
                <div id="ewallet-content" class="hidden mt-3 space-y-2">
                    @foreach($paymentMethods['ewallet'] as $ewallet)
                    <div class="payment-method-card bg-[#1a1a1a] rounded-lg p-3 cursor-pointer hover:border hover:border-blue-500 transition-all"
                         data-payment-id="{{ $ewallet->id }}"
                         data-payment-code="{{ $ewallet->code }}"
                         data-payment-name="{{ $ewallet->name }}"
                         data-payment-fee="{{ $ewallet->total_fee }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($ewallet->image_url)
                                    <img src="{{ $ewallet->image_url }}" alt="{{ $ewallet->name }}" class="h-6 w-auto object-contain">
                                @endif
                                <span class="text-white text-sm">{{ $ewallet->name }}</span>
                            </div>
                            <span class="text-gray-400 text-xs">Fee: {{ $ewallet->formatted_customer_fee }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Retail -->
            @if(isset($paymentMethods['retail']) && $paymentMethods['retail']->count() > 0)
            <div class="bg-[#27272A] rounded-lg p-3">
                <div class="flex items-center justify-between cursor-pointer" onclick="togglePaymentCategory('retail')">
                    <span class="text-white font-medium">Retail</span>
                    <i class="fas fa-chevron-down text-gray-400" id="retail-icon"></i>
                </div>
                <div id="retail-content" class="hidden mt-3 space-y-2">
                    @foreach($paymentMethods['retail'] as $retail)
                    <div class="payment-method-card bg-[#1a1a1a] rounded-lg p-3 cursor-pointer hover:border hover:border-blue-500 transition-all"
                         data-payment-id="{{ $retail->id }}"
                         data-payment-code="{{ $retail->code }}"
                         data-payment-name="{{ $retail->name }}"
                         data-payment-fee="{{ $retail->total_fee }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($retail->image_url)
                                    <img src="{{ $retail->image_url }}" alt="{{ $retail->name }}" class="h-6 w-auto object-contain">
                                @endif
                                <span class="text-white text-sm">{{ $retail->name }}</span>
                            </div>
                            <span class="text-gray-400 text-xs">Fee: {{ $retail->formatted_customer_fee }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Virtual Account -->
            @if(isset($paymentMethods['virtual_account']) && $paymentMethods['virtual_account']->count() > 0)
            <div class="bg-[#27272A] rounded-lg p-3">
                <div class="flex items-center justify-between cursor-pointer" onclick="togglePaymentCategory('va')">
                    <span class="text-white font-medium">Virtual Account</span>
                    <i class="fas fa-chevron-down text-gray-400" id="va-icon"></i>
                </div>
                <div id="va-content" class="hidden mt-3 space-y-2">
                    @foreach($paymentMethods['virtual_account'] as $va)
                    <div class="payment-method-card bg-[#1a1a1a] rounded-lg p-3 cursor-pointer hover:border hover:border-blue-500 transition-all"
                         data-payment-id="{{ $va->id }}"
                         data-payment-code="{{ $va->code }}"
                         data-payment-name="{{ $va->name }}"
                         data-payment-fee="{{ $va->total_fee }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($va->image_url)
                                    <img src="{{ $va->image_url }}" alt="{{ $va->name }}" class="h-6 w-auto object-contain">
                                @endif
                                <span class="text-white text-sm">{{ $va->name }}</span>
                            </div>
                            <span class="text-gray-400 text-xs">Fee: {{ $va->formatted_customer_fee }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
    function togglePaymentCategory(category) {
        const content = document.getElementById(category + '-content');
        const icon = document.getElementById(category + '-icon');
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
    </script>
    
    <!-- Contact Info -->
    <div class="bg-[#1a1d2e] rounded-xl p-6">
        <h2 class="text-white font-semibold text-lg mb-4 flex items-center gap-2">
            <i class="fas fa-envelope text-red-500"></i>
            Informasi Kontak
        </h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-gray-300 text-sm mb-2">WhatsApp Number</label>
                <input type="text" 
                       id="contact_whatsapp" 
                       name="whatsapp"
                       class="w-full bg-[#0f1117] border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 transition-colors"
                       placeholder="08xxxxxxxxxx">
            </div>
            
            <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4">
                <p class="text-yellow-400 text-sm flex items-start gap-2">
                    <i class="fas fa-exclamation-triangle mt-0.5"></i>
                    <span>Isi nomor WhatsApp dengan benar untuk menerima notifikasi pesanan</span>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Order Summary & Submit -->
    <div class="bg-[#000000] rounded px-3 py-4 sticky bottom-0">
        <div class="bg-[#2a2d3e] border border-dashed border-gray-600 rounded-lg p-4 mb-4 text-center">
            <p class="text-gray-400 text-sm" id="summary-text">No product selected yet.</p>
        </div>
        
        <button type="button" 
                id="btn-order"
                class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
            <i class="fas fa-shopping-bag"></i>
            Order Now!
        </button>
    </div>
</x-order.content>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedItem = null;
    let selectedPayment = null;
    const phoneInput = document.getElementById('phone_number');
    const whatsappInput = document.getElementById('contact_whatsapp');
    const emailInput = document.getElementById('contact_email');
    
    // Handle item selection
    document.querySelectorAll('.item-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove selection from all cards
            document.querySelectorAll('.item-card').forEach(c => {
                c.classList.remove('border-blue-500', 'bg-blue-500/5');
                const check = c.querySelector('.item-check');
                if (check) check.classList.add('hidden');
            });
            
            // Add selection to clicked card
            this.classList.add('border-blue-500', 'bg-blue-500/5');
            const check = this.querySelector('.item-check');
            if (check) check.classList.remove('hidden');
            
            // Store selected item
            selectedItem = {
                code: this.dataset.code,
                name: this.dataset.name,
                price: parseInt(this.dataset.price)
            };
            
            // Update summary
            updateSummary();
            
            // Check form validity
            checkFormValidity();
        });
    });
    
    // Handle payment method selection
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove selection from all payment cards
            document.querySelectorAll('.payment-method-card').forEach(c => {
                c.classList.remove('border-blue-500', 'bg-blue-500/5');
            });
            
            // Add selection to clicked card
            this.classList.add('border-blue-500', 'bg-blue-500/5');
            
            // Store selected payment
            selectedPayment = {
                id: this.dataset.paymentId,
                code: this.dataset.paymentCode,
                name: this.dataset.paymentName,
                fee: parseFloat(this.dataset.paymentFee)
            };
            
            // Update summary
            updateSummary();
            
            // Check form validity
            checkFormValidity();
        });
    });
    
    // Update summary
    function updateSummary() {
        const summaryText = document.getElementById('summary-text');
        if (selectedItem) {
            const itemPrice = selectedItem.price;
            const paymentFee = selectedPayment ? selectedPayment.fee : 0;
            const total = itemPrice + paymentFee;
            
            let summaryHTML = '<div class="text-left">';
            summaryHTML += '<div class="flex justify-between mb-1">';
            summaryHTML += '<span class="text-gray-400">Harga Item:</span>';
            summaryHTML += `<span class="text-white">Rp ${itemPrice.toLocaleString('id-ID')}</span>`;
            summaryHTML += '</div>';
            
            if (selectedPayment) {
                summaryHTML += '<div class="flex justify-between mb-1">';
                summaryHTML += '<span class="text-gray-400">Biaya Admin:</span>';
                summaryHTML += `<span class="text-white">Rp ${paymentFee.toLocaleString('id-ID')}</span>`;
                summaryHTML += '</div>';
            }
            
            summaryHTML += '<div class="flex justify-between border-t border-gray-600 pt-1 mt-1">';
            summaryHTML += '<span class="text-gray-400 font-semibold">Total:</span>';
            summaryHTML += `<span class="text-white font-bold">Rp ${total.toLocaleString('id-ID')}</span>`;
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            
            summaryText.innerHTML = summaryHTML;
        } else {
            summaryText.textContent = 'No product selected yet.';
        }
    }
    
    // Check form validity
    function checkFormValidity() {
        const phoneValue = phoneInput ? phoneInput.value.trim() : '';
        const whatsappValue = whatsappInput ? whatsappInput.value.trim() : '';
        const emailValue = emailInput ? emailInput.value.trim() : '';
        const hasSelectedItem = selectedItem !== null;
        const hasSelectedPayment = selectedPayment !== null;
        
        const btnOrder = document.getElementById('btn-order');
        if (phoneValue && whatsappValue && emailValue && hasSelectedItem && hasSelectedPayment) {
            btnOrder.disabled = false;
        } else {
            btnOrder.disabled = true;
        }
    }
    
    // Listen to input changes
    if (phoneInput) phoneInput.addEventListener('input', checkFormValidity);
    if (whatsappInput) whatsappInput.addEventListener('input', checkFormValidity);
    if (emailInput) emailInput.addEventListener('input', checkFormValidity);
    
    // Handle order submission
    document.getElementById('btn-order').addEventListener('click', function() {
        const phoneNumber = phoneInput ? phoneInput.value.trim() : '';
        const whatsapp = whatsappInput ? whatsappInput.value.trim() : '';
        const email = emailInput ? emailInput.value.trim() : '';
        
        if (!selectedItem) {
            if (typeof swal !== 'undefined') {
                swal({
                    title: 'Oops!',
                    text: 'Silakan pilih nominal terlebih dahulu',
                    icon: 'warning',
                    button: 'OK'
                });
            } else {
                alert('Silakan pilih nominal terlebih dahulu');
            }
            return;
        }
        
        // TODO: Submit order to backend
        console.log('Order Data:', {
            brand: '{{ $brand }}',
            phone_number: phoneNumber,
            email: email,
            item_code: selectedItem.code,
            item_price: selectedItem.price,
            payment_method_id: selectedPayment ? selectedPayment.id : null,
            payment_method_code: selectedPayment ? selectedPayment.code : null,
            payment_fee: selectedPayment ? selectedPayment.fee : 0,
            total_amount: selectedItem.price + (selectedPayment ? selectedPayment.fee : 0),
            whatsapp: whatsapp
        });
        
        if (typeof swal !== 'undefined') {
            swal({
                title: 'Coming Soon!',
                text: 'Fitur checkout sedang dalam pengembangan',
                icon: 'info',
                button: 'OK'
            });
        } else {
            alert('Coming Soon! Fitur checkout sedang dalam pengembangan');
        }
    });
});
</script>

@endsection
