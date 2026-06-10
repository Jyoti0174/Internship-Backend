<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $tickets = [
            ['title' => 'Cannot login to email',          'description' => 'User unable to login to company email.',       'status' => 'open',        'priority' => 'high'],
            ['title' => 'Laptop running slow',            'description' => 'Laptop is very slow, needs upgrade.',          'status' => 'in_progress', 'priority' => 'medium'],
            ['title' => 'Software installation required', 'description' => 'Need Adobe Acrobat installed.',                'status' => 'open',        'priority' => 'low'],
            ['title' => 'Printer not working',            'description' => 'Office printer not responding.',               'status' => 'open',        'priority' => 'medium'],
            ['title' => 'VPN connection issue',           'description' => 'Unable to connect to office VPN.',             'status' => 'open',        'priority' => 'high'],
            ['title' => 'Payroll system error',           'description' => 'Payroll software throwing error.',             'status' => 'open',        'priority' => 'high'],
            ['title' => 'New employee account setup',     'description' => 'Need user account for new joiner.',            'status' => 'open',        'priority' => 'medium'],
            ['title' => 'Excel file not opening',         'description' => 'Important Excel file showing corrupt error.',  'status' => 'in_progress', 'priority' => 'high'],
            ['title' => 'Internet connection drops',      'description' => 'Internet disconnecting every 30 minutes.',    'status' => 'open',        'priority' => 'medium'],
            ['title' => 'Data backup request',            'description' => 'Need backup before server migration.',         'status' => 'open',        'priority' => 'high'],
            ['title' => 'Monitor display flickering',     'description' => 'Monitor screen flickers every few minutes.',  'status' => 'open',        'priority' => 'low'],
            ['title' => 'Password reset request',         'description' => 'User forgot password, needs reset.',          'status' => 'in_progress', 'priority' => 'medium'],
            ['title' => 'Zoom not working',               'description' => 'Zoom crashes when joining meetings.',          'status' => 'in_progress', 'priority' => 'high'],
            ['title' => 'Access permission required',     'description' => 'Need access to shared finance folder.',       'status' => 'open',        'priority' => 'medium'],
            ['title' => 'Keyboard not working',           'description' => 'Few keys stopped working.',                   'status' => 'closed',      'priority' => 'low'],
            ['title' => 'Server downtime complaint',      'description' => 'Internal server was down for 2 hours.',       'status' => 'closed',      'priority' => 'high'],
            ['title' => 'Mobile device sync issue',       'description' => 'Work email not syncing on mobile.',           'status' => 'open',        'priority' => 'medium'],
            ['title' => 'Antivirus update needed',        'description' => 'Antivirus showing outdated definitions.',     'status' => 'open',        'priority' => 'low'],
            ['title' => 'New software license request',   'description' => 'Team needs 5 additional licenses.',           'status' => 'in_progress', 'priority' => 'medium'],
            ['title' => 'Database connection error',      'description' => 'Application showing connection timeout.',     'status' => 'open',        'priority' => 'high'],
        ];

        foreach ($tickets as $index => $ticket) {
            Ticket::create([
                'title'       => $ticket['title'],
                'description' => $ticket['description'],
                'status'      => $ticket['status'],
                'priority'    => $ticket['priority'],
                'user_id'     => $users[$index % $users->count()]->id,
            ]);
        }
    }
}