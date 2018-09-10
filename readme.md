<b>Laravel 5.7.1<br />
PostgreSQL 10.5<br />
PHP 7.2.8</b><br /><br/>
Получение списка новостей:<br />
<i>GET /news/{slug}</i><br />
Параметры:<br />
<i>from</i> - время и дата от, YYYY-MM-DD HH:MI:SS<br />
<i>to</i> - время и дата до, YYYY-MM-DD HH:MI:SS<br />
<i>header</i> - заголовок<br /><br />
Добавление новостей:<br />
<i>POST /news</i><br />
Параметры:<br />
<i>content</i> - контент <br />
<i>header</i> - заголовок<br />
<i>preview</i> или <i>guid</i> - url превью или guid изображения из галереи<br />
<i>slug</i> - текст url<br /><br/>
Редактирование новостей (admin):<br />
<i>PUT /news/{id}</i><br />
Параметры:<br />
<i>content</i> - контент <br />
<i>header</i> - заголовок<br />
<i>preview</i> или <i>guid</i> - url превью или guid изображения из галереи<br />
<i>slug</i> - текст url<br /><br/>
Удаление новостей (admin):<br />
<i>DELETE /news/{id}</i><br /><br />
Получение списка изображений:<br />
<i>GET /images/{guid}</i><br />
Параметры:<br />
<i>tags[]</i> - массив тегов<br /><br />
Добавление изображений:<br />
<i>POST /images</i><br />
Параметры:<br />
<i>image</i> - файл изображения<br />
<i>description</i> - описание<br />
<i>tags[]</i> - массив тегов<br /><br />
Редактирование изображений (admin):<br />
<i>POST /images{guid}</i><br />
Параметры:<br />
<i>image</i> - файл изображения<br />
<i>description</i> - описание<br />
<i>tags[]</i> - массив тегов<br /><br />
Удаление изображений (admin):<br />
<i>DELETE /images/{guid}</i><br /><br />
Для admin необходмо передавать дополнительно параметр <i>password</i> = bacb2f0c06c9d83b3e93570e568eb49c 
