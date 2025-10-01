<?php
/**
 * Simple API Test Script
 * اختبار بسيط لـ API
 */

$baseUrl = 'http://localhost:8000/api/v1';
$authBaseUrl = 'http://localhost:8000/api';

echo "=== Tourism Website API Test ===\n\n";

// Test Authentication
echo "0. Testing Authentication...\n";
$loginData = json_encode([
    'email' => 'admin@example.com',
    'password' => 'admin123'
]);

$loginContext = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $loginData
    ]
]);

$loginResponse = file_get_contents($authBaseUrl . '/login', false, $loginContext);
$loginResult = json_decode($loginResponse, true);

if ($loginResult && $loginResult['success']) {
    echo "✅ Authentication working\n";
    echo "   Token: " . substr($loginResult['data']['token'], 0, 20) . "...\n";
    echo "   User: " . $loginResult['data']['user']['name'] . "\n";
    $token = $loginResult['data']['token'];
} else {
    echo "❌ Authentication failed\n";
    echo "   Response: " . $loginResponse . "\n";
    exit(1);
}
echo "\n";

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
$searchQuery = urlencode('مسقط');
$response = file_get_contents($baseUrl . '/search?q=' . $searchQuery);
$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "✅ Search API working\n";
    echo "   Search query: " . $data['query'] . "\n";
    echo "   Total results: " . $data['data']['total_results'] . "\n";
} else {
    echo "❌ Search API failed\n";
    echo "   Response: " . $response . "\n";
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

// Test 9: Admin API with Authentication
echo "9. Testing Admin API with Authentication...\n";
$adminData = json_encode([
    'name_ar' => 'الباطنة الشمالية',
    'name_en' => 'North Al Batinah',
    'description_ar' => 'محافظة الباطنة الشمالية',
    'description_en' => 'North Al Batinah Governorate'
]);

$adminContext = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nAuthorization: Bearer $token",
        'content' => $adminData
    ]
]);

$adminResponse = file_get_contents($baseUrl . '/admin/governorates', false, $adminContext);
$adminResult = json_decode($adminResponse, true);

if ($adminResult && $adminResult['success']) {
    echo "✅ Admin API working with authentication\n";
    echo "   Created governorate: " . $adminResult['data']['name_ar'] . "\n";
} else {
    echo "❌ Admin API failed\n";
    echo "   Response: " . $adminResponse . "\n";
}
echo "\n";

// Test 10: Test /me endpoint
echo "10. Testing /me endpoint...\n";
$meContext = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer $token"
    ]
]);

$meResponse = file_get_contents($authBaseUrl . '/me', false, $meContext);
$meResult = json_decode($meResponse, true);

if ($meResult && $meResult['success']) {
    echo "✅ /me endpoint working\n";
    echo "   User: " . $meResult['data']['user']['name'] . " (" . $meResult['data']['user']['email'] . ")\n";
} else {
    echo "❌ /me endpoint failed\n";
    echo "   Response: " . $meResponse . "\n";
}
echo "\n";

echo "=== API Test Complete ===\n";
echo "Note: Make sure your Laravel server is running (php artisan serve)\n";
echo "and your database is properly configured.\n";
?>
