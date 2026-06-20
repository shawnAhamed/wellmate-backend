<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@wellmate.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Verified doctors
        $verifiedDoctors = [
            [
                'name' => 'Dr. Elena Kovač',
                'email' => 'elena.kovac@wellmate.test',
                'specialization' => 'Adolescent Psychiatrist',
                'license' => 'LIC-2001',
                'bio' => 'Adolescent psychiatrist focused on teen anxiety, self-esteem, and body image — helping young people understand what they\'re feeling without judgment.',
                'verifiedDaysAgo' => 40,
            ],
            [
                'name' => 'Dr. Johan Berg',
                'email' => 'johan.berg@wellmate.test',
                'specialization' => 'Clinical Psychologist',
                'license' => 'LIC-2002',
                'bio' => 'Clinical psychologist specializing in grief, separation, and life-transition counseling, with a particular focus on divorce recovery.',
                'verifiedDaysAgo' => 35,
            ],
            [
                'name' => 'Dr. Marta Nováková',
                'email' => 'marta.novakova@wellmate.test',
                'specialization' => 'OB-GYN',
                'license' => 'LIC-2003',
                'bio' => 'Obstetrician-gynecologist dedicated to clear, myth-busting pregnancy guidance from the first trimester through delivery.',
                'verifiedDaysAgo' => 45,
            ],
        ];

        foreach ($verifiedDoctors as $d) {
            $user = User::create([
                'name' => $d['name'],
                'email' => $d['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('doctor');

            Doctor::create([
                'user_id' => $user->id,
                'specialization' => $d['specialization'],
                'bio' => $d['bio'],
                'license_number' => $d['license'],
                'license_number_hash' => Doctor::hashLicenseNumber($d['license']),
                'is_verified' => true,
                'verified_at' => now()->subDays($d['verifiedDaysAgo']),
            ]);
        }

        // Pending (unverified) doctor — for demoing the admin verification flow live
        $pendingUser = User::create([
            'name' => 'Dr. Aiden Cole',
            'email' => 'aiden.cole@wellmate.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $pendingUser->assignRole('doctor');

        Doctor::create([
            'user_id' => $pendingUser->id,
            'specialization' => 'Dermatologist',
            'bio' => 'Newly registered dermatologist awaiting verification.',
            'license_number' => 'LIC-2004',
            'license_number_hash' => Doctor::hashLicenseNumber('LIC-2004'),
            'is_verified' => false,
        ]);

        // Patient (anonymous-asker) accounts matching the seeded Q&A content
        $users = [
            ['name' => 'Nusrat Jahan', 'email' => 'teen16@wellmate.test', 'handle' => 'QuietMornings16'],
            ['name' => 'Rakib Hasan', 'email' => 'teen15@wellmate.test', 'handle' => 'StillGrowing15'],
            ['name' => 'Shirin Akhter', 'email' => 'divorce41@wellmate.test', 'handle' => 'NewBeginnings41'],
            ['name' => 'Faisal Rahman', 'email' => 'divorce35@wellmate.test', 'handle' => 'FinallyFree35'],
            ['name' => 'Tasnim Khan', 'email' => 'expecting@wellmate.test', 'handle' => 'ExpectingJoy'],
        ];

        foreach ($users as $u) {
            $user = User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password'),
                'anonymous_handle' => $u['handle'],
                'email_verified_at' => now(),
            ]);
            $user->assignRole('user');
        }
    }
}
