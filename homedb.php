<?php

//-----تكليف الاسبوع الاول-----mysqlاتصال بقاعدة بيانات بستخدام -----------------


class Database {
    private $host = "localhost";
    private $db_name = "your_database";
    private $username = "your_username";
    private $password = "your_password";
    private $conn;
    private static $instance = null;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
            
            echo "تم الاتصال بقاعدة البيانات بنجاح";
            
        } catch(PDOException $e) {
            die("خطأ في الاتصال: " . $e->getMessage());
        }
    }

    // Singleton Pattern
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    // تنفيذ استعلام
    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            die("خطأ في الاستعلام: " . $e->getMessage());
        }
    }
}

// استخدام الفئة
$db = Database::getInstance();
$connection = $db->getConnection();

// مثال على استعلام
$stmt = $db->query("SELECT * FROM users WHERE id = ?", [1]);
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo $user['username'];
}




//-------------------------bindParam-----------------


// إنشاء اتصال بقاعدة البيانات
$pdo = new PDO('mysql:host=localhost;dbname=test', 'username', 'password');

// المثال 1: bindParam - الربط بالإشارة (Reference)
echo "<h3>المثال 1: bindParam - الربط بالإشارة</h3>";

$stmt = $pdo->prepare("INSERT INTO employees (name, salary, department) VALUES (:name, :salary, :dept)");

$name = "أحمد محمد";
$salary = 5000;
$department = "المبيعات";

// الربط بالإشارة
$stmt->bindParam(':name', $name);
$stmt->bindParam(':salary', $salary);
$stmt->bindParam(':dept', $department);

echo "القيم قبل التغيير: $name, $salary, $department<br>";

// تغيير القيم بعد الربط
$name = "محمد خالد";
$salary = 7500;
$department = "التسويق";

echo "القيم بعد التغيير: $name, $salary, $department<br>";

$stmt->execute();

// ------------------------------bindValue------------------------------

// المثال 2: bindValue - الربط بالقيمة (Value)
echo "<h3>المثال 2: bindValue - الربط بالقيمة</h3>";

$stmt2 = $pdo->prepare("INSERT INTO employees (name, salary, department) VALUES (:name, :salary, :dept)");

$name2 = "سارة أحمد";
$salary2 = 6000;
$dept2 = "الموارد البشرية";

// الربط بالقيمة
$stmt2->bindValue(':name', $name2);
$stmt2->bindValue(':salary', $salary2);
$stmt2->bindValue(':dept', $dept2);

echo "القيم قبل التغيير: $name2, $salary2, $dept2<br>";

// تغيير القيم بعد الربط
$name2 = "فاطمة علي";
$salary2 = 8000;
$dept2 = "المحاسبة";

echo "القيم بعد التغيير: $name2, $salary2, $dept2<br>";

$stmt2->execute();




//-----------------PDF السيناريوالحرج في ملف------------------//


class BankTransactionPDFProcessor {
    private $pdo;
    private $pdfParser;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * ⚠️  مثال على كود خطير جداً - لا تستخدمه في الإنتاج
     * المشكلة: استخدام bindParam مع متغيرات يتم تعديلها
     */
    public function processTransactionsDangerous($pdfFilePath) {
        // استخراج المعاملات من PDF
        $transactions = $this->extractTransactionsFromPDF($pdfFilePath);
        
        // تحضير الاستعلام
        $stmt = $this->pdo->prepare("
            INSERT INTO bank_transactions (
                transaction_id, 
                account_number, 
                amount, 
                transaction_date,
                beneficiary_account,
                pdf_reference,
                status
            ) VALUES (
                :trans_id, 
                :acc_no, 
                :amount, 
                :trans_date,
                :beneficiary,
                :pdf_ref,
                :status
            )
        ");
        
        // تعريف المتغيرات
        $transId = '';
        $accNo = '';
        $amount = 0;
        $transDate = '';
        $beneficiary = '';
        $pdfRef = basename($pdfFilePath);
        $status = 'pending';
        
        // ⚠️ الخطأ: استخدام bindParam في حلقة مع معالجة متزامنة
        $stmt->bindParam(':trans_id', $transId);
        $stmt->bindParam(':acc_no', $accNo);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':trans_date', $transDate);
        $stmt->bindParam(':beneficiary', $beneficiary);
        $stmt->bindParam(':pdf_ref', $pdfRef);
        $stmt->bindParam(':status', $status);
        
        $insertedIds = [];
        
        foreach ($transactions as $index => $transaction) {
            // تعيين القيم
            $transId = $transaction['id'];
            $accNo = $transaction['account'];
            $amount = $transaction['amount'];
            $transDate = $transaction['date'];
            $beneficiary = $transaction['beneficiary'];
            
            // ⚠️ الخطر: معالجة متزامنة أو تأخير
            // لو حصل أي تأخير أو معالجة متوازية...
            usleep(1000); // تأخير بسيط
            
            // تطبيق عمليات تحقق - قد تعدل المتغيرات!
            $this->validateTransaction($amount, $accNo, $beneficiary);
            
            // ⚠️ ما يحدث هنا: المتغيرات قد تغيرت قبل execute()
            $stmt->execute();
            
            $insertedIds[] = $this->pdo->lastInsertId();
        }
        
        return $insertedIds;
    }
    
