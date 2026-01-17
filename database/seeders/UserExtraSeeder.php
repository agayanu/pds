<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => 'Aman Abdurahman, S.Pd.I','username' => 'aman','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Akhmad Rifai, S.Pd.I','username' => 'arifai','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Purwo Nugroho, S.Pd.Gr','username' => 'purwonugroho','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'M. Ardi Budiawan, S.Pd.','username' => 'mardhi','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Fia Fianti, M.Pd.','username' => 'ffianti','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Fery Yantini, M.Pd.','username' => 'fyantini','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Dwi Pratiwi, S.Pd.Gr','username' => 'dpratiwi','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Kemala Saras R, S.Pd.','username' => 'ksaras','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Rizky Nurul Hidayah, S.Pd','username' => 'rnurulhidayah','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Yusi Rahma Wati, S.Pd.Gr','username' => 'yrakhmahwati','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Giri Indah Sari, S.Pd.Gr.','username' => 'gindah','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Annisa Marheliyana, S.Pd','username' => 'amarheliyana','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Rizky Lingga Ayuningtyas, SH., MH, Gr','username' => 'rlingga','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
['name' => 'Puti, S.Sos.','username' => 'puti','role' => '0','password' => Hash::make('123456'),'created_at' => now()],
        ]);
    }
}
