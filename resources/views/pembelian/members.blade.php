@extends('layouts.app')

@section('title', 'Members')

@section('content')
    <div class="container mx-auto p-6 flex space-x-6">
        
        <div class="w-1/2 border p-4 rounded-lg shadow ">
            <div class="w-full border p-4 rounded-lg shadow table-container">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-2">Nama Produk</th>
                            <th class="text-left p-2">QTY</th>
                            <th class="text-left p-2">Harga</th>
                            <th class="text-left p-2">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tbody>
                        @php $totalHarga = 0; @endphp
                        @foreach ($data['id_produk'] as $index => $id)
                            @php
                                $subtotal = $data['qty'][$index] * $data['harga'][$index];
                                $totalHarga += $subtotal;
                            @endphp
                            <tr class="border-b" data-id="{{ $id }}">
                                <td class="p-2">{{ $produk[$id] ?? 'Nama Tidak Ditemukan' }}</td>
                                <td class="p-2">{{ $data['qty'][$index] }}</td>
                                <td class="p-2">Rp. {{ number_format($data['harga'][$index], 0, ',', '.') }}</td>
                                <td class="p-2">Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        @foreach ($produks as $produk)
                            <input type="hidden" name="id_produk[]" value="{{ $produk->id }}">
                            <input type="hidden" name="harga[]" value="{{ $produk->harga_beli }}">
                            <input type="hidden" name="qty[]" value="{{ $produk->jumlah }}">
                        @endforeach

                        <input type="hidden" name="total_harga"
                            value="{{ $produks->sum(fn($p) => $p->harga_beli * $p->jumlah) }}">
                        <input type="hidden" name="total_bayar" id="total_bayar" value="{{ $data['total_bayar'] }}">
                        <input type="hidden" name="no_hp" id="hidden_no_hp">
                        <input type="hidden" name="member_status" id="hidden_member_status">
                    </tbody>
                </table>
                <div class="mt-4">
                    <p class="font-bold text-lg">
                        Total Harga
                        <span class="float-right">
                            Rp. {{ number_format($totalHarga, 0, ',', '.') }}
                        </span>
                    </p>
                </div>
            </div>


            
            <input type="hidden" name="id_member" id="id_member" value="{{ $member_id }}">

            <div class="w-1/2 border p-4 rounded-lg shadow">
                <div class="mb-4">
                    <label class="block font-bold mb-1">Nama Member (identitas)</label>
                    <input type="text" id="name" class="w-full p-2 border rounded-lg" value="{{ $name ?? '' }}"
                        placeholder="Masukkan nama">
                </div>
                <input type="hidden" id="no_hp" value="{{ $no_hp ?? '' }}"> 

                <div class="mb-4">
                    <label class="block font-bold mb-1">Poin</label>
                    <input type="text" id="point" class="w-full p-2 border rounded-lg"
                        style="background-color: #d1d5db;" value="{{ $point ?? '0' }}" readonly>
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" id="gunakanPoin" class="mr-2" {{ ($point ?? 0) == 0 ? 'disabled' : '' }}>
                    <span class="text-sm">Gunakan point
                        <span class="text-red-500">Poin tidak dapat digunakan pada pembelanjaan pertama.</span>
                    </span>
                </div>

                @if (isset($produks) && count($produks) > 0)
                    @foreach ($produks as $produk)
                        <input type="hidden" name="id_produk[]" value="{{ $produk->id }}">
                        <input type="hidden" name="harga[]" value="{{ $produk->harga_beli }}">
                        <input type="hidden" name="qty[]" value="{{ $produk->jumlah }}">
                    @endforeach
                @endif



                <button id="btnSubmit" class="text-white px-4 py-2 rounded-lg" style="background-color: #007bff;">
                    Selanjutnya
                </button>
            </div>

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const gunakanPoinCheckbox = document.getElementById('gunakanPoin');
                const poinInput = document.getElementById('point');
                const noHpInput = document.getElementById('no_hp');
                const totalHargaElement = document.querySelector('.font-bold.text-lg span');
                const btnSubmit = document.getElementById('btnSubmit');
                const idMemberInput = document.getElementById('id_member');
                const idMember = idMemberInput ? idMemberInput.value : null;
                const rawValue = document.getElementById('total_bayar').value;
                const totalBayar = Number(
                    rawValue.replace(/[^0-9]/g, '') 
                );




                let totalHarga = parseInt(totalHargaElement.textContent.replace(/Rp\.|\./g, '').trim()) || 0;
                let point = parseInt(poinInput.value) || 0;

                
                const numberWithCommas = (x) => x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');


                
                noHpInput.addEventListener('blur', () => {
                    const noHp = noHpInput.value.trim();
                    if (noHp) {
                        cekMember(noHp);
                    }
                });

                
                btnSubmit.addEventListener('click', () => {
                    const name = document.getElementById('name').value.trim();
                    const noHp = noHpInput.value.trim();

                    if (!name || !noHp) {
                        alert('Nama Member dan Nomor HP harus diisi!');
                        return;
                    }

                    
                    const poinYangDigunakan = 150; 
                    if (gunakanPoinCheckbox.checked) {
                        if (point >= poinYangDigunakan && totalHarga > 0) {
                            
                            totalHarga -= poinYangDigunakan;
                            point -= poinYangDigunakan;
                        } else {
                            alert('Poin tidak mencukupi atau total harga tidak valid.');
                            return;
                        }
                    }

                    
                    totalHargaElement.textContent = `Rp. ${numberWithCommas(totalHarga)}`;
                    poinInput.value = point;

                    
                    let idProduk = [];
                    let qty = [];
                    let harga = [];

                    document.querySelectorAll("table tbody tr").forEach(row => {
                        idProduk.push(row.getAttribute("data-id"));
                        qty.push(parseInt(row.children[1].textContent.trim())); 
                        harga.push(parseInt(row.children[2].textContent.replace(/Rp\.|\./g, '')
                    .trim())); 
                    });

                    
                    let poinDidapat = totalBayar > 100000 ? 100 : 0;

                    const dataToSend = {
                        id_member: idMember,
                        name,
                        no_hp: noHp,
                        gunakan_poin: gunakanPoinCheckbox.checked ? 1 : 0,
                        id_produk: idProduk,
                        qty: qty,
                        harga: harga,
                        total_bayar: totalBayar,
                        poin_didapat: poinDidapat,
                    };

                    console.log("Data yang dikirim:", dataToSend);

                    fetch('/member/store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify(dataToSend),
                        })
                        .then(async response => {
                            const text = await response.text(); 
                            console.log("Raw response text:", text);

                            let data;
                            try {
                                data = JSON.parse(text);
                            } catch (e) {
                                throw new Error('Response bukan JSON');
                            }

                            if (data.success) {
                                alert('Pembelian berhasil');
                                const queryString = new URLSearchParams(dataToSend).toString();
                                window.location.href = `/struk?${queryString}`;
                            } else {
                                alert(`Gagal: ${data.message}`);
                            }
                        })
                        .catch(error => {
                            console.error('Terjadi kesalahan:', error);
                            alert('Terjadi kesalahan saat menyimpan pembelian.');
                        });

                });

            });
        </script>

    @endsection
