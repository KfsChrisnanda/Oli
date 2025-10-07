<div>
    <div class="row gx-4">

        {{-- =================================================================
        // KOLOM KIRI: PENCARIAN & KERANJANG BELANJA
        // ================================================================= --}}
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    
                    {{-- Notifikasi --}}
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Input Pencarian Produk --}}
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               class="form-control" 
                               placeholder="Cari produk berdasarkan nama atau kode...">
                    </div>

                    {{-- Hasil Pencarian --}}
                    @if (strlen($search) >= 2 && count($products) > 0)
                        <ul class="list-group mb-3">
                            @foreach ($products as $product)
                                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                                    wire:click="addToCart({{ $product->id }})"
                                    style="cursor: pointer;">
                                    <div>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small>Stok: {{ $product->stock }} | Harga: Rp {{ number_format($product->sell_price) }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                    {{-- Keranjang Belanja --}}
                    <h5 class="mb-3">Keranjang Belanja</h5>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light position-sticky top-0">
                                <tr>
                                    <th>Produk</th>
                                    <th style="width: 120px;">Qty</th>
                                    <th>Subtotal</th>
                                    <th style="width: 50px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cart as $item)
                                    <tr>
                                        <td>{{ $item['name'] }} <br> <small>Rp {{ number_format($item['price']) }}</small></td>
                                        <td>
                                            <input type="number" 
                                                   wire:change="updateCartQuantity({{ $item['id'] }}, $event.target.value)"
                                                   class="form-control form-control-sm" 
                                                   value="{{ $item['quantity'] }}"
                                                   min="1">
                                        </td>
                                        <td>Rp {{ number_format($item['price'] * $item['quantity']) }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-danger" wire:click="removeFromCart({{ $item['id'] }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Keranjang masih kosong</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- =================================================================
        // KOLOM KANAN: TOTAL & PEMBAYARAN
        // ================================================================= --}}
        <div class="col-lg-5">
            <div class="card shadow-sm position-sticky top-0">
                <div class="card-body">
                    <h4 class="card-title">Total Belanja</h4>
                    <h1 class="display-5 fw-bold text-primary">Rp {{ number_format($total) }}</h1>
                    <hr>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" wire:model.live="paymentMethod">
                            <option value="Cash">Cash</option>
                            <option value="Debit">Debit</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amountPaid" class="form-label">Jumlah Dibayar</label>
                        <input type="number" wire:model.live="amountPaid" class="form-control @error('amountPaid') is-invalid @enderror" placeholder="0">
                        @error('amountPaid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="d-flex justify-content-between fs-5">
                        <span>Kembalian:</span>
                        <span class="fw-bold">Rp {{ number_format(floatval($amountPaid) > $total ? floatval($amountPaid) - $total : 0) }}</span>
                    </div>
                    <div class="d-grid mt-3">
                         <button class="btn btn-primary btn-lg" 
                                 wire:click="submitTransaction" 
                                 wire:loading.attr="disabled"
                                 {{ empty($cart) ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="submitTransaction">
                                <i class="bi bi-check-circle"></i> SELESAIKAN TRANSAKSI
                            </span>
                            <span wire:loading wire:target="submitTransaction">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>