<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanySetting::create([
            'company_name' => 'ANKomputer',
            'address' => "Jl. AMD RT.18 Gang Hj.Kadir Malinau Kota\nKabupaten Malinau Provinsi Kalimantan Utara, 77554",
            'phone' => '+6285251990910',
            'email' => 'ankomputermalinau@gmail.com',
            'website' => 'https://siplah.tokoladang.co.id/official-store/ankomputer.70185',
            'bank_name' => 'Kaltimtara',
            'bank_account_number' => '0127167460',
            'bank_account_name' => 'Azisah Nurul Khaerani',
            'invoice_prefix' => 'INV01-ANKompl/',
            'terms_conditions' => "- Harap melunasi tagihan sebelum tanggal jatuh tempo\n- Harga sudah termasuk semua pajak yang berlaku\n- Barang tetap menjadi milik penjual sampai pembayaran penuh diterima.",
        ]);
    }
}
