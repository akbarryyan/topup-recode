@extends('layouts.app')

@section('title', 'Order ' . $game)

@section('content')
<x-order.content :game="$game" :gameImage="$gameImage">
    <!-- Account Fields -->
    <div class="bg-[#1D1618] rounded px-3 py-3">
        <h2 class="text-white text-lg mb-4 flex items-center gap-2">
            Input Akun
            <i class="fas fa-info-circle text-green-500"></i>
        </h2>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($accountFields as $field)
                @php
                    $inputId = 'account_' . $field->field_key;
                @endphp
                <div>
                    <label for="{{ $inputId }}" class="block text-sm text-gray-300 mb-2">
                        {{ $field->label }}
                        @if($field->is_required)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <input type="{{ $field->input_type }}"
                           id="{{ $inputId }}"
                           name="account_fields[{{ $field->field_key }}]"
                           data-account-field
                           data-field-key="{{ $field->field_key }}"
                           data-required="{{ $field->is_required ? '1' : '0' }}"
                           class="w-full bg-[#27272A] rounded-lg px-3 py-2 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 transition-colors"
                           placeholder="{{ $field->placeholder ?? $field->label }}">
                    @if($field->helper_text)
                        <p class="text-gray-400 text-[13px] italic mt-2">{{ $field->helper_text }}</p>
                    @endif
                </div>
                @endforeach
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
            Pilih Item
        </h2>
        
        @if(stripos($game, 'Mobile Legends') !== false)
        <!-- Category Tabs for Mobile Legends -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <button class="category-filter active bg-[#27272A] hover:bg-[#2f3240] rounded-lg p-4 transition-all border-2 border-red-500" data-category="instant">
                <div class="flex flex-col items-center gap-2">
                    <img src="{{ asset('diamond.webp') }}" alt="Diamond" class="w-8 h-8">
                    <span class="text-white font-medium text-[15px]">Instant Top Up</span>
                </div>
            </button>
            <button class="category-filter bg-[#27272A] hover:bg-[#2f3240] rounded-lg p-4 transition-all border-2 border-transparent" data-category="special">
                <div class="flex flex-col items-center gap-2">
                    <span class="text-3xl">🔥</span>
                    <span class="text-white font-medium text-[15px]">Special Items</span>
                </div>
            </button>
        </div>
        @endif
        
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3" id="items-container">
            {{-- Items will be rendered by JavaScript for Mobile Legends --}}
            @if(stripos($game, 'Mobile Legends') === false)
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
            @endif
        </div>
    </div>
    
    <!-- Payment Method -->
    <div class="bg-[#1D1618] rounded px-3 py-3">
        <h2 class="text-white text-lg mb-4 flex items-center gap-2">
            <i class="fas fa-credit-card text-green-500"></i>
            Pilih Metode Pembayaran
        </h2>
        
        <div class="space-y-3">
            <!-- Credits -->
            <div class="bg-[#27272A] rounded-lg p-3 relative">
                <div class="absolute top-2 right-2">
                    <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">BEST PRICE</span>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-yellow-500 rounded-full"></div>
                        <span class="text-white font-medium">Credits</span>
                    </div>
                    <span class="text-gray-400 text-sm">Min Rp 0</span>
                </div>
            </div>

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
console.log('Script loaded!');

// All services data
const allServices = @json($services->toArray());

// Check if this is Mobile Legends
const isMobileLegends = '{{ $game }}'.toLowerCase().includes('mobile legends');

