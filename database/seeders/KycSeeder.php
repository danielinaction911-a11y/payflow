<?php

namespace Database\Seeders;

use App\Models\Kyc;
use Illuminate\Database\Seeder;

class KycSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'type' => "Driver's License",
                'status' => true,
                'required_fields' => [
                    ['name' => 'full_name', 'label' => 'Full Legal Name', 'type' => 'text', 'required' => true],
                    ['name' => 'license_number', 'label' => 'License Number', 'type' => 'text', 'required' => true],
                    ['name' => 'front_image', 'label' => 'Front of License', 'type' => 'file', 'required' => true],
                    ['name' => 'back_image', 'label' => 'Back of License', 'type' => 'file', 'required' => true],
                ],
            ],
            [
                'type' => 'National ID Card',
                'status' => true,
                'required_fields' => [
                    ['name' => 'full_name', 'label' => 'Full Legal Name', 'type' => 'text', 'required' => true],
                    ['name' => 'id_number', 'label' => 'ID Number', 'type' => 'text', 'required' => true],
                    ['name' => 'front_image', 'label' => 'Front of ID Card', 'type' => 'file', 'required' => true],
                    ['name' => 'back_image', 'label' => 'Back of ID Card', 'type' => 'file', 'required' => true],
                ],
            ],
            [
                'type' => 'Passport',
                'status' => true,
                'required_fields' => [
                    ['name' => 'full_name', 'label' => 'Full Legal Name', 'type' => 'text', 'required' => true],
                    ['name' => 'passport_number', 'label' => 'Passport Number', 'type' => 'text', 'required' => true],
                    ['name' => 'expiry_date', 'label' => 'Expiry Date', 'type' => 'text', 'required' => true],
                    ['name' => 'passport_image', 'label' => 'Passport Photo Page', 'type' => 'file', 'required' => true],
                ],
            ],
            [
                'type' => 'Proof of Address',
                'status' => true,
                'required_fields' => [
                    ['name' => 'document_type', 'label' => 'Document Type (utility bill, bank statement)', 'type' => 'text', 'required' => true],
                    ['name' => 'document_image', 'label' => 'Upload Document', 'type' => 'file', 'required' => true],
                ],
            ],
        ];

        foreach ($types as $type) {
            Kyc::updateOrCreate(['type' => $type['type']], $type);
        }
    }
}