<?php
// 1. strlen() -  
echo strlen("Hello"); // 5

// 2. trim() -    
echo trim("  Hello  "); // "Hello"

// 3. strtolower() -   
echo strtolower("HELLO"); // "hello"

// 4. strtoupper() -   
echo strtoupper("hello"); // "HELLO"

// 5. ucfirst() -   
echo ucfirst("hello"); // "Hello"

// 6. ucwords() -      
echo ucwords("hello world"); // "Hello World"

// 7. str_replace() -  
echo str_replace("World", "PHP", "Hello World"); // "Hello PHP"

// 8. str_ireplace() -     
echo str_ireplace("WORLD", "PHP", "Hello World"); // "Hello PHP"

// 9. substr() -   
echo substr("Hello World", 6); // "World"
echo substr("Hello World", 0, 5); // "Hello"

// 10. strpos() -   
echo strpos("Hello World", "World"); // 6

// 11. stripos() -     
echo stripos("Hello WORLD", "world"); // 6

// 12. str_contains() -    
echo str_contains("Hello World", "World") ? 'نعم' : 'لا'; // نعم

// 13. str_starts_with() -    
echo str_starts_with("Hello World", "Hello") ? 'نعم' : 'لا'; // نعم

// 14. str_ends_with() -    
echo str_ends_with("Hello World", "World") ? 'نعم' : 'لا'; // نعم

// 15. explode() -   
print_r(explode(",", "apple,banana,orange")); // ['apple','banana','orange']

// 16. implode() -   
echo implode(" - ", ['apple', 'banana', 'orange']); // "apple - banana - orange"

// 17. str_split() -    
print_r(str_split("hello")); // ['h','e','l','l','o']

// 18. str_repeat() -  
echo str_repeat("", 5); // "**"

// 19. str_pad() -  
echo str_pad("Hi", 5, "", STR_PAD_BOTH); // "Hi"

// 20. strrev() -  
echo strrev("Hello"); // "olleH"

// 21. str_word_count() - 
echo str_word_count("Hello World PHP"); // 3

// 22. htmlspecialchars() -  HTML
echo htmlspecialchars("<div>Hello</div>"); // "&lt;div&gt;Hello&lt;/div&gt;"

// 23. strip_tags() -  HTML
echo strip_tags("<p>Hello</p>"); // "Hello"

// 24. nl2br() -  <br>
echo nl2br("Hello\nWorld"); // "Hello<br />World"

// 25. md5() -  MD5
echo md5("hello"); // "5d41402abc4b2a76b9719d911017c592"

// 26. sha1() -  SHA1
echo sha1("hello"); // "aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d"

// 27. base64_encode() -  base64
echo base64_encode("Hello"); // "SGVsbG8="

// 28. base64_decode() - base64
echo base64_decode("SGVsbG8="); // "Hello"

// 29. urlencode() -  URL
echo urlencode("name=John Doe"); // "name%3DJohn+Doe"

// 30. json_encode() -  JSON
echo json_encode(['name' => 'John', 'age' => 25]); // '{"name":"John","age":25}'
?>

//----------------------------------------------------------------------//

<?php

// 1.  array_push  
$cart = [];
array_push($cart, "Laptop"); 
array_push($cart, "Mouse", "Keyboard"); 
echo "سلة التسوق: " . implode(", ", $cart) . "\n";

// 2. array_pop 
$removed = array_pop($cart);
echo "تم إزالة: $removed\n";
echo "السلة الآن: " . implode(", ", $cart) . "\n";

// 3. array_unshift 
array_unshift($cart, "USB Cable");
echo "بعد إضافة منتج عاجل: " . implode(", ", $cart) . "\n";

// 4. array_column 
$users = [
    ["name" => "أحمد", "age" => 25, "city" => "الرياض"],
    ["name" => "محمد", "age" => 30, "city" => "جدة"],
    ["name" => "فاطمة", "age" => 22, "city" => "الدمام"]
];

$names = array_column($users, 'name');
echo "أسماء المستخدمين: " . implode(", ", $names) . "\n";

// 5. array_filter 
$adults = array_filter($users, function($user) {
    return $user['age'] > 25;
});
echo "المستخدمين فوق 25 سنة: " . count($adults) . "\n";

// 6. array_reduce 
$total_age = array_reduce($users, function($carry, $user) {
    return $carry + $user['age'];
}, 0);
$average_age = $total_age / count($users);
echo "متوسط الأعمار: $average_age\n";

// 7.  lookup array 
$user_lookup = array_combine(
    array_column($users, 'name'),
    $users
);
echo "بيانات أحمد: ";
print_r($user_lookup["أحمد"]);

// 8. array_multisort  
$ages = array_column($users, 'age');
array_multisort($ages, SORT_ASC, $users);
echo "المستخدمين مرتبين حسب العمر:\n";
foreach ($users as $user) {
    echo "- {$user['name']} ({$user['age']})\n";
}

// 9. array_count_values 
$products = ["Laptop", "Mouse", "Keyboard", "Mouse", "Monitor", "Keyboard"];
$product_count = array_count_values($products);
echo "إحصائيات المنتجات:\n";
print_r($product_count);

// 10. array_chunk   
$all_users = range(1, 10); // مستخدمين من 1 إلى 10
$groups = array_chunk($all_users, 3);
echo "المستخدمين مقسمين لمجموعات:\n";
print_r($groups);

// 11. array_key_exists    
$colors = ["red" => "#FF0000", "green" => "#00FF00", "blue" => "#0000FF"];
if (array_key_exists("red", $colors)) {
    echo "اللون الأحمر موجود بقيمة: " . $colors["red"] . "\n";
}

// 12. array_merge      
$default_settings = ["theme" => "light", "language" => "ar", "notifications" => true];
$user_settings = ["theme" => "dark", "timezone" => "Riyadh"];
$final_settings = array_merge($default_settings, $user_settings);
echo "الإعدادات النهائية:\n";
print_r($final_settings);

// 13.   
$serial_numbers = array_fill(0, 5, "SN-");
$serial_numbers = array_map(function($value, $index) {
    return $value . ($index + 1);
}, $serial_numbers, array_keys($serial_numbers));
echo "الأرقام التسلسلية: " . implode(", ", $serial_numbers) . "\n";

// 14. array_reverse   
$steps = ["الخطوة 1", "الخطوة 2", "الخطوة 3", "الخطوة 4"];
$reversed_steps = array_reverse($steps);
echo "الخطوات بترتيب عكسي: " . implode(" ← ", $reversed_steps) . "\n";

// 15. array_rand   
$questions = ["سؤال 1", "سؤال 2", "سؤال 3", "سؤال 4", "سؤال 5"];
$random_questions = array_rand($questions, 3);
echo "أسئلة عشوائية:\n";
foreach ($random_questions as $index) {
    echo "- {$questions[$index]}\n";
}
?>