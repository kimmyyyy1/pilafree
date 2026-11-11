<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin / Attending Physician
        User::firstOrCreate(
            ['email' => 'admin@pilafree.ph'],
            [
                'name' => 'Dr. Josefa Rizal, MD',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Public Health Clinic Departments (Professional Filipino Descriptions)
        $opd = Department::create([
            'name' => 'Pangkalahatang Konsultasyon (General OPD)',
            'code' => 'OPD',
            'room_number' => 'Silid-Konsultasyon 1',
            'description' => 'Pangkalahatang pagsusuri para sa lagnat, ubo, altapresyon, at regular na medikal na konsultasyon.',
            'daily_quota' => 60,
            'avg_consultation_minutes' => 10,
            'is_active' => true,
        ]);

        $peds = Department::create([
            'name' => 'Kalusugan ng Bata at Bakuna (Pediatrics)',
            'code' => 'PEDS',
            'room_number' => 'Silid-Konsultasyon 2',
            'description' => 'Pagsusuri sa kalusugan ng mga sanggol at bata, regular na pagbabakuna, at nutrisyon.',
            'daily_quota' => 40,
            'avg_consultation_minutes' => 15,
            'is_active' => true,
        ]);

        $dent = Department::create([
            'name' => 'Serbisyong Dental (Dental Clinic)',
            'code' => 'DENT',
            'room_number' => 'Silid-Dental 3',
            'description' => 'Pagsusuri ng ngipin, pagbunot, oral prophylaxis, at pangangalaga sa kalusugan ng bibig.',
            'daily_quota' => 25,
            'avg_consultation_minutes' => 20,
            'is_active' => true,
        ]);

        $obgyn = Department::create([
            'name' => 'Pangangalaga sa Ina at Nagdadalang-tao (Maternal Care)',
            'code' => 'OBGYN',
            'room_number' => 'Silid-Maternal 4',
            'description' => 'Prenatal check-up, gabay sa pagbubuntis, at postpartum follow-up para sa mga ina.',
            'daily_quota' => 35,
            'avg_consultation_minutes' => 15,
            'is_active' => true,
        ]);

        // 3. Libreng Gamot (Essential Public Medicine Inventory)
        $medicines = [
            [
                'generic_name' => 'Paracetamol',
                'brand_name' => 'Biogesic / Generics',
                'dosage' => '500mg',
                'category' => 'Lagnat at Kirot',
                'unit' => 'tableta',
                'stock_quantity' => 850,
                'low_stock_threshold' => 100,
                'instructions' => '1 tableta bawat 4 hanggang 6 na oras para sa lagnat o pananakit ng katawan.',
                'is_available' => true,
            ],
            [
                'generic_name' => 'Amlodipine Besilate',
                'brand_name' => 'Norvasc / Generics',
                'dosage' => '5mg',
                'category' => 'Altapresyon (Maintenance)',
                'unit' => 'tableta',
                'stock_quantity' => 340,
                'low_stock_threshold' => 80,
                'instructions' => '1 tableta isang beses sa isang araw tuwing umaga.',
                'is_available' => true,
            ],
            [
                'generic_name' => 'Metformin Hydrochloride',
                'brand_name' => 'Glucophage / Generics',
                'dosage' => '500mg',
                'category' => 'Diabetes (Maintenance)',
                'unit' => 'tableta',
                'stock_quantity' => 290,
                'low_stock_threshold' => 80,
                'instructions' => '1 tableta kasabay ng pagkain o ayon sa payo ng doktor.',
                'is_available' => true,
            ],
            [
                'generic_name' => 'Losartan Potassium',
                'brand_name' => 'Cozaar / Generics',
                'dosage' => '50mg',
                'category' => 'Altapresyon (Maintenance)',
                'unit' => 'tableta',
                'stock_quantity' => 210,
                'low_stock_threshold' => 50,
                'instructions' => '1 tableta isang beses sa isang araw.',
                'is_available' => true,
            ],
            [
                'generic_name' => 'Amoxicillin Trihydrate',
                'brand_name' => 'Generics',
                'dosage' => '500mg',
                'category' => 'Antibiotiko',
                'unit' => 'kapsula',
                'stock_quantity' => 35,
                'low_stock_threshold' => 60,
                'instructions' => '1 kapsula tatlong beses sa isang araw pagkatapos kumain sa loob ng 7 araw.',
                'is_available' => true,
            ],
            [
                'generic_name' => 'Salbutamol Sulfate',
                'brand_name' => 'Ventolin / Generics',
                'dosage' => '2mg/5ml Syrup',
                'category' => 'Hika at Paghinga',
                'unit' => 'bote',
                'stock_quantity' => 12,
                'low_stock_threshold' => 20,
                'instructions' => 'Inumin ayon sa sukat na itinakda ng doktor para sa hirap sa paghinga.',
                'is_available' => true,
            ],
            [
                'generic_name' => 'Oral Rehydration Salts (ORS)',
                'brand_name' => 'Hydrite / DOH ORS',
                'dosage' => '20.5g Sachet / 1L Tubig',
                'category' => 'Rehydration',
                'unit' => 'sachet',
                'stock_quantity' => 400,
                'low_stock_threshold' => 50,
                'instructions' => 'Tunawin ang 1 sachet sa 1 litrong malinis na inuming tubig.',
                'is_available' => true,
            ],
            [
                'generic_name' => 'Ascorbic Acid + Zinc',
                'brand_name' => 'Vitamin C Generics',
                'dosage' => '500mg',
                'category' => 'Bitamina at Suplemento',
                'unit' => 'kapsula',
                'stock_quantity' => 620,
                'low_stock_threshold' => 100,
                'instructions' => '1 kapsula araw-araw pagkatapos kumain bilang pampalakas ng resistensya.',
                'is_available' => true,
            ],
        ];

        foreach ($medicines as $med) {
            Medicine::create($med);
        }

        // 4. Initial Sample Patients
        $p1 = Patient::create([
            'full_name' => 'Remedios M. Santos',
            'phone_number' => '09171234567',
            'is_priority' => true,
            'priority_type' => 'senior',
            'gender' => 'Babae',
            'age' => 74,
            'notes' => 'Pagsusuri ng presyon ng dugo at refill ng gamot sa altapresyon.',
        ]);

        $p2 = Patient::create([
            'full_name' => 'Juan S. Dela Cruz Jr.',
            'phone_number' => '09189876543',
            'is_priority' => false,
            'priority_type' => null,
            'gender' => 'Lalaki',
            'age' => 28,
            'notes' => 'Tatlong araw nang may lagnat at ubo.',
        ]);

        $p3 = Patient::create([
            'full_name' => 'Maria Clara L. Garcia',
            'phone_number' => '09205551234',
            'is_priority' => true,
            'priority_type' => 'pregnant',
            'gender' => 'Babae',
            'age' => 26,
            'notes' => 'Ika-anim na buwang prenatal check-up.',
        ]);

        $p4 = Patient::create([
            'full_name' => 'Mario R. Ramos',
            'phone_number' => '09194443322',
            'is_priority' => true,
            'priority_type' => 'senior',
            'gender' => 'Lalaki',
            'age' => 69,
            'notes' => 'Follow-up check-up para sa altapresyon.',
        ]);

        $p5 = Patient::create([
            'full_name' => 'Carlo D. Mendoza',
            'phone_number' => '09278889900',
            'is_priority' => false,
            'priority_type' => null,
            'gender' => 'Lalaki',
            'age' => 34,
            'notes' => 'Pananakit ng bagang at pagsusuri ng ngipin.',
        ]);

        // 5. Initial Active Queue Tickets for Today
        Ticket::create([
            'ticket_number' => 'OPD-P01',
            'patient_id' => $p1->id,
            'department_id' => $opd->id,
            'status' => 'serving',
            'queue_sequence' => 1,
            'is_priority' => true,
            'assigned_room' => 'Silid-Konsultasyon 1',
            'channel' => 'kiosk',
            'called_at' => now()->subMinutes(8),
            'serving_at' => now()->subMinutes(6),
            'remarks' => 'Pagsusuri ng presyon ng dugo at refill ng gamot sa altapresyon.',
        ]);

        Ticket::create([
            'ticket_number' => 'OPD-P02',
            'patient_id' => $p4->id,
            'department_id' => $opd->id,
            'status' => 'waiting',
            'queue_sequence' => 2,
            'is_priority' => true,
            'assigned_room' => 'Silid-Konsultasyon 1',
            'channel' => 'sms',
            'remarks' => 'Follow-up check-up para sa altapresyon.',
        ]);

        Ticket::create([
            'ticket_number' => 'OPD-001',
            'patient_id' => $p2->id,
            'department_id' => $opd->id,
            'status' => 'waiting',
            'queue_sequence' => 3,
            'is_priority' => false,
            'assigned_room' => 'Silid-Konsultasyon 1',
            'channel' => 'web',
            'remarks' => 'Tatlong araw nang may lagnat at ubo.',
        ]);

        Ticket::create([
            'ticket_number' => 'OBGYN-P01',
            'patient_id' => $p3->id,
            'department_id' => $obgyn->id,
            'status' => 'serving',
            'queue_sequence' => 1,
            'is_priority' => true,
            'assigned_room' => 'Silid-Maternal 4',
            'channel' => 'web',
            'called_at' => now()->subMinutes(12),
            'serving_at' => now()->subMinutes(10),
            'remarks' => 'Ika-anim na buwang prenatal check-up.',
        ]);

        Ticket::create([
            'ticket_number' => 'DENT-001',
            'patient_id' => $p5->id,
            'department_id' => $dent->id,
            'status' => 'waiting',
            'queue_sequence' => 1,
            'is_priority' => false,
            'assigned_room' => 'Silid-Dental 3',
            'channel' => 'kiosk',
            'remarks' => 'Pananakit ng bagang at pagsusuri ng ngipin.',
        ]);
    }
}
