<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $transactions = Transaction::with([
            'user', 
            'details.product' => function($query) {
                $query->withTrashed();
            }
        ])->latest()->get();
        
        $exportData = collect();
        
        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $index => $detail) {
                $exportData->push((object)[
                    'transaction' => $transaction,
                    'detail' => $detail,
                    'is_first_row' => $index === 0
                ]);
            }
        }
        
        return $exportData;
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Tanggal',
            'Kasir',
            'Nama Produk',
            'Kode Produk',
            'Qty',
            'Harga Satuan',
            'Subtotal',
            'Metode Pembayaran',
            'Total Transaksi',
            'Jumlah Dibayar',
            'Kembalian'
        ];
    }

    public function map($row): array
    {
        return [
            $row->is_first_row ? $row->transaction->invoice_number : '',
            $row->is_first_row ? $row->transaction->created_at->format('d-m-Y H:i') : '',
            $row->is_first_row ? $row->transaction->user->name : '',
            $row->detail->product ? $row->detail->product->name : 'Produk Dihapus',
            $row->detail->product ? $row->detail->product->code : '-',
            $row->detail->quantity,
            'Rp ' . number_format($row->detail->price),
            'Rp ' . number_format($row->detail->quantity * $row->detail->price),
            $row->is_first_row ? $row->transaction->payment_method : '',
            $row->is_first_row ? 'Rp ' . number_format($row->transaction->total_amount) : '',
            $row->is_first_row ? 'Rp ' . number_format($row->transaction->paid_amount) : '',
            $row->is_first_row ? 'Rp ' . number_format($row->transaction->change_amount) : '',
        ];
    }
}