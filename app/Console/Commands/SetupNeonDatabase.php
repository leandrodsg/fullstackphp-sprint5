<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use Exception;

class SetupNeonDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neon:setup {--test-only : Only test connection without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup and configure Neon PostgreSQL database for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Neon PostgreSQL database setup...');
        
        // Setup temporary connection to Neon
        $this->setupNeonConnection();
        
        // Test connection
        if (!$this->testConnection()) {
            $this->error('Failed to connect to Neon. Please verify credentials.');
            return 1;
        }
        
        if ($this->option('test-only')) {
            $this->info('Connection test completed successfully!');
            return 0;
        }
        
        // Check and run migrations
        $this->runMigrations();
        
        // Create test users
        $this->createTestUsers();
        
        // Verify data
        $this->verifyData();
        
        $this->info('Neon database setup completed successfully!');
        return 0;
    }
    
    private function setupNeonConnection()
    {
        $this->info('Configuring Neon connection...');
        
        Config::set('database.connections.neon', [
            'driver' => 'pgsql',
            'host' => 'ep-spring-boat-ag8g7v2p-pooler.c-2.eu-central-1.aws.neon.tech',
            'port' => '5432',
            'database' => 'neondb',
            'username' => 'neondb_owner',
            'password' => 'npg_fuMPB4IvaN6G',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]);
        
        Config::set('database.default', 'neon');
    }
    
    private function testConnection(): bool
    {
        $this->info('Testing Neon connection...');
        
        try {
            DB::connection('neon')->getPdo();
            $result = DB::connection('neon')->select('SELECT version()');
            $this->info('Connection established: ' . $result[0]->version);
            return true;
        } catch (Exception $e) {
            $this->error('Connection error: ' . $e->getMessage());
            return false;
        }
    }
    
    private function runMigrations()
    {
        $this->info('Checking migrations...');
        
        try {
            // Check if migrations table exists
            $tables = DB::connection('neon')->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            $tableNames = array_column($tables, 'tablename');
            
            if (!in_array('migrations', $tableNames)) {
                $this->info('Running migrations...');
                $this->call('migrate', ['--database' => 'neon', '--force' => true]);
            } else {
                $this->info('Migrations already executed');
            }
        } catch (Exception $e) {
            $this->error('Migration error: ' . $e->getMessage());
        }
    }
    
    private function createTestUsers()
    {
        $this->info('Creating test users...');
        
        try {
            // Check if users already exist
            $userCount = DB::connection('neon')->table('users')->count();
            
            if ($userCount == 0) {
                $this->info('Creating users...');
                
                // Admin User
                DB::connection('neon')->table('users')->insert([
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'role' => 'admin',
                    'password' => bcrypt('AdminPassword@123'),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Regular User
                DB::connection('neon')->table('users')->insert([
                    'name' => 'Test User',
                    'email' => 'user@example.com',
                    'role' => 'user',
                    'password' => bcrypt('UserPassword@123'),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->info('Users created successfully');
            } else {
                $this->info("Users already exist ($userCount found)");
            }
        } catch (Exception $e) {
            $this->error('User creation error: ' . $e->getMessage());
        }
    }
    
    private function verifyData()
    {
        $this->info('Verifying database setup...');
        
        try {
            // Check tables
            $tables = DB::connection('neon')->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            $this->info('Tables found: ' . count($tables));
            
            // Check users
            $userCount = DB::connection('neon')->table('users')->count();
            $this->info("Users in database: $userCount");
            
            if ($userCount > 0) {
                $users = DB::connection('neon')->table('users')->select('name', 'email', 'role')->get();
                foreach ($users as $user) {
                    $this->info("- {$user->name} ({$user->email}) - Role: {$user->role}");
                }
            }
            
            $this->info('Setup verification completed');
        } catch (Exception $e) {
            $this->error('Verification error: ' . $e->getMessage());
        }
    }
}
