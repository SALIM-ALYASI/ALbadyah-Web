<?php
/**
 * Simple API Test Script
 * اختبار بسيط لـ API
 */

$baseUrl = 'http://localhost:8000/api/v1';

echo "=== Tourism Website API Test ===\n\n";

// Test 1: Get General Stats
echo "1. Testing General Stats...\n";
$response = file_get_contents($baseUrl . '/stats');
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Stats API working\n";
    echo "   Total Governorates: " . $data['data']['total_governorates'] . "\n";
    echo "   Total Wilayats: " . $data['data']['total_wilayats'] . "\n";
    echo "   Total Tourist Sites: " . $data['data']['total_tourist_sites'] . "\n";
    echo "   Total Tourist Services: " . $data['data']['total_tourist_services'] . "\n";
} else {
    echo "❌ Stats API failed\n";
}
echo "\n";

// Test 2: Get Governorates
echo "2. Testing Governorates API...\n";
$response = file_get_contents($baseUrl . '/governorates');
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Governorates API working\n";
    echo "   Found " . count($data['data']) . " governorates\n";
    if (!empty($data['data'])) {
        $first = $data['data'][0];
        echo "   First governorate: " . $first['name_ar'] . " (" . $first['name_en'] . ")\n";
    }
} else {
    echo "❌ Governorates API failed\n";
}
echo "\n";

// Test 3: Get Wilayats
echo "3. Testing Wilayats API...\n";
$response = file_get_contents($baseUrl . '/wilayats');
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Wilayats API working\n";
    echo "   Found " . count($data['data']) . " wilayats\n";
} else {
    echo "❌ Wilayats API failed\n";
}
echo "\n";

// Test 4: Get Tourist Sites
echo "4. Testing Tourist Sites API...\n";
$response = file_get_contents($baseUrl . '/tourist-sites');
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Tourist Sites API working\n";
    echo "   Found " . count($data['data']) . " tourist sites\n";
} else {
    echo "❌ Tourist Sites API failed\n";
}
echo "\n";

// Test 5: Get Tourist Services
echo "5. Testing Tourist Services API...\n";
$response = file_get_contents($baseUrl . '/tourist-services');
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Tourist Services API working\n";
    echo "   Found " . count($data['data']) . " tourist services\n";
} else {
    echo "❌ Tourist Services API failed\n";
}
echo "\n";

// Test 6: Search API
echo "6. Testing Search API...\n";
$response = file_get_contents($baseUrl . '/search?q=مسقط');
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Search API working\n";
    echo "   Search query: " . $data['query'] . "\n";
    echo "   Total results: " . $data['data']['total_results'] . "\n";
} else {
    echo "❌ Search API failed\n";
}
echo "\n";

// Test 7: Record Visit
echo "7. Testing Visit Recording...\n";
$visitData = json_encode([
    'ip_address' => '192.168.1.1',
    'user_agent' => 'Test User Agent',
    'page_url' => 'https://example.com/test',
    'country' => 'Oman',
    'city' => 'Muscat'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $visitData
    ]
]);

$response = file_get_contents($baseUrl . '/visits', false, $context);
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Visit Recording API working\n";
    echo "   Visit ID: " . $data['data']['id'] . "\n";
} else {
    echo "❌ Visit Recording API failed\n";
}
echo "\n";

// Test 8: Get Visit Stats
echo "8. Testing Visit Stats API...\n";
$response = file_get_contents($baseUrl . '/visits/stats');
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Visit Stats API working\n";
    echo "   Total visits: " . $data['data']['overview']['total_visits'] . "\n";
    echo "   Period visits: " . $data['data']['overview']['period_visits'] . "\n";
} else {
    echo "❌ Visit Stats API failed\n";
}
echo "\n";

echo "=== API Test Complete ===\n";
echo "Note: Make sure your Laravel server is running (php artisan serve)\n";
echo "and your database is properly configured.\n";
?>
