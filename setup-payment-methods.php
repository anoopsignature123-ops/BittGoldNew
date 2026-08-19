<?php
/**
 * Payment Method Database Setup Script
 * Run this if migrations fail due to environment issues
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$kernel->handle($request);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "=== Payment Method Database Setup ===\n\n";

    // Check if payment_methods table exists
    $paymentMethodsExists = Schema::hasTable('payment_methods');
    echo "[1] Payment Methods Table: " . ($paymentMethodsExists ? "✓ EXISTS" : "✗ MISSING") . "\n";

    if (!$paymentMethodsExists) {
        echo "    Creating payment_methods table...\n";
        DB::statement("
            CREATE TABLE IF NOT EXISTS payment_methods (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                type ENUM('qr', 'upi', 'bank') NOT NULL,
                title VARCHAR(255) NOT NULL,
                qr_image VARCHAR(255) NULL,
                upi_id VARCHAR(255) NULL,
                bank_name VARCHAR(255) NULL,
                account_holder_name VARCHAR(255) NULL,
                account_number VARCHAR(255) NULL,
                ifsc_code VARCHAR(255) NULL,
                branch_name VARCHAR(255) NULL,
                notes LONGTEXT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                sort_order INT UNSIGNED DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_type_active (type, is_active),
                INDEX idx_sort_order (sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "    ✓ Created successfully!\n";
    }

    // Check deposits table columns
    $hasPaymentMethod = Schema::hasColumn('deposits', 'payment_method');
    $hasPaymentDetails = Schema::hasColumn('deposits', 'payment_details');
    
    echo "[2] Deposits Table - payment_method column: " . ($hasPaymentMethod ? "✓ EXISTS" : "✗ MISSING") . "\n";
    echo "[3] Deposits Table - payment_details column: " . ($hasPaymentDetails ? "✓ EXISTS" : "✗ MISSING") . "\n";

    if (!$hasPaymentMethod || !$hasPaymentDetails) {
        echo "    Adding missing columns to deposits table...\n";
        
        if (!$hasPaymentMethod) {
            DB::statement("ALTER TABLE deposits ADD COLUMN payment_method VARCHAR(255) NULL AFTER reference_no");
            echo "    ✓ Added payment_method column\n";
        }
        
        if (!$hasPaymentDetails) {
            DB::statement("ALTER TABLE deposits ADD COLUMN payment_details LONGTEXT NULL AFTER payment_method");
            echo "    ✓ Added payment_details column\n";
        }
    }

    // Test data: Create sample payment methods if table is empty
    $count = \App\Models\PaymentMethod::count();
    echo "[4] Existing Payment Methods: " . $count . "\n\n";

    if ($count === 0) {
        echo "Creating sample payment methods...\n";
        
        \App\Models\PaymentMethod::create([
            'type' => 'qr',
            'title' => 'Google Pay QR',
            'is_active' => true,
            'sort_order' => 1,
            'notes' => 'Scan to pay via Google Pay',
        ]);
        echo "✓ Added: Google Pay QR\n";

        \App\Models\PaymentMethod::create([
            'type' => 'upi',
            'title' => 'UPI Payment',
            'upi_id' => 'merchant@upi',
            'is_active' => true,
            'sort_order' => 2,
            'notes' => 'Direct UPI transfer',
        ]);
        echo "✓ Added: UPI Payment\n";

        \App\Models\PaymentMethod::create([
            'type' => 'bank',
            'title' => 'Bank Transfer',
            'bank_name' => 'HDFC Bank',
            'account_holder_name' => 'BittGold',
            'account_number' => '1234567890',
            'ifsc_code' => 'HDFC0001234',
            'branch_name' => 'Main Branch',
            'is_active' => true,
            'sort_order' => 3,
            'notes' => 'Direct bank transfer',
        ]);
        echo "✓ Added: Bank Transfer\n";
    }

    echo "\n=== Setup Complete! ===\n";
    echo "✓ Database is ready for payment methods\n";
    echo "✓ Admin can now manage payment methods at: /admin/payment-methods\n";
    echo "✓ Users can select payment method at: /user/deposit\n";

} catch (\Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
