<?php

namespace Database\Seeders;

use App\Models\Fund;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //Add role column if it doesn't exist yet
        if (! Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE users ADD COLUMN role VARCHAR(255) NOT NULL DEFAULT 'treasurer' AFTER password");
        }

        //Users
        User::create([
            'name'     => 'SSG Admin',
            'email'    => 'admin@ssg.edu.ph',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Angela Ann Lenteria',
            'email'    => 'treasurer@ssg.edu.ph',
            'password' => Hash::make('password'),
            'role'     => 'treasurer',
        ]);

        //Funds
        $generalFund = Fund::create([
            'name'             => 'General Fund',
            'description'      => 'Main operating fund for all SSG activities and operations.',
            'allocated_amount' => 50000.00,
            'current_balance'  => 50000.00,
            'school_year'      => '2024-2025',
            'semester'         => '1st Semester',
            'status'           => 'active',
        ]);

        Fund::create([
            'name'             => 'Cultural & Arts Fund',
            'description'      => 'Budget allocated for cultural events and art programs.',
            'allocated_amount' => 20000.00,
            'current_balance'  => 20000.00,
            'school_year'      => '2024-2025',
            'semester'         => '1st Semester',
            'status'           => 'active',
        ]);

        Fund::create([
            'name'             => 'Sports Development Fund',
            'description'      => 'Funding for inter-school sports events and training.',
            'allocated_amount' => 15000.00,
            'current_balance'  => 15000.00,
            'school_year'      => '2024-2025',
            'semester'         => '1st Semester',
            'status'           => 'active',
        ]);

        //Members
        Member::create([
            'student_id' => '2024-00001',
            'name'       => 'Juan dela Cruz',
            'email'      => 'juan.delacruz@student.edu.ph',
            'course'     => 'BS Computer Science',
            'year_level' => '3rd Year',
            'section'    => 'A',
            'position'   => 'SSG President',
            'is_active'  => true,
        ]);

        Member::create([
            'student_id' => '2024-00002',
            'name'       => 'Ana Santos',
            'email'      => 'ana.santos@student.edu.ph',
            'course'     => 'BS Information Technology',
            'year_level' => '2nd Year',
            'section'    => 'B',
            'position'   => 'SSG Treasurer',
            'is_active'  => true,
        ]);

        Member::create([
            'student_id' => '2024-00003',
            'name'       => 'Carlo Reyes',
            'email'      => 'carlo.reyes@student.edu.ph',
            'course'     => 'BS Accountancy',
            'year_level' => '1st Year',
            'section'    => 'A',
            'position'   => null,
            'is_active'  => true,
        ]);
    }
}
