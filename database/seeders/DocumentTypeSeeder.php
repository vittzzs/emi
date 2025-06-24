<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            ['code' => '0', 'name' => 'OTRO DOCUMENTO (COD 0)', 'description' => 'DOC.TRIB.NO.DOM.SIN.RUC'],
            ['code' => '1', 'name' => 'D.N.I.', 'description' => 'DOC. NACIONAL DE IDENTIDAD'],
            ['code' => '4', 'name' => 'CARNET DE EXTRANJERIA (COD 4)', 'description' => 'CARNET DE EXTRANJERIA'],
            ['code' => '6', 'name' => 'R.U.C.', 'description' => 'REG. UNICO DE CONTRIBUYENTES'],
            ['code' => '7', 'name' => 'PASAPORTE', 'description' => 'PASAPORTE'],
            ['code' => 'A', 'name' => 'CED. DIPLOMATICA DE IDENTIDAD', 'description' => 'CED. DIPLOMATICA DE IDENTIDAD'],
            ['code' => 'B', 'name' => 'OC.IDENT.PAIS.RESIDENCIA-NO.D', 'description' => 'DOC.IDENT.PAIS.RESIDENCIA-NO.D'],
            ['code' => 'C', 'name' => 'TIN', 'description' => 'Tax Identification Number - TIN – Doc Trib PP.NND'],
            ['code' => 'D', 'name' => 'IN', 'description' => 'Identification Number - IN – Doc Trib PP. JJ'],
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::create($documentType);
        }
    }
}