console.log('Game:', '{{ $game }}');
console.log('Is Mobile Legends:', isMobileLegends);
console.log('All services:', allServices);
console.log('All services count:', allServices.length);

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready!');
    
    let selectedItem = null;
    let selectedPayment = null;
    let currentCategory = 'instant'; // Default category for Mobile Legends
    const accountFieldInputs = document.querySelectorAll('[data-account-field]');
    const whatsappInput = document.getElementById('contact_whatsapp');
    
    // Function to filter and render items
    function renderItems(category) {
        const container = document.getElementById('items-container');
        container.innerHTML = '';
        
        console.log('Rendering items for category:', category);
        console.log('All services:', allServices);
        console.log('Is Mobile Legends:', isMobileLegends);
        
        let filteredServices = allServices;
        
        if (isMobileLegends) {
            if (category === 'special') {
                // Show items with "Pass" in the name
                filteredServices = allServices.filter(service => 
                    service.name.toLowerCase().includes('pass')
                );
            } else {
                // Show items without "Pass" in the name
                filteredServices = allServices.filter(service => 
                    !service.name.toLowerCase().includes('pass')
                );
            }
        }
        
        console.log('Filtered services:', filteredServices);
        
        // Render filtered items
        filteredServices.forEach(service => {
            let itemHtml = '';
            
            if (isMobileLegends && category === 'instant') {
                // Instant Top Up style with vertical layout
                itemHtml = `
                    <div class="item-card bg-[#1a1a1a] border-2 border-transparent rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-all duration-200"
                         data-code="${service.code}"
                         data-price="${service.price_basic}"
                         data-name="${service.name}">
                        <div class="flex flex-col items-center text-center">
                            <h3 class="text-white text-xs font-medium mb-3">${service.name}</h3>
                            <div class="flex items-center gap-2">
                                <img src="/diamond.webp" alt="Diamond" class="w-8 h-8 mb-3">
                                <p class="text-white font-bold text-sm mb-4">Rp ${parseInt(service.price_basic).toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    </div>
                `;
            } else if (isMobileLegends && category === 'special') {
                // Special Items style with vertical layout and pass image
                itemHtml = `
                    <div class="item-card bg-[#1a1a1a] border-2 border-transparent rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-all duration-200"
                         data-code="${service.code}"
                         data-price="${service.price_basic}"
                         data-name="${service.name}">
                        <div class="flex flex-col items-center text-center">
                            <h3 class="text-white text-xs font-medium mb-3">${service.name}</h3>
                            <div class="flex items-center gap-2">
                                <img src="/pass.webp" alt="Pass" class="w-8 h-8 mb-3">
                                <p class="text-white font-bold text-sm mb-4">Rp ${parseInt(service.price_basic).toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // Default style (horizontal layout for other games)
                itemHtml = `
                    <div class="item-card bg-[#0f1117] border border-white/10 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-all duration-200"
                         data-code="${service.code}"
                         data-price="${service.price_basic}"
                         data-name="${service.name}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-white font-medium mb-1">${service.name}</h3>
                                ${service.note ? `<p class="text-gray-400 text-xs mb-2">${service.note}</p>` : ''}
                                <p class="text-blue-500 font-semibold">Rp ${parseInt(service.price_basic).toLocaleString('id-ID')}</p>
                            </div>
                            <div class="item-check hidden">
                                <i class="fas fa-check-circle text-blue-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            container.insertAdjacentHTML('beforeend', itemHtml);
        });
        
        // Re-attach click handlers
        attachItemClickHandlers();
    }
    
    // Handle category filter for Mobile Legends
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active state from all
            document.querySelectorAll('.category-filter').forEach(b => {
                b.classList.remove('active', 'border-red-500');
                b.classList.add('border-transparent');
            });
            
            // Add active state to clicked
            this.classList.add('active', 'border-red-500');
            this.classList.remove('border-transparent');
            
            // Get category
            currentCategory = this.dataset.category;
            
            // Clear selection
            selectedItem = null;
            updateSummary();
            checkFormValidity();
            
            // Render items
            renderItems(currentCategory);
        });
    });
    
    // Function to attach click handlers to item cards
    function attachItemClickHandlers() {
        document.querySelectorAll('.item-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selection from all cards
                if (isMobileLegends && currentCategory === 'instant') {
                    document.querySelectorAll('.item-card').forEach(c => {
                        c.classList.remove('border-blue-500');
                        c.classList.add('border-transparent');
                    });
                } else {
                    document.querySelectorAll('.item-card').forEach(c => {
                        c.classList.remove('border-blue-500', 'bg-blue-500/5');
                        const check = c.querySelector('.item-check');
                        if (check) check.classList.add('hidden');
                    });
                }
                
                // Add selection to clicked card
                if (isMobileLegends && currentCategory === 'instant') {
                    this.classList.add('border-blue-500');
                    this.classList.remove('border-transparent');
                } else {
                    this.classList.add('border-blue-500', 'bg-blue-500/5');
                    const check = this.querySelector('.item-check');
                    if (check) check.classList.remove('hidden');
                }
                
                // Store selected item
                selectedItem = {
                    code: this.dataset.code,
                    name: this.dataset.name,
                    price: parseInt(this.dataset.price)
                };
                
                // Update summary
                updateSummary();
                
                // Enable order button if all required fields are filled
                checkFormValidity();
            });
        });
    }
    
    // Initial render
    if (isMobileLegends) {
        renderItems(currentCategory);
    } else {
        // For non-Mobile Legends games, attach handlers to existing items
        attachItemClickHandlers();
    }
    
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
        const requiredFieldsFilled = Array.from(accountFieldInputs).every(input => {
            if (input.dataset.required === '1') {
                return input.value.trim() !== '';
            }
            return true;
        });
        const whatsappValue = whatsappInput ? whatsappInput.value.trim() : '';
        const hasSelectedItem = selectedItem !== null;
        const hasSelectedPayment = selectedPayment !== null;
        
        const btnOrder = document.getElementById('btn-order');
        if (requiredFieldsFilled && whatsappValue && hasSelectedItem && hasSelectedPayment) {
            btnOrder.disabled = false;
        } else {
            btnOrder.disabled = true;
        }
    }
    
    // Listen to input changes
    accountFieldInputs.forEach(input => {
        input.addEventListener('input', checkFormValidity);
    });
    if (whatsappInput) {
        whatsappInput.addEventListener('input', checkFormValidity);
    }
    
    // Handle order submission
    document.getElementById('btn-order').addEventListener('click', function() {
        const whatsapp = whatsappInput ? whatsappInput.value.trim() : '';
        const email = document.getElementById('contact_email').value.trim();
        const accountData = {};
        accountFieldInputs.forEach(input => {
            accountData[input.dataset.fieldKey] = input.value.trim();
        });
        
        if (!selectedItem) {
            if (typeof swal !== 'undefined') {
                swal({
                    title: 'Oops!',
                    text: 'Silakan pilih item terlebih dahulu',
                    icon: 'warning',
                    button: 'OK'
                });
            } else {
                alert('Silakan pilih item terlebih dahulu');
            }
            return;
        }

        if (!selectedPayment) {
            if (typeof swal !== 'undefined') {
                swal({
                    title: 'Oops!',
                    text: 'Silakan pilih metode pembayaran',
                    icon: 'warning',
                    button: 'OK'
                });
            } else {
                alert('Silakan pilih metode pembayaran');
            }
            return;
        }
        
        // Show loading state
        const btnOrder = this;
        const originalText = btnOrder.innerHTML;
        btnOrder.disabled = true;
        btnOrder.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Prepare data
        const formData = {
            game: '{{ $game }}',
            service_code: selectedItem.code,
            account_fields: accountData,
            whatsapp: whatsapp,
            email: email,
            payment_method_id: selectedPayment.id,
            _token: '{{ csrf_token() }}'
        };
        
        // Submit order
        fetch('{{ route("order.game.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(async response => {
            const isJson = response.headers.get('content-type')?.includes('application/json');
            const data = isJson ? await response.json() : null;

            if (!response.ok) {
                // Handle non-2xx responses
                const errorMessage = (data && data.message) || response.statusText || 'Terjadi kesalahan pada server';
                throw new Error(errorMessage);
            }

            if (data && data.success) {
                if (typeof swal !== 'undefined') {
                    swal({
                        title: 'Berhasil!',
                        text: 'Pesanan berhasil dibuat. Mengalihkan ke halaman pembayaran...',
                        icon: 'success',
                        buttons: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = data.data.payment_url || data.data.redirect_url;
                    });
                } else {
                    alert('Pesanan berhasil! Mengalihkan...');
                    window.location.href = data.data.payment_url || data.data.redirect_url;
                }
            } else {
                throw new Error((data && data.message) || 'Terjadi kesalahan saat memproses pesanan');
            }
        })
        .catch(error => {
            console.error('Order Error:', error);
            if (typeof swal !== 'undefined') {
                swal({
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan sistem',
                    icon: 'error',
                    button: 'OK'
                });
            } else {
                alert('Gagal: ' + (error.message || 'Terjadi kesalahan sistem'));
            }
        })
        .finally(() => {
            // Reset button
            if (btnOrder) {
                btnOrder.disabled = false;
                btnOrder.innerHTML = originalText;
            }
        });
    });
});
</script>

@endsection
