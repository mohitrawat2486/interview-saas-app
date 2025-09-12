<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrNew(['email'=>'admin@example.com']);
        $admin->name = 'Admin';
        $admin->password = Hash::make('password');
        $admin->role = 'admin';
        $admin->save();

        // Reviewer
        $rev = User::firstOrNew(['email'=>'reviewer@example.com']);
        $rev->name = 'Reviewer';
        $rev->password = Hash::make('password');
        $rev->role = 'reviewer';
        $rev->save();

        // Candidate
        $cand = User::firstOrNew(['email'=>'candidate@example.com']);
        $cand->name = 'Candidate';
        $cand->password = Hash::make('password');
        $cand->role = 'candidate';
        $cand->save();
    }
}
