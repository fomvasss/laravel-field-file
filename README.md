# Laravel FieldFile
Пакет для создания и управления файловыми полями в Laravel (картинки, документы)

## Установка
Запустить:
```bash
	composer require fomvasss/laravel-field-file
```

Для Laravel > 5.5 добавить в сервис-провайдер в app.php
```php
	Fomvasss\Taxonomy\FieldFileServiceProvider::class,
```

Опубликовать конфиг, миграцию, файлы локализации:
```bash
	php artisan vendor:publish --provider="Fomvasss\FieldFile\FieldFileServiceProvider"
```
Запустить миграцию
```bash
	php artisan migrate
```

---
## Использование

### Загрузка

#### Роуты пакета
**Документы:**
* POST: /file/document/upload
* POST: /file/document/upload-multiple
* GET: /file/document/get-file/{id}
* GET: /file/document/delete/{id}
    
**Изображения:**
* POST: /file/image/upload
* POST: /file/image/upload-multiple
* GET: /file/image/get-file/{id}
* GET: /file/image/delete/{id}

Если не будут используватся стандартные роуты и контроллеры пакате, то в конфигах их можно отключить: `'routes.use' => false`

Можно использовать следующии менеджеры для соотв. типов файлов:
```php
use Fomvasss\FieldFile\Managers\DocumentFileManager;
use Fomvasss\FieldFile\Managers\ImageFileManager
```

```php
<?php
public function __construct(ImageFileManager $manager)
{
	$this->manager = $manager;
}

public function upload($request)
{
	if ($request->hasFile('image')) {
		$result = $this->manager->store($request->file('image'));

		return $result;
	}
	return 0;
}
```

Если будут использоватся свои контроллеры, то они могут наследовать базовый контроллер пакета:
```php
Fomvasss\FieldFile\Http\Controllers\BaseFileController
```
и в которых в конструкторе указать нужный менеджер и доступные имена полей:
```php
public function __construct(\Fomvasss\FieldFile\Managers\ImageFileManager $manager)
{
	$this->manager = $manager;
	$this->allowedFieldNames = ['image', 'img', 'file'];
}
```

Метод `store($requestFile, array $attr = [], $returnModel = false)` менеджера картинок принимает:
- $requestFile - файл для загрузки
- $attr - массив параметров:
	- path - начальный путь к папке для файла (если нет - будет по умолчанию, заданные в конфигу),
	- image_makers[] - массив своих классов построителей картинок, 
	- type - тип файла (image, document,...)
- $returnModel - тип возвращаемого результата (по молчанию ID файла)

Аналогичные параметры принимает менеджер документов, кроме массива image_makers

Пример метода `make()` построителя картинок:
```php
<?php
public function make()
    {
        $userId = \Auth::id();

        $userPath = $this->checkMakeDir($this->path . '/'.$userId);

        Image::make($this->image)
            ->resize(360, 280, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($userPath.'/' . $this->fileName.'.'.$this->format, $this->compress);
    }
```

Код построителя полностью можно увидеть в примере:
```php
Fomvasss\FieldFile\Services\ImageMaker\ExampleImageMaker
```
### Использование связей в своих моделях
Вставте нужные методы для соотв. полей в свои модели
```php
<?php
	/**
     * Сущность Post имеет много полей файлов-документов
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fieldDocs()
    {
        return $this->morphMany(FieldDoc::class, 'field_docable');
    }

    /**
     * Сущность Post имеет много полей файлов-изображений
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fieldImages()
    {
        return $this->morphMany(FieldImage::class, 'field_imageable');
    }

    /**
     * Сущность Post имеет одно поле файл-логотип
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fieldAvatar()
    {
        return $this->morphOne(FieldAvatar::class, 'field_avatarable');
    }
```

или же просто используйте трейт:
```php
use \Fomvasss\FieldFile\Models\Traits\HasFieldFile;
```

### Связывание поля-файла со своей моделью
```php
<?php
$post = \App\Models\Post::find(1);
$fieldImage = ['file_id' => 1, 'alt' => 'Изображение', 'title' => 'Это изображение', 'weight' => 22];
$post->fieldImages()->save(new \Fomvasss\FieldFile\Models\FieldImage($fieldImage));

$fieldDoc = ['file_id' => 3, 'title' => 'Мой документ', 'weight' => 33];
$post->fieldDoc()->save(new \Fomvasss\FieldFile\Models\FieldDoc($fieldDoc));

$fieldAvatar = ['file_id' => 1, 'alt' => 'Аватар', 'title' => 'Это аватар'];
$post->fieldAvatar()->save(new \Fomvasss\FieldFile\Models\FieldAvatar($fieldAvatar));
```

Можно использовать метод `saveMany()` для сохранение нескольких полей-изобреженией или полей-документов одновременно
Также можно использовать `create()` :
```php
    $post->fieldImages()->create(['file_id' => 2, 'alt' => 'Изображение', 'title' => 'Это тоже изображение']);
```

### Отвязывание поля файла от своей модели
Для отвязывания поля-файла от своей модели, вы можете просто удалить нужный файл, используя метод `safeDelete()` соотв. менеджера.
Файл станет не используемым (`is_used` = 0) и после некоторого времени его можно удалять полносью с диска и бд используя метод `delete()` или специальную, ниже описанную, команду `php artisan field-file`

### Команды работы с пакетом
Удалить все неиспользуемые файлы с бд и диска, которые были загружены больше 7 часов назад: 
```bash
	php artisan field-file:remove 7
```
