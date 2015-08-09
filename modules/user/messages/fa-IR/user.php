<?php
return [
    'user.attr.username'                =>  'نام کاربری',
    'user.attr.email'                   =>  'ایمیل',
    'user.attr.password'                =>  'کلمه عبور',
    'user.attr.name'                    =>  'نام',
    'user.attr.tagline'                 =>  'تگ لاین',
    
    'user.activity_setting.digest'      =>  'دریافت چندین فعالیت بصورت چکیده',
    'user.activity_setting.full'        =>  'دریافت هر فعالیت بصورت کامل',
    'user.activity_setting.off'         =>  'عدم دریافت',
    'user.reading_list.weekly'          =>  'دریافت هفتگی',
    'user.reading_list.daily'           =>  'دریافت روزانه',
    'user.reading_list.off'             =>  'عدم دریافت',
    
    
    // login
    'login.attr.email'                  =>  'ایمیل/نام کاربری',
    'login.attr.password'               =>  'کلمه عبور',
    'login.attr.rememberMe'             =>  'مرا به یاد داشته باش',
    
    'login.invalidUsernameOrPassword'   =>  'ایمیل/نام کاربری و یا کلمه عبور اشتباه می باشد.',
    'login.muchLoginTry'                =>  'تلاش های فراوان و ناموفق زیادی جهت ورود با این حساب کاربری در مدت کوتاهی انجام شده است،ما برای حفاظت از حساب شما از دست هکرها آن را برای مدت {ttl} ثانیه قفل نمودیم،لطفا بعد از سپری شدن زمان دوباره تلاش نمایید و یا جهت ورود سریع از سرویس های گوگل،فیس بوک،لینکدین و یا گیت هاب در بالا استفاده نمایید.',
    'login.loginBtn'                    =>  'ورود',
    
    
    // join
    'join.socialHeader'                 =>  'عضویت و ورود با دیگر حساب ها',
    'join.byGoogle'                     =>  'عضویت با گوگل',
    'join.byFacebook'                   =>  'عضویت با فیس بوک',
    'join.byLinkedin'                   =>  'عضویت با لینکدین',
    'join.byGithub'                     =>  'عضویت با گیت هاب',
    
    'join.commonHeader'                 =>  'عضویت',
    'join.joinBtn'                      =>  'عضویت',
    'join.terms'                        =>  'عضویت به معنی قبول حقوق متقابل سایت و کاربر می باشد.',
    'join.activation'                   =>  'دریافت مجدد لینک فعال سازی',
    'join.reset'                        =>  'فراموشی کلمه عبور',
    'join.success_join.header'          =>  'تبریک می گوییم',
    'join.success_join.text'            =>  'تبریک می گوییم،عضویت شما با موفقیت انجام شد.ما نیاز به تایید ایمیل شما داریم تا اطمینان حاصل نماییم که شخص دیگری از ایمیل شما برای فعالیت استفاده نمی نماید.به همین جهت لینک فعال سازی حساب را برای ایمیل شما ارسال نمودیم،لطفا بر روی لینک ارسال شده در صندوق پستی ایمیل خود کلیک نمایید تا حساب کاربری شما فعال گردد و بصورت خودکار وارد شوید.',
    'join.resend_activation.text'       =>  'در صورتی که لینک فعال سازی را دریافت نکردید،جهت دریافت مجدد لینک فعال سازی اینجا کلیک نمایید.',
    
    
    //activation
    'activation.attr.email'             =>  'ایمیل/نام کاربری',
    'activation.vld.error'              =>  'ایمیل/نام کاربری اشتباه می باشد و یا این حساب فعال می باشد.',
    
    'activation.header'           =>  'دریافت مجدد لینک فعال سازی',    
    'activation.expired_token.header'   =>  'لینک منقضی شده است',
    'activation.expired_token.text'     =>  '.اعتبار لینک فعال سازی حساب ۲۴ ساعت می باشد و پس از آن منقضی خواهد شد.متاسفانه مدتی بیش از این زمان از ایجاد این لینک سپری شده است و دیگر معتبر نمی باشد،جهت ارسال مجدد لینک فعال سازی معتبر از فرم زیر استفاده نمایید',
    'activation.activationBtn'          =>  'ارسال لینک فعال سازی',
    'activation.sent.header'            =>  'لینک فعال سازی ارسال شد',
    'activation.sent.text'              =>  'لینک فعال سازی حساب با موفقیت ارسال گردید،اعتبار لینک ارسال شده ۲۴ ساعت می باشد و پس از آن غیر فعال خواهد شد.لطفا در صورتی که ایمیل را در پوشه Inbox ایمیل خود نیافتید،پوشه Spam را چک نمایید.',
    
    
    // reset
    'reset.attr.email'                  =>  'ایمیل/نام کاربری',
    
    'reset.vld.error'                   =>  'ایمیل/نام کاربری اشتباه می باشد و یا این حساب غیر فعال می باشد.',
    'reset.expired_token.header'        =>  'لینک منقضی شده است',
    'reset.expired_token.text'          =>  '.اعتبار لینک بازسازی کلمه عبور ۲۴ ساعت می باشد و پس از آن منقضی خواهد شد.متاسفانه مدتی بیش از این زمان از ایجاد این لینک سپری شده است و دیگر معتبر نمی باشد،جهت ارسال مجدد لینک بازسازی کلمه عبور معتبر از فرم زیر استفاده نمایید',    
    'reset.sent.header'                 =>  'لینک بازسازی کلمه عبور ارسال شد',
    'reset.sent.text'                   =>  'لینک بازسازی کلمه عبور با موفقیت ارسال گردید،اعتبار لینک ارسال شده ۲۴ ساعت می باشد و پس از آن غیر فعال خواهد شد.لطفا در صورتی که ایمیل را در پوشه Inbox ایمیل خود نیافتید،پوشه Spam را چک نمایید.',    
    'reset.commonHeader'                =>  'بازسازی کلمه عبور',
    'reset.resetBtn'                    =>  'بازسازی کلمه عبور',
    
    
    // setting
    'setting.setting.header'            =>  'تنظیمات دریافت ایمیل',
    'setting.email.header'              =>  'درخواست تغییر ایمیل',
    'setting.password.header'           =>  'تغییر کلمه عبور',
    'setting.username.header'           =>  'تغییر نام کاربری',
    'setting.email.help.body'           =>  'تغییر ایمیل یک فرآیند دو مرحله می باشد،با ارسال درخواست تغییر ایمیل،یه لینک به ایمیل فعلی شما ارسال می گردد که به مدت ۲۴ ساعت فعال می باشد.با کلیک بر روی لینک ارسالی شما می توانید ایمیل جدید خود را وارد نمایید و پس از آن با کلیک بر روی لینک فعال سازی ایمیل در ایمیل جدید،فرآیند تغییر ایمیل کامل خواهد شد.',
    'setting.password.successful'       =>  'کلمه عبور شما با موفقیت بروزرسانی شد.',
    'setting.setting.successful.header' =>  'تنظیمات دریافت ایمیل با موفقیت بروزرسانی شد.',
    
    // profile
    'profile.public.header'                 =>  'تغییر اطلاعات عمومی',
    'publicProfileForm.attr.profilePicture' =>  'تصویر پروفایل',
    'publicProfileForm.attr.coverPicture'   =>  'تصویر کاور',
    'profile._public.image.select'          =>  'انتخاب',
    'profile._public.image.remove'          =>  'پاک کردن',
    'profile._public.image.change'          =>  'تغییر',
    'profile._public.saveBtn'               =>  'بروزرسانی پروفایل',
    'profile._public.successful.header'     =>  'پروفایل شما با موفقیت بروزرسانی شد، اعمال برخی تغییرات ممکن است چند دقیقه زمان ببرد.',
    
    'publicProfileForm.profile.gravatar'            =>  'تصویر پیش فرض',
    'publicProfileForm.profile.uploadedProfilePic'  =>  'آخرین تصویر پروفایل افزوده شده',    
    'publicProfileForm.cover.noCover'               =>  'بدون کاور',
    'publicProfileForm.cover.uploadedCover'         =>  'آخرین کاور افزوده شده',
    // url
    'profile.urls.header'                   =>  'دیگر رسانه ها',
    'profile.updateUrls'                    =>  'بروزرسانی لینک ها',
    'profile._url.status.on'                =>  'فعال',
    'profile._url.status.off'               =>  'غیر فعال',
    'profile.url.help.body'                 =>  'فعال یا غیر فعال نمودن یک لینک به معنی نمایش ویا عدم نمایش در صفحه پروفایل شما می باشد،در صورتی که قصد پاک کردن لینکی بصورت کامل را دارید فیلد مربوط به لینک را پاک نمایید.',
    'profile.addUrl'                        =>  'افزودن یک رسانه جدید',
    'url.vld.unUniqueUrl'                   =>  'شما {url} را قبلا در میان آدرس دیگر رسانه های خود ثبت نموده اید.',
    'profile._url.add.successful.header'    =>  'رسانه جدید با موفقیت به لیست رسانه های شما اضافه گردید.',
    
    // password
    'password.changePasswordBtn'        =>  'تغییر کلمه عبور',
    'chgPwd.attr.password'              =>  'کلمه عبور فعلی',
    'chgPwd.attr.newPassword'           =>  'کلمه عبور جدید',
    'chgPwd.attr.confirmNewPassword'    =>  'تکرار کلمه عبور جدید',
    'chgPwg.vld.wrongePassword'         =>  'کلمه عبور اشتباه می باشد.',
    
    // setting
    'chgSetting.attr.content_activity'          =>  'نظرات برای نوشته های شما',
    'chgSetting.attr.social_activity'           =>  'فعالیت های اجتماعی',
    'chgSetting.attr.reading_list'              =>  'نوشته های پیشنهاد شده',
    
    'setting._setting.content_activity.hint'    =>  'نحوه مطلع نمودن شما از طریق ایمیل از نظرات ارسال شده روی نوشته ها شما',
    'setting._setting.social_activity.hint'     =>  'نحوه مطلع نمودن شما از دنبال کنندگان جدید و یا پیوستن دوستان ',
    'setting._setting.reading_list.hint'        =>  'دریافت  نوشته های پیشنهاد شده و برتر',
    'setting.changeSettingBtn'                  =>  'تغییر تنظیمات',
    
    // email
    'email.requestBtn'                          =>  'درخواست تغییر ایمیل ',
    'setting.email.step1.successful.header'     =>  'لینک تغییر ایمیل ارسال شد',
    'setting.email.step1.successful.text'       =>  'لینک تغییر ایمیل به   <code>{email}</code>  ارسال گردید،با کلیک بر روی لینک شما به صفحه تغییر ایمیل هدایت می شوید.اعتبار لینک ارسال شده ۲۴ ساعت می باشد و پس از آن منقضی می گردد.',
    'setting.email.step2.successful.header'     =>  'بروزرسانی ایمیل انجام شد.',
    'setting.email.step2.successful.text'       =>  'آدرس ایمیل شما با موفقیت به <code>{email}</code> تغییر یافت.',
    'setting.email.step2.failed.header'         =>  'مشکل در بروزرسانی ایمیل',
    'setting.email.step2.failed.text'           =>  'متاسفانه مشکلی در بروزرسانی آدرس ایمیل حساب بوجود آمده است،لطفا مجددا درخواست تغییر ایمیل را ارسال نمایید.',
    'setting.email.expired.header'              =>  'لینک منقضی شده است',
    'setting.email.expired.text'                =>  'اعتبار لینک تغییر ایمیل ۲۴ ساعت می باشد و پس از آن منقضی خواهد شد.متاسفانه مدتی بیش از این زمان از ایجاد این لینک سپری شده است و دیگر معتبر نمی باشد.لطفا فرآیند تغییر ایمیل را از ابتدا شروع نمایید.',
    
    // username
    'setting.username.successful.header'        =>  'نام کاربری شما با موفقیت تغییر یافت',
    'setting.username.successful.text'          =>  'نام کاربری شما با موفقیت به {username} تغییر یافت،همچنین آدرس نوشته ها و پروفایل شما به تبعیت از نام کاربری به {link} تغییر یافت.',
    'username.changeUsernameBtn'                =>  'تغییر نام کاربری',
    'chgUsername.attr.username'                 =>  'نام کاربری',
    'chgUsername.vld.uniqueUsername'            =>  'در حال حاضر نام کاربری با مقدار "{username}" گرفته شده است.',
    'chgUsername.vld.yourUsername'              =>  '"{username}" نام کاربری فعلی شما می باشد و تغییر آن بی تاثیر می باشد.در صورتی که قصد تغییر نام کاربری خود را ندارید این پیام را نادیده بگیرید.',
    
    
    'setting.head.title'                        =>  'تنظیمات حساب کاربری - '.Yii::t('app','title'),
    'profile.head.title'                        =>  'بروزرسانی پروفایل - '.Yii::t('app','title'),
    'join.head.title'                           =>  'عضویت - '.Yii::t('app','title'),
    'reset.head.title'                          =>  'بازسازی کلمه عبور - '.Yii::t('app','title'),
    'activation.head.title'                     =>  'دریافت مجدد لینک فعال سازی حساب کاربری - '.Yii::t('app','title'),
    
    // meta tags
    'meta.total.site_name'                      =>  'نودز',
    'meta._join.title'                          =>  'عضویت در نودز',
    'meta._join.description'                    =>  'به راحتی و رایگان در نودز عضو شوید.همچنین شما می توانید با استفاده از سرویس های گوگل،فیس بوک،لینکدین و یا گیت هاب وارد نودز شوید. ',
    'meta._activation.title'                    =>  'دریافت مجدد لینک فعال سازی حساب کاربری - نودز',
    'meta._activation.description'              =>  'در صورتی که به هر دلیلی لینک فعال سازی حساب را دریافت نکرده اید و یا از اعتبار آن منقضی شده است ا، می توانید مجددا اقدام نمایید.',
    'meta._reset.title'                         =>  'فراموشی کلمه عبور نودز',
    'meta._reset.description'                   =>  'در صورتی که کلمه عبور خود را فراموش کرده اید، می توانید از طریق این صفحه لینک نوسازی کلمه عبور را در ایمیل خود دریافت نمایید.',
    
    // use in UserController
    'user.githubclient.no_email'                =>  'با توجه به تنظیمات حریم خصوصی حساب کاربری گیت هاب شما،ما قادر به دریافت آدرس ایمیل شما نیستم.برای ادامه لطفا یکی دیگر از روش های ورود و یا ثبت نام را امتحان نمایید.',
    'user.facebookclient.no_email'              =>  'با توجه به تنظیمات حریم خصوصی حساب کاربری فیس بوک شما،ما قادر به دریافت آدرس ایمیل شما نیستم.برای ادامه لطفا یکی دیگر از روش های ورود و یا ثبت نام را امتحان نمایید.',
    'user.googleclient.no_email'                =>  'با توجه به تنظیمات حریم خصوصی حساب کاربری گوگل شما،ما قادر به دریافت آدرس ایمیل شما نیستم.برای ادامه لطفا یکی دیگر از روش های ورود و یا ثبت نام را امتحان نمایید.',
    'user.linkedinclient.no_email'              =>  'با توجه به تنظیمات حریم خصوصی حساب کاربری لینکدین شما،ما قادر به دریافت آدرس ایمیل شما نیستم.برای ادامه لطفا یکی دیگر از روش های ورود و یا ثبت نام را امتحان نمایید.',
];