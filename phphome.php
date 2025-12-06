<?php
//  قراءة ملف كامل
$content = file_get_contents('hashem.txt');
echo "محتوى الملف:<br>" . nl2br($content) . "<br><br>";

// فتح الملف وقراءته سطراً بسطر
$file = fopen('hashem.txt', 'r');
if ($file) {
    echo "قراءة الملف سطراً بسطر:<br>";
    while (($line = fgets($file)) !== false) {
        echo nl2br($line);
    }
    fclose($file);
} else {
    echo "لا يمكن فتح الملف!";
}
echo "<br><br>";

//  قراءة الملف كمصفوفة (كل سطر عنصر)
$lines = file('hashem.txt', FILE_IGNORE_NEW_LINES);
echo "قراءة الملف كمصفوفة:<br>";
print_r($lines);
echo "<br><br>";


//  كتابة محتوى إلى ملف
$data = "السلام عليكم\nهذا مثال لكتابة نص في ملف\n";
file_put_contents('output.txt', $data, FILE_APPEND);
echo "تم كتابة المحتوى إلى الملف.<br><br>";

//  فتح الملف للكتابة
$file = fopen('hashemfile.txt', 'w');
if ($file) {
    fwrite($file, "سطر جديد 1\n");
    fwrite($file, "سطر جديد 2\n");
    fwrite($file, "سطر جديد 3\n");
    fclose($file);
    echo "تم إنشاء ملف جديد.<br><br>";
}

//  نسخ ، إعادة تسمية وحذف الملفات
if (copy('output.txt', 'backup/output_backup.txt')) {
    echo "تم نسخ الملف.<br>";
}

if (rename('newfile.txt', 'renamed_file.txt')) {
    echo "تم إعادة تسمية الملف.<br>";
}

if (file_exists('temp.txt')) {
    unlink('temp.txt');
    echo "تم حذف الملف.<br><br>";
}

//--------------------------------------------------------//


$filename = 'hashem.txt';

// التحقق من وجود الملف
if (file_exists($filename)) {
    echo "المعلومات عن الملف:<br>";
    echo "اسم الملف: " . basename($filename) . "<br>";
    echo "المسار: " . realpath($filename) . "<br>";
    echo "حجم الملف: " . filesize($filename) . " بايت<br>";
    echo "نوع الملف: " . filetype($filename) . "<br>";
    echo "آخر تعديل: " . date('Y-m-d H:i:s', filemtime($filename)) . "<br>";
    echo "آخر وصول: " . date('Y-m-d H:i:s', fileatime($filename)) . "<br><br>";
}

// قراءة مجلد
$dir = '.';
if (is_dir($dir)) {
    echo "قائمة الملفات في المجلد:<br>";
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo $file . "<br>";
        }
    }
}


//--------------------------------------------------------//


echo "<h3>دوال الوقت والتاريخ الأساسية</h3>";

// الوقت الحالي بالثواني منذ 1 يناير 1970
$timestamp = time();
echo "الطابع الزمني الحالي: " . $timestamp . "<br>";

// الوقت الحالي بتنسيق محدد
echo "التاريخ الحالي: " . date('Y-m-d') . "<br>";
echo "الوقت الحالي: " . date('H:i:s') . "<br>";
echo "التاريخ والوقت الكامل: " . date('Y-m-d H:i:s') . "<br>";
echo "اليوم: " . date('l') . "<br>";
echo "الشهر: " . date('F') . "<br>";
echo "السنة: " . date('Y') . "<br><br>";

// تنسيقات مختلفة
echo "تنسيقات مختلفة للتاريخ:<br>";
echo "d/m/Y: " . date('d/m/Y') . "<br>";
echo "m-d-Y: " . date('m-d-Y') . "<br>";
echo "F j, Y: " . date('F j, Y') . "<br>";
echo "jS F Y: " . date('jS F Y') . "<br><br>";

// التحويل من نص إلى طابع زمني
$date_str = "2025-12-2 14:30:00";
$timestamp_from_str = strtotime($date_str);
echo "الطابع الزمني لـ '{$date_str}': " . $timestamp_from_str . "<br>";
echo "التاريخ بعد التحويل: " . date('Y-m-d H:i:s', $timestamp_from_str) . "<br><br>";

// العمليات الحسابية على التواريخ
$today = time();
$next_week = strtotime('+1 week');
$next_month = strtotime('+1 month');
$yesterday = strtotime('-1 day');

echo "اليوم: " . date('Y-m-d', $today) . "<br>";
echo "بعد أسبوع: " . date('Y-m-d', $next_week) . "<br>";
echo "بعد شهر: " . date('Y-m-d', $next_month) . "<br>";
echo "أمس: " . date('Y-m-d', $yesterday) . "<br><br>";

// حساب الفرق بين تاريخين
$date1 = new DateTime('2024-01-01');
$date2 = new DateTime('2024-12-31');
$interval = $date1->diff($date2);
echo "الفرق بين 2024-01-01 و 2024-12-31:<br>";
echo $interval->format('%y سنة، %m شهر، %d يوم') . "<br><br>";


//---------------------------------استخدام كائن----------------------


// إنشاء كائن DateTime
$now = new DateTime();
echo "الوقت الحالي: " . $now->format('Y-m-d H:i:s') . "<br>";

// إنشاء كائن بتاريخ محدد
$birthday = new DateTime('2000-05-1');
echo "تاريخ الميلاد: " . $birthday->format('Y-m-d') . "<br>";

// إضافة وقت
$future_date = clone $now;
$future_date->add(new DateInterval('P10D')); // إضافة 10 أيام
echo "بعد 10 أيام: " . $future_date->format('Y-m-d') . "<br>";

// طرح وقت
$past_date = clone $now;
$past_date->sub(new DateInterval('P1M')); // طرح شهر
echo "قبل شهر: " . $past_date->format('Y-m-d') . "<br><br>";

// مقارنة التواريخ
$date_a = new DateTime('2024-06-01');
$date_b = new DateTime('2025-12-01');

if ($date_a < $date_b) {
    echo "{$date_a->format('Y-m-d')} قبل {$date_b->format('Y-m-d')}<br>";
} else {
    echo "{$date_a->format('Y-m-d')} بعد {$date_b->format('Y-m-d')}<br>";
}


//------------------------------إدارة المناطق الزمنية------------------------


// عرض المنطقة الزمنية الحالية
echo "المنطقة الزمنية الحالية: " . date_default_timezone_get() . "<br>";

// تغيير المنطقة الزمنية العامة
date_default_timezone_set('Asia/Sana’a');
echo "بعد التغيير إلى صنعاء: " . date('Y-m-d H:i:s') . "<br><br>";

// قائمة المناطق الزمنية المتاحة
$timezones = timezone_identifiers_list();
echo "عينة من المناطق الزمنية المتاحة:<br>";
for ($i = 0; $i < 10; $i++) {
    echo $timezones[$i] . "<br>";
}
echo "<br>";

?>