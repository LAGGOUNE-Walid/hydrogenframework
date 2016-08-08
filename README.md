# hydrogen-framework
فريمورك هيدروجين هو اطار عمل مصغر يكفي لعمل المواقع البسيطة .

## Download docs :
https://drive.google.com/open?id=0B_qWn_IYeBMxQWJuTWhXbzhIdEk
التوثيق عبارة عن ملف html


### توثيق هيدروجين 3.2

#### المتطلبات :

*   php >= 5.5.9
*   PDO
*   Mcrypt

* * *

#### محتويات التوثيق

*   ضبط الاعدادات
*   انشاء ال Routes
*   ربط ال Routes مع ال controller
*   ارسال بيانات الى View
*   تضمين ملفات style / js و الصور في ال view
*   اعادة توجيه المستخدمين
*   تصفية و تحليل طلبات HTTP
*   عمل HTTP cache
*   فلترة المدخلات با كلاس Validation
*   انشاء Model و تضمينه في Controller
*   اخراج البيانات من قاعدة البيانات
*   إدخال البيانات
*   بعض العمليات الاخرى على قاعدة البيانات
*   تخصيص ال query
*   انشاء Json web service بسيط
*   التعامل مع الجلسات Sessions
*   انشاء نضام تسجيل دخول المستخدمين
*   الفائدة من ال Interfaces
*   تحميل مكتبات خارجية و تضمينها في الفريمورك
*   ارسال الرسائل عبر بروتوكول smtp
*   تضمين كلاساتك الخاصة في الفريمروك

### ضبط اﻹعدادات :

<small>Kernel/config.ini</small>  
**[Database] : اعدادات بيانات قاعدة البيانات**  
**[Auth] : اعدادات جدول المستخدمين <ins>table : اسم الجدول usernameColumn : اسم حقل اليوزر passwordColumn : اسم حقل كلمة السر</ins>**  
**[phpMailer] : اعدادات ال smtp mailer**  
**[Libraries] : مسار المكتبات التي تريد تضمينها**  

### انشاء ال Routes :

<small>Kernel/Routes/routes.php</small>  
<xmp><?php return $this->add([ '/postData' => "HomeController.post|POST", '/user/id' => "HomeController.getData|GET", '/home' => "HomeController.index|GET" ]);</xmp>

المعامل الاول هو عنوان الRoute , المعامل الثاني هو اسم controller و يليه اسم الدالة و تليها نوع الطلب المفترض ان يكون في ذلك الRoute

### ربط ال Route مع ال Controller :

<small>Kernel/Controllers/YOUR_CONTROLLER_NAME.php</small>  
<small>exp : Kernel/Controllers/HomeController.php</small>  
<xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\User as User; class HomeController extends BaseController { public function index() { return $this->View->show("home"); } public function getData($params) { /* Params var used to get values from url */ echo "Your id is $params['id']"; } }</xmp>  

### Views:

<small>views/YOUR_VIEW_NAME.php</small>  
<small>exp : views/home.php</small>  
لعرض ال Views من ال Controller :  
<xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\User as User; class HomeController extends BaseController { public function index() { /* this controller in this function will show views/home.php */ return $this->View->show("home"); } }</xmp>  

اﻹرسال بيانات الى view :

<xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\User as User; class HomeController extends BaseController { public function index() { $this->View->name = "walid"; $this->View->lastName = "laggoune"; return $this->View->show("home"); } }</xmp> <small>views/home.php</small> <xmp><? echo $this->name; ?> /* output = walid */</xmp>  

### تضمين ملفات الستايل ... :

<xmp>views/home.php link rel="stylesheet" href="<? echo $this->url("views/style.css"); ?>" media="screen" title="no title" charset="utf-8" img src="<? echo $this->url("views/logo.png"); ?>" ...</xmp>  

### اعادة توجيه المستخدمين :

<small>Kernel/home.php</small>  
<xmp><?php /* views/home.php */ $this->redirect("user");</xmp> <xmp><?php /* HomeController */ use Kernel\controllers\BaseController; use Kernel\Models\User as User; class HomeController extends BaseController { public function index() { $this->View->redirect("login"); } }</xmp>  

### تصفية و تحليل طلبات HTTP :

بعد انشاء صفحة ارسال بيانات POST الى صفحة postData سا نقوم با تحليل هذه البيانات كا التالي :

<xmp>/* for inputs */ $username = $this->Request->get("username"); /* for files */ $fileName = $this->Request->file("fileName")->getName(); $fileSize_MB = $this->Request->file("fileName")->getSize(); $fileSize_Bytes = $this->Request->file("fileName")->size(); $fileExtension = $this->Request->file("fileName")->getExtension(); $fileMemetype = $this->Request->file("fileName")->getMimetype(); $fileTmpPath = $this->Request->file("fileName")->getTmp(); /* get Info */ print_r($this->Request->info()); /* get Header */ print_r($this->Request->getHeader("foo"));</xmp>  

### عمل HTTP cache :

اذا كان عندك view لا تحصل له تعديلات كثيرة , يمكن ان تعمل له كاش في المتصفح ليضهر سريعا للمستخدم دون ان يسبب ضغط على السيرفر

<xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\User as User; class HomeController extends BaseController { public function index() { $this->HttpCache->make("home"); $this->View->show("home"); } }</xmp> المعامل الاول من دالة make هو اسم ال View .  

<pre>Second request  = response HTTP/1.1 304 Not Modified</pre>

سا تحفظ الصفحة و محتوايتها و الصور و ملفات الستايل ...  

### فلترة المدخلات با كلاس Validation :

<xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\User as User; class HomeController extends BaseController { public function index() { $username = $this->Request->get("username"); $password = $this->Request->get("password"); $validate = $this->Validator->make([ "username.$username" => ["min:5","max:20"] , "password.$password" => ["min:5"] ]); if(sizeof($validate->errors)) { foreach($validate->errors['0'] as $error) { echo $error; } }else { echo "No errors"; // the rest of code ... } } }</xmp>

المعامل الاول من دالة make عبارة عن Array محتوياتها تكون :

*   1 - اسم المدخل.القيمة مثال : username.$username
*   2 - يكون عبارة عن Array محتوياتها تكون عن القواعد التي سا تفلتر بها المدخل

#### القواعد المرفقة مع هيدروجين :

*   min:(int) example : min:2
*   max:(int) example : max:2
*   required:(bool) example : required:true | required:false
*   xss:(bool) example : xss:true | required:false <small>Clean html and javascript tags</small>
*   type:(string) example : type:string | type:num | type:email | type:url | type:Aurl<small>Active url</small>

### جلب الاخطاْ الناتجة عن الفلترة

<xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\User as User; class HomeController extends BaseController { public function index() { $username = $this->Request->get("username"); $password = $this->Request->get("password"); $validate = $this->Validator->make([ "username.$username" => ["min:5","max:20"] , "password.$password" => ["min:5"] ]); /* هنا لجلب الاخطاء */ if(sizeof($validate->errors)) { foreach($validate->errors['0'] as $error) { echo $error; } }else { echo "No errors"; // the rest of code ... } /* نهاية جلب الاخطاء */ } }</xmp>

### جلب قيمة المدخل بعد عمليه clean xss :

<xmp>echo $validate->xss[0]["username"]; echo $validate->xss[0]["password"]; ...</xmp>  

### انشاء Model و تضمينه في Controller :

<small>Kernel/Models/YOUR_MODEL_NAME.php</small>

example : Kernel/Models/Post.php

<xmp><?php namespace Kernel\Models; use Kernel\Models\Model as Model; class Post extends Model { public function getPosts() { /* Use Database functions */ } }</xmp>

هذه هي الطريقة الصحيحة لانشاء Model .

**الاستخدام هذا Model في أي controller** <xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\Post as Post; /* Here how to use the Post Model */ class HomeController extends BaseController { public function index() { $post = new Post; $posts = $post->getPosts(); } }</xmp>  

### اخراج البيانات من قاعدة البيانات :

بعد ضبط اعدادات قاعدة البيانات في ملف config.ini , و انشاء Model للتعامل مع قاعدة البيانات و ارفاقه في Controller . سا نشرح كيفية استخراج البيانات الموجودة في قاعدة البيانات

<xmp><?php namespace Kernel\Models; use Kernel\Models\Model as Model; class Post extends Model { public function getPosts() { return $this->Database->get("blogs")->all(); } }</xmp>

المعامل الاول من دالة get هو اسم الجدول , all الاستخراج جميع البيانات

<small>البيانات المسترجعة تكون على شكل array فيها Objects</small>  

### إدخال البيانات :

<xmp><?php namespace Kernel\Models; use Kernel\Models\Model as Model; class Post extends Model { public function savePost() { return $this->Database->save("blogs",["title","content"],["I am the title","i am the content"]); } }</xmp>

المعامل الاول من دالة save هو اسم الجدول و المعامل الثاني عبارة عن Array فيها اسماء الحقول في هذا الجدول و المعامل الثالث عبارة عن Array فيها البيانات التي تريد حفظها .

### بعض العمليات الاخرى على قاعدة البيانات :

**Where :** <xmp>return $this->Database->from("blogs")->where("id","=",2);</xmp> **orderByDesc :** <xmp>return $this->Database->get("blogs")->orderByDesc()->all();</xmp> **count :** <xmp>return $this->Database->get("blogs")->count(); Or return $this->Database->from("blogs")->where("title","=","hello")->count();</xmp> **limit :** <xmp>return $this->Database->from("blogs")->where("title","=","hello")->limit(1)->all(); Or return $this->Database->get("blogs")->limit(2)->all();</xmp> **in :** <xmp>return $this->Database->from("blogs")->in("title","hello")->all();</xmp> **mWhere (Mega where !)** <xmp>return $this->Database->from("blogs")->mWhere([ "id='2'", "title='hello'", ])->all();</xmp> <small>دالة mWhere تسمح لك با عمل عمليتين where في نفس الوقت</small>  

### تخصيص ال query :

<xmp>return $this->Database->runSql("SELECT * FROM blogs ORDER BY id desc LIMIT 2"); // ...</xmp> <small>دالة runSql تسمح لك با تشغيل الاستعلام الخاص بك و تخصيصه كما تريد انت</small>  

### انشاء Json web service بسيط :

<xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\Post as Post; /* Here how to use the Post Model */ class HomeController extends BaseController { public function index() { header('Content-Type: application/json'); echo $this->Database->JsonResponse("blogs"); } }</xmp> المعامل الاول من دالة JsonResponse هو اسم الجدول .  

### التعامل مع الجلسات Sessions

<small>في هذا الكود فيه كل دوال كلاس Session</small> <xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\Post as Post; /* Here how to use the Post Model */ class HomeController extends BaseController { public function index() { $this->Session->put("name","walid"); /* store value in the session Key value*/ if($this->Session->has("name")) { /* check if value exists in the session */ echo $this->Session->get("name"); /* Get value from the the session with the key */ $this->Session->destroy("name"); /* destroy value in the session with key */ } pritn_r($this->Session->getAll()); /* get All value stored in the seession */ } }</xmp> **Token && Verify token**  

الانشاء التوكن في فورم ارسال من نوع POST كل ماعليك هو ان :

<xmp>/* homeController */ public function form() { $this->View->token = $this->Session->token(); return $this->View->show("form"); } /* Views/from.php */ ... input type="hidden" name="_token" value="<? echo $this->token; ?>" ...</xmp> <small>وللتحقق من التوكن المرسل :</small> <xmp>/* homeController */ public function postData() { ... $this->Session->verifyToken(); ... }</xmp>  

### انشاء نضام تسجيل دخول المستخدمين :

<small>بعد ضبظ اعدادات ال Auth في config.ini</small>

هيدروجين يوفر لك كلاس جاهز لتنفيذ عميات تسجيل دخول المستخدمين :

<xmp><?php use Kernel\controllers\BaseController; use Kernel\Models\Post as Post; /* Here how to use the Post Model */ class HomeController extends BaseController { public function index() { if($this->User->canLogin("walid","123")) { echo "You can Login"; /* start session ... */ }else { die("You can't login "); } } }</xmp> <small>المعامل الاول من دالة canLogin هو اسم المستخدم و المعامل الثاني هو كلمة المرور . الناتج سا يكون boolean</small>  

### الفائدة من ال Interfaces :

البعض يسألني لماذا تنشئ Interfaces ... الجواب هو أني وضعتها خصيصا للاشخاص الذين يريدون فهم الكلاسات و الدوال التي فيها بدون اﻹطلاع على الكلاس كاملا و سأحاول ان اكتب توثيق الكلاس مع كل Inteface إن شاء الله .

### تحميل مكتبات خارجية و تضمينها في الفريمورك :

سا نجرب على هذا الكلاس : [hhttps://github.com/edwardstock/php-curl-class](https://github.com/edwardstock/php-curl-class)

*   1 - نتوجه الى : libs/composer.json
*   2 - نقوم با ارفاق كود ال require **أو** با التوجه للمسار السابق و فتح الطرفية و لصق امر استدعاء المكتبة
*   3 - تقوم با التوجه الى ملف config.ini ثم تقوم با التوجه ال مكان Libraries و تضيف اسم الكلاس ثم مسار ال Autoloader الخاص با هذه المكتبة
*   4 - يمكنك الان استخدام المكتبة التي حملتها في اي Controller | لديك مثال عن مكتبة Mailer المرفق مع الفريمروك

### ارسال الرسائل عبر بروتوكول smtp :

<small>بعد ضبط اعدادات ال smtp في ملف config.ini</small>

تستطيع استخدام الدوال التي يوفرها لك كلاس Mailer

<xmp>$mail = $this->mail $mail->setFrom('from@example.com', 'Mailer'); $mail->addAddress('joe@example.net', 'Joe User'); // Add a recipient $mail->addAddress('ellen@example.com'); // Name is optional $mail->addReplyTo('info@example.com', 'Information'); $mail->addCC('cc@example.com'); $mail->addBCC('bcc@example.com'); $mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name $mail->isHTML(true); // Set email format to HTML $mail->Subject = 'Here is the subject'; $mail->Body = 'This is the HTML message body <b>in bold!</b>'; $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; if(!$mail->send()) { echo 'Message could not be sent.'; echo 'Mailer Error: ' . $mail->ErrorInfo; } else { echo 'Message has been sent'; }</xmp>  

### تضمين كلاساتك الخاصة في الفريمروك :

*   قم با انشاء كلاسك الخاص با داخل مجلد Kernel مثل | Kernel/myClass/myClass.php
*   الاسماء يجب ان تكون نفسها
*   قم با عمل له namespace .
*   توجه الى ملف BaseController.php قم با عمل use للكلاس الخاص بك .
*   قم عمل متغير له نفس اسم الكلاس
*   توجه الى دالة __construct() ثم قم با تغيير قيمة المتغير من فارغة الى الكلاس .
<xmp>... $this->myClass = new myClass(); ...</xmp>
*   الاستعمال الكلاس في جميع المتحكمات :
<xmp>... $this->myClass->getData(); ... ...</xmp>

