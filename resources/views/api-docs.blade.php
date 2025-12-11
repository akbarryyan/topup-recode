@extends('layouts.app')

@section('content')
<div class="mt-4 lg:mt-28">
    <div class="min-h-screen bg-[#050505] pt-24 pb-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    <i class="ri-code-s-slash-line text-yellow-500 mr-2"></i>
                    {{ app()->getLocale() === 'en' ? 'API Documentation' : 'Dokumentasi API' }}
                </h1>
                <p class="text-gray-400 max-w-2xl mx-auto">
                    {{ app()->getLocale() === 'en' ? 'Integrate our services into your application with our easy-to-use API.' : 'Integrasikan layanan kami ke dalam aplikasi Anda dengan API yang mudah digunakan.' }}
                </p>
            </div>
    
            <!-- API Info Card -->
            <div class="bg-[#111114] border border-white/10 rounded-2xl p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-white mb-2">
                            {{ app()->getLocale() === 'en' ? 'Getting Started' : 'Memulai' }}
                        </h2>
                        <p class="text-gray-400 text-sm">
                            {{ app()->getLocale() === 'en' ? 'To use our API, you need an API Key. Contact us to get your API credentials.' : 'Untuk menggunakan API kami, Anda memerlukan API Key. Hubungi kami untuk mendapatkan kredensial API Anda.' }}
                        </p>
                    </div>
                    <a href="{{ localized_url('/contact-us') }}" class="inline-flex items-center gap-2 bg-yellow-500 text-black px-6 py-3 rounded-xl font-semibold hover:bg-yellow-400 transition-colors whitespace-nowrap">
                        <i class="ri-mail-line"></i>
                        {{ app()->getLocale() === 'en' ? 'Contact Us' : 'Hubungi Kami' }}
                    </a>
                </div>
            </div>
    
            <!-- Base URL -->
            <div class="bg-[#111114] border border-white/10 rounded-2xl p-6 mb-8">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="ri-server-line text-yellow-500"></i>
                    Base URL
                </h3>
                <div class="bg-black/50 rounded-xl p-4 font-mono text-sm">
                    <code class="text-green-400">{{ url('/') }}/api/v1</code>
                </div>
            </div>
    
            <!-- Authentication -->
            <div class="bg-[#111114] border border-white/10 rounded-2xl p-6 mb-8">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="ri-key-line text-yellow-500"></i>
                    {{ app()->getLocale() === 'en' ? 'Authentication' : 'Autentikasi' }}
                </h3>
                <p class="text-gray-400 text-sm mb-4">
                    {{ app()->getLocale() === 'en' ? 'All API requests require authentication using API Key and API Secret in the request body or headers.' : 'Semua permintaan API memerlukan autentikasi menggunakan API Key dan API Secret di body request atau header.' }}
                </p>
                <div class="bg-black/50 rounded-xl p-4 font-mono text-sm overflow-x-auto">
    <pre class="text-gray-300"><span class="text-purple-400">POST</span> /api/v1/order
    <span class="text-gray-500">Content-Type:</span> <span class="text-green-400">application/json</span>
    
    {
        <span class="text-blue-400">"api_key"</span>: <span class="text-yellow-400">"your_api_key"</span>,
        <span class="text-blue-400">"api_secret"</span>: <span class="text-yellow-400">"your_api_secret"</span>,
        ...
    }</pre>
                </div>
            </div>
    
            <!-- Endpoints -->
            <div class="space-y-6">
                <h2 class="text-2xl font-bold text-white mb-6">
                    <i class="ri-route-line text-yellow-500 mr-2"></i>
                    Endpoints
                </h2>
    
                <!-- Get Services -->
                <div class="bg-[#111114] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="bg-emerald-500/10 border-b border-white/10 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded">GET</span>
                            <code class="text-white font-mono">/api/v1/services</code>
                        </div>
                        <p class="text-gray-400 text-sm mt-2">
                            {{ app()->getLocale() === 'en' ? 'Get list of all available services (games & prepaid products)' : 'Mendapatkan daftar semua layanan yang tersedia (game & produk prepaid)' }}
                        </p>
                    </div>
                    <div class="p-6">
                        <h4 class="text-white font-semibold mb-3">{{ app()->getLocale() === 'en' ? 'Parameters' : 'Parameter' }}</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-400 border-b border-white/10">
                                        <th class="pb-3 pr-4">{{ app()->getLocale() === 'en' ? 'Name' : 'Nama' }}</th>
                                        <th class="pb-3 pr-4">Type</th>
                                        <th class="pb-3 pr-4">{{ app()->getLocale() === 'en' ? 'Required' : 'Wajib' }}</th>
                                        <th class="pb-3">{{ app()->getLocale() === 'en' ? 'Description' : 'Deskripsi' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-300">
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">api_key</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">API Key Anda</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">type</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-gray-500">{{ app()->getLocale() === 'en' ? 'No' : 'Tidak' }}</span></td>
                                        <td class="py-3">Filter by type: <code class="text-yellow-400">game</code> or <code class="text-yellow-400">prepaid</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
    
                        <h4 class="text-white font-semibold mt-6 mb-3">{{ app()->getLocale() === 'en' ? 'Response Example' : 'Contoh Response' }}</h4>
                        <div class="bg-black/50 rounded-xl p-4 font-mono text-sm overflow-x-auto">
    <pre class="text-gray-300">{
        <span class="text-blue-400">"success"</span>: <span class="text-green-400">true</span>,
        <span class="text-blue-400">"data"</span>: [
            {
                <span class="text-blue-400">"code"</span>: <span class="text-yellow-400">"ML_WEEKLY"</span>,
                <span class="text-blue-400">"name"</span>: <span class="text-yellow-400">"Weekly Diamond Pass"</span>,
                <span class="text-blue-400">"game"</span>: <span class="text-yellow-400">"Mobile Legends"</span>,
                <span class="text-blue-400">"price"</span>: <span class="text-purple-400">28000</span>,
                <span class="text-blue-400">"status"</span>: <span class="text-yellow-400">"active"</span>
            },
            ...
        ]
    }</pre>
                        </div>
                    </div>
                </div>
    
                <!-- Create Order -->
                <div class="bg-[#111114] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="bg-blue-500/10 border-b border-white/10 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded">POST</span>
                            <code class="text-white font-mono">/api/v1/order</code>
                        </div>
                        <p class="text-gray-400 text-sm mt-2">
                            {{ app()->getLocale() === 'en' ? 'Create a new order for game top-up or prepaid products' : 'Membuat pesanan baru untuk top-up game atau produk prepaid' }}
                        </p>
                    </div>
                    <div class="p-6">
                        <h4 class="text-white font-semibold mb-3">{{ app()->getLocale() === 'en' ? 'Parameters' : 'Parameter' }}</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-400 border-b border-white/10">
                                        <th class="pb-3 pr-4">{{ app()->getLocale() === 'en' ? 'Name' : 'Nama' }}</th>
                                        <th class="pb-3 pr-4">Type</th>
                                        <th class="pb-3 pr-4">{{ app()->getLocale() === 'en' ? 'Required' : 'Wajib' }}</th>
                                        <th class="pb-3">{{ app()->getLocale() === 'en' ? 'Description' : 'Deskripsi' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-300">
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">api_key</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">API Key Anda</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">api_secret</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">API Secret Anda</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">service_code</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">{{ app()->getLocale() === 'en' ? 'Service code from services list' : 'Kode layanan dari daftar services' }}</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">target</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">{{ app()->getLocale() === 'en' ? 'User ID / Phone Number' : 'User ID / Nomor HP' }}</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">zone</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-gray-500">{{ app()->getLocale() === 'en' ? 'No' : 'Tidak' }}</span></td>
                                        <td class="py-3">{{ app()->getLocale() === 'en' ? 'Zone/Server ID (for games that require it)' : 'Zone/Server ID (untuk game yang memerlukan)' }}</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">ref_id</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-gray-500">{{ app()->getLocale() === 'en' ? 'No' : 'Tidak' }}</span></td>
                                        <td class="py-3">{{ app()->getLocale() === 'en' ? 'Your reference ID for tracking' : 'Reference ID Anda untuk tracking' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
    
                        <h4 class="text-white font-semibold mt-6 mb-3">{{ app()->getLocale() === 'en' ? 'Request Example' : 'Contoh Request' }}</h4>
                        <div class="bg-black/50 rounded-xl p-4 font-mono text-sm overflow-x-auto">
    <pre class="text-gray-300">{
        <span class="text-blue-400">"api_key"</span>: <span class="text-yellow-400">"your_api_key"</span>,
        <span class="text-blue-400">"api_secret"</span>: <span class="text-yellow-400">"your_api_secret"</span>,
        <span class="text-blue-400">"service_code"</span>: <span class="text-yellow-400">"ML_86"</span>,
        <span class="text-blue-400">"target"</span>: <span class="text-yellow-400">"123456789"</span>,
        <span class="text-blue-400">"zone"</span>: <span class="text-yellow-400">"1234"</span>,
        <span class="text-blue-400">"ref_id"</span>: <span class="text-yellow-400">"INV-001"</span>
    }</pre>
                        </div>
    
                        <h4 class="text-white font-semibold mt-6 mb-3">{{ app()->getLocale() === 'en' ? 'Response Example' : 'Contoh Response' }}</h4>
                        <div class="bg-black/50 rounded-xl p-4 font-mono text-sm overflow-x-auto">
    <pre class="text-gray-300">{
        <span class="text-blue-400">"success"</span>: <span class="text-green-400">true</span>,
        <span class="text-blue-400">"message"</span>: <span class="text-yellow-400">"Order created successfully"</span>,
        <span class="text-blue-400">"data"</span>: {
            <span class="text-blue-400">"trxid"</span>: <span class="text-yellow-400">"GAME-1234567890-1234"</span>,
            <span class="text-blue-400">"ref_id"</span>: <span class="text-yellow-400">"INV-001"</span>,
            <span class="text-blue-400">"service_code"</span>: <span class="text-yellow-400">"ML_86"</span>,
            <span class="text-blue-400">"target"</span>: <span class="text-yellow-400">"123456789"</span>,
            <span class="text-blue-400">"zone"</span>: <span class="text-yellow-400">"1234"</span>,
            <span class="text-blue-400">"price"</span>: <span class="text-purple-400">15000</span>,
            <span class="text-blue-400">"status"</span>: <span class="text-yellow-400">"processing"</span>
        }
    }</pre>
                        </div>
                    </div>
                </div>
    
                <!-- Check Status -->
                <div class="bg-[#111114] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="bg-purple-500/10 border-b border-white/10 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded">POST</span>
                            <code class="text-white font-mono">/api/v1/status</code>
                        </div>
                        <p class="text-gray-400 text-sm mt-2">
                            {{ app()->getLocale() === 'en' ? 'Check order status by transaction ID' : 'Cek status pesanan berdasarkan ID transaksi' }}
                        </p>
                    </div>
                    <div class="p-6">
                        <h4 class="text-white font-semibold mb-3">{{ app()->getLocale() === 'en' ? 'Parameters' : 'Parameter' }}</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-400 border-b border-white/10">
                                        <th class="pb-3 pr-4">{{ app()->getLocale() === 'en' ? 'Name' : 'Nama' }}</th>
                                        <th class="pb-3 pr-4">Type</th>
                                        <th class="pb-3 pr-4">{{ app()->getLocale() === 'en' ? 'Required' : 'Wajib' }}</th>
                                        <th class="pb-3">{{ app()->getLocale() === 'en' ? 'Description' : 'Deskripsi' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-300">
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">api_key</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">API Key Anda</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">api_secret</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">API Secret Anda</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">trxid</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">{{ app()->getLocale() === 'en' ? 'Transaction ID from order response' : 'ID Transaksi dari response order' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
    
                        <h4 class="text-white font-semibold mt-6 mb-3">{{ app()->getLocale() === 'en' ? 'Response Example' : 'Contoh Response' }}</h4>
                        <div class="bg-black/50 rounded-xl p-4 font-mono text-sm overflow-x-auto">
    <pre class="text-gray-300">{
        <span class="text-blue-400">"success"</span>: <span class="text-green-400">true</span>,
        <span class="text-blue-400">"data"</span>: {
            <span class="text-blue-400">"trxid"</span>: <span class="text-yellow-400">"GAME-1234567890-1234"</span>,
            <span class="text-blue-400">"ref_id"</span>: <span class="text-yellow-400">"INV-001"</span>,
            <span class="text-blue-400">"service_code"</span>: <span class="text-yellow-400">"ML_86"</span>,
            <span class="text-blue-400">"target"</span>: <span class="text-yellow-400">"123456789"</span>,
            <span class="text-blue-400">"zone"</span>: <span class="text-yellow-400">"1234"</span>,
            <span class="text-blue-400">"price"</span>: <span class="text-purple-400">15000</span>,
            <span class="text-blue-400">"status"</span>: <span class="text-yellow-400">"success"</span>,
            <span class="text-blue-400">"sn"</span>: <span class="text-yellow-400">"SN123456789"</span>,
            <span class="text-blue-400">"note"</span>: <span class="text-yellow-400">"Order completed"</span>
        }
    }</pre>
                        </div>
                    </div>
                </div>
    
                <!-- Check Balance -->
                <div class="bg-[#111114] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="bg-yellow-500/10 border-b border-white/10 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="bg-yellow-500 text-black text-xs font-bold px-3 py-1 rounded">POST</span>
                            <code class="text-white font-mono">/api/v1/balance</code>
                        </div>
                        <p class="text-gray-400 text-sm mt-2">
                            {{ app()->getLocale() === 'en' ? 'Check your API account balance' : 'Cek saldo akun API Anda' }}
                        </p>
                    </div>
                    <div class="p-6">
                        <h4 class="text-white font-semibold mb-3">{{ app()->getLocale() === 'en' ? 'Parameters' : 'Parameter' }}</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-400 border-b border-white/10">
                                        <th class="pb-3 pr-4">{{ app()->getLocale() === 'en' ? 'Name' : 'Nama' }}</th>
                                        <th class="pb-3 pr-4">Type</th>
                                        <th class="pb-3 pr-4">{{ app()->getLocale() === 'en' ? 'Required' : 'Wajib' }}</th>
                                        <th class="pb-3">{{ app()->getLocale() === 'en' ? 'Description' : 'Deskripsi' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-300">
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">api_key</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">API Key Anda</td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 pr-4"><code class="text-blue-400">api_secret</code></td>
                                        <td class="py-3 pr-4">string</td>
                                        <td class="py-3 pr-4"><span class="text-red-400">{{ app()->getLocale() === 'en' ? 'Yes' : 'Ya' }}</span></td>
                                        <td class="py-3">API Secret Anda</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
    
                        <h4 class="text-white font-semibold mt-6 mb-3">{{ app()->getLocale() === 'en' ? 'Response Example' : 'Contoh Response' }}</h4>
                        <div class="bg-black/50 rounded-xl p-4 font-mono text-sm overflow-x-auto">
    <pre class="text-gray-300">{
        <span class="text-blue-400">"success"</span>: <span class="text-green-400">true</span>,
        <span class="text-blue-400">"data"</span>: {
            <span class="text-blue-400">"balance"</span>: <span class="text-purple-400">1500000</span>,
            <span class="text-blue-400">"username"</span>: <span class="text-yellow-400">"your_username"</span>
        }
    }</pre>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Status Codes -->
            <div class="mt-12 bg-[#111114] border border-white/10 rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <i class="ri-information-line text-yellow-500"></i>
                    {{ app()->getLocale() === 'en' ? 'Order Status' : 'Status Pesanan' }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4">
                        <span class="bg-yellow-500 text-black text-xs font-bold px-3 py-1 rounded">waiting</span>
                        <span class="text-gray-300 text-sm">{{ app()->getLocale() === 'en' ? 'Waiting for payment' : 'Menunggu pembayaran' }}</span>
                    </div>
                    <div class="flex items-center gap-3 bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                        <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded">processing</span>
                        <span class="text-gray-300 text-sm">{{ app()->getLocale() === 'en' ? 'Order is being processed' : 'Pesanan sedang diproses' }}</span>
                    </div>
                    <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 rounded-xl p-4">
                        <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded">success</span>
                        <span class="text-gray-300 text-sm">{{ app()->getLocale() === 'en' ? 'Order completed successfully' : 'Pesanan berhasil' }}</span>
                    </div>
                    <div class="flex items-center gap-3 bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                        <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded">failed</span>
                        <span class="text-gray-300 text-sm">{{ app()->getLocale() === 'en' ? 'Order failed' : 'Pesanan gagal' }}</span>
                    </div>
                </div>
            </div>
    
            <!-- Error Codes -->
            <div class="mt-8 bg-[#111114] border border-white/10 rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <i class="ri-error-warning-line text-red-500"></i>
                    {{ app()->getLocale() === 'en' ? 'Error Codes' : 'Kode Error' }}
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-400 border-b border-white/10">
                                <th class="pb-3 pr-4">Code</th>
                                <th class="pb-3">{{ app()->getLocale() === 'en' ? 'Description' : 'Deskripsi' }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-300">
                            <tr class="border-b border-white/5">
                                <td class="py-3 pr-4"><code class="text-red-400">401</code></td>
                                <td class="py-3">{{ app()->getLocale() === 'en' ? 'Invalid API Key or Secret' : 'API Key atau Secret tidak valid' }}</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-3 pr-4"><code class="text-red-400">400</code></td>
                                <td class="py-3">{{ app()->getLocale() === 'en' ? 'Invalid request parameters' : 'Parameter request tidak valid' }}</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-3 pr-4"><code class="text-red-400">402</code></td>
                                <td class="py-3">{{ app()->getLocale() === 'en' ? 'Insufficient balance' : 'Saldo tidak mencukupi' }}</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-3 pr-4"><code class="text-red-400">404</code></td>
                                <td class="py-3">{{ app()->getLocale() === 'en' ? 'Service or transaction not found' : 'Layanan atau transaksi tidak ditemukan' }}</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-3 pr-4"><code class="text-red-400">500</code></td>
                                <td class="py-3">{{ app()->getLocale() === 'en' ? 'Internal server error' : 'Error server internal' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    
            <!-- Support -->
            <div class="mt-12 bg-linear-to-r from-yellow-500/10 to-orange-500/10 border border-yellow-500/30 rounded-2xl p-8 text-center">
                <i class="ri-customer-service-2-line text-5xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2">
                    {{ app()->getLocale() === 'en' ? 'Need Help?' : 'Butuh Bantuan?' }}
                </h3>
                <p class="text-gray-400 mb-6">
                    {{ app()->getLocale() === 'en' ? 'Our team is ready to help you integrate our API.' : 'Tim kami siap membantu Anda mengintegrasikan API kami.' }}
                </p>
                <a href="{{ localized_url('/contact-us') }}" class="inline-flex items-center gap-2 bg-yellow-500 text-black px-6 py-3 rounded-xl font-semibold hover:bg-yellow-400 transition-colors">
                    <i class="ri-mail-line"></i>
                    {{ app()->getLocale() === 'en' ? 'Contact Support' : 'Hubungi Support' }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