    /**
     * ✅ الطريقة الآمنة لمعالجة ملفات PDF
     */
    public function processTransactionsSecure($pdfFilePath) {
        try {
            // بدء معاملة قاعدة بيانات
            $this->pdo->beginTransaction();
            
            // 1. التحقق من أمان ملف PDF
            $this->validatePDFSecurity($pdfFilePath);
            
            // 2. استخراج البيانات
            $transactions = $this->extractTransactionsSecurely($pdfFilePath);
            
            // 3. تسجيل ملف PDF في النظام أولاً
            $pdfRecordId = $this->logPDFFile($pdfFilePath);
            
            // 4. معالجة كل معاملة بشكل منفصل وآمن
            $stmt = $this->pdo->prepare("
                INSERT INTO bank_transactions (
                    transaction_id, 
                    account_number, 
                    amount, 
                    transaction_date,
                    beneficiary_account,
                    pdf_file_id,
                    status,
                    created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, NOW()
                )
            ");
            
            $processedTransactions = [];
            
            foreach ($transactions as $transaction) {
                // ✅ استخدام bindValue مع القيم المباشرة
                $stmt->bindValue(1, $transaction['id'], PDO::PARAM_STR);
                $stmt->bindValue(2, $transaction['account'], PDO::PARAM_STR);
                
                // ✅ التحقق من المبلغ قبل الإدخال
                $validatedAmount = $this->validateAndFormatAmount($transaction['amount']);
                $stmt->bindValue(3, $validatedAmount, PDO::PARAM_STR);
                
                $stmt->bindValue(4, $transaction['date'], PDO::PARAM_STR);
                $stmt->bindValue(5, $transaction['beneficiary'], PDO::PARAM_STR);
                $stmt->bindValue(6, $pdfRecordId, PDO::PARAM_INT);
                $stmt->bindValue(7, 'processed', PDO::PARAM_STR);
                
                // ✅ التنفيذ مع التعامل مع الأخطاء
                if ($stmt->execute()) {
                    $processedTransactions[] = [
                        'id' => $this->pdo->lastInsertId(),
                        'transaction_id' => $transaction['id'],
                        'status' => 'success'
                    ];
                } else {
                    // تسجيل المعاملة الفاشلة
                    $this->logFailedTransaction($transaction, $pdfRecordId);
                }
                
                // إعادة تعيين الاستعلام للدورة التالية
                $stmt->closeCursor();
            }
            
            // تأكيد كل العمليات
            $this->pdo->commit();
            
            // تحديث حالة ملف PDF
            $this->updatePDFStatus($pdfRecordId, 'processed');
            
            return [
                'success' => true,
                'processed_count' => count($processedTransactions),
                'transactions' => $processedTransactions
            ];
            
        } catch (Exception $e) {
            // التراجع عن كل العمليات في حالة الخطأ
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            
            // تسجيل الخطأ
            $this->logError($e, $pdfFilePath);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * ✅ أفضل ممارسة: استخدام الاستعلام المحدد
     */
    public function processTransactionsBestPractice($pdfFilePath) {
        // استخدام استعلام واحد لكل العمليات مع ON DUPLICATE KEY
        $stmt = $this->pdo->prepare("
            INSERT INTO bank_transactions (
                transaction_id, 
                account_number, 
                amount, 
                transaction_date,
                beneficiary_account,
                pdf_reference,
                status,
                created_at
            ) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
            ON DUPLICATE KEY UPDATE 
                amount = VALUES(amount),
                status = IF(status = 'reversed', 'reprocessed', 'updated'),
                updated_at = NOW()
        ");
        
        $transactions = $this->extractTransactionsFromPDF($pdfFilePath);
        $results = [];
        
        foreach ($transactions as $transaction) {
            // ✅ تمرير القيم مباشرة في execute (أكثر أماناً)
            $success = $stmt->execute([
                $transaction['id'],
                $transaction['account'],
                $this->sanitizeAmount($transaction['amount']),
                $transaction['date'],
                $this->validateAccount($transaction['beneficiary']),
                basename($pdfFilePath)
            ]);
            
            $results[] = [
                'transaction_id' => $transaction['id'],
                'success' => $success,
                'message' => $success ? 'تمت المعالجة' : 'فشلت المعالجة'
            ];
        }
        
        return $results;
    }
    
    // ========== الدوال المساعدة ==========
    
    private function validatePDFSecurity($filePath) {
        // التحقق من أن الملف PDF حقيقي
        $mime = mime_content_type($filePath);
        if ($mime !== 'application/pdf') {
            throw new Exception("الملف ليس بصيغة PDF صالحة");
        }
        
        // التحقق من حجم الملف
        $fileSize = filesize($filePath);
        if ($fileSize > 10 * 1024 * 1024) { // 10MB
            throw new Exception("حجم ملف PDF يتجاوز الحد المسموح");
        }
        
        // فحص الفيروسات (محاكاة)
        if (!$this->scanForViruses($filePath)) {
            throw new Exception("تم اكتشاف تهديد أمني في الملف");
        }
        
        return true;
    }
    
    private function extractTransactionsSecurely($filePath) {
        // استخدام مكتبة آمنة لاستخراج بيانات PDF
        // هنا نستخدم بيانات وهمية للتوضيح
        return [
            [
                'id' => 'TRX' . uniqid(),
                'account' => 'SA0380000000608010167519',
                'amount' => 15000.50,
                'date' => date('Y-m-d H:i:s'),
                'beneficiary' => 'SA0330000000608010167520'
            ],
            [
                'id' => 'TRX' . uniqid(),
                'account' => 'SA0380000000608010167519',
                'amount' => 5000.75,
                'date' => date('Y-m-d H:i:s'),
                'beneficiary' => 'SA0330000000608010167530'
            ]
        ];
    }
    
    private function validateAndFormatAmount($amount) {
        // التأكد من أن المبلغ رقم موجب
        $amount = floatval($amount);
        if ($amount <= 0) {
            throw new Exception("المبلغ يجب أن يكون موجباً");
        }
        
        if ($amount > 1000000) { // حد أقصى للمعاملات
            throw new Exception("المبلغ يتجاوز الحد المسموح للمعاملة الواحدة");
        }
        
        // تنسيق المبلغ برقمين عشريين
        return number_format($amount, 2, '.', '');
    }
    
    private function logPDFFile($filePath) {
        $stmt = $this->pdo->prepare("
            INSERT INTO pdf_files 
            (file_name, file_path, file_size, uploaded_at, status) 
            VALUES (?, ?, ?, NOW(), 'uploaded')
        ");
        
        $stmt->execute([
            basename($filePath),
            $filePath,
            filesize($filePath)
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    private function logError($exception, $filePath) {
        $stmt = $this->pdo->prepare("
            INSERT INTO error_logs 
            (error_message, file_path, created_at) 
            VALUES (?, ?, NOW())
        ");
        
        $stmt->execute([
            $exception->getMessage(),
            $filePath
        ]);
    }
}

// ========== مثال الاستخدام ==========

$pdo = new PDO(
    'mysql:host=localhost;dbname=bank_system;charset=utf8mb4',
    'secure_user',
    'StrongPassword123!',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false, // مهم للأمان
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

$processor = new BankTransactionPDFProcessor($pdo);

// معالجة ملف PDF بطريقة آمنة
$result = $processor->processTransactionsSecure('/path/to/bank_statement.pdf');

if ($result['success']) {
    echo "✅ تمت معالجة {$result['processed_count']} معاملة بنجاح";
} else {
    echo "❌ فشلت المعالجة: {$result['error']}";
}
?>